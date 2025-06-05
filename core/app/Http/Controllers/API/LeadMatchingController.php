<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Feature;
use App\Models\Company;
use App\Models\RatingDetail;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LeadMatchingController extends Controller
{
    public function getCategoryFeatures($categoryId)
    {
        try {
            // Cache features for 1 hour since they don't change frequently
            $features = Cache::remember("category_features_{$categoryId}", 3600, function() use ($categoryId) {
                return Feature::where('category_id', $categoryId)
                    ->where('status', 1)
                    ->select('id', 'name', 'description')
                    ->get();
            });

            return response()->json($features);
        } catch (\Exception $e) {
            \Log::error('Error loading category features: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi tải features'
            ], 500);
        }
    }

    public function findMatchingContractors(Request $request)
    {
        try {
            $categoryId = $request->input('category_id');
            $districtCode = $request->input('district_code');
            $wardCode = $request->input('ward_code');
            $minRating = $request->input('min_rating', 0);
            $maxContractors = $request->input('max_contractors', 5);
            $featureRequirements = $request->input('feature_requirements', []);

            // Validate required parameters
            if (!$categoryId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category ID is required'
                ], 400);
            }

            // Build base query for companies with eager loading
            $query = Company::with(['user', 'category', 'ratings'])
                ->where('category_id', $categoryId)
                ->where('status', 1)
                ->where('is_approved', 1);

            // Filter by location with fallback to city level if no district matches
            if ($districtCode) {
                $query->where(function($q) use ($districtCode) {
                    $q->where('district', $districtCode)
                      ->orWhere('city', $districtCode); // Fallback to city level
                });
            }

            // Get all companies
            $companies = $query->get();

            if ($companies->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'contractors' => [],
                    'total' => 0,
                    'message' => 'Không tìm thấy thợ phù hợp trong khu vực này'
                ]);
            }

            $matchingContractors = [];

            foreach ($companies as $company) {
                // Calculate average rating with better performance
                $avgRating = $company->ratings->avg('avg_rating') ?: 0;
                
                // Skip if below minimum rating requirement
                if ($avgRating < $minRating) {
                    continue;
                }

                // Get feature ratings for this company (with caching for better performance)
                $featureRatings = $this->getCompanyFeatureRatings($company->id, $categoryId);
                
                // Calculate match score based on feature requirements
                $matchScore = $this->calculateMatchScore($featureRatings, $featureRequirements);

                // Skip companies with very low match scores
                if ($matchScore < 30) {
                    continue;
                }

                $matchingContractors[] = [
                    'id' => $company->id,
                    'name' => $company->name,
                    'image' => getImage(getFilePath('company') . '/' . $company->image, getFileSize('company')),
                    'category' => $company->category->name ?? 'N/A',
                    'avg_rating' => round($avgRating, 1),
                    'review_count' => $company->ratings->count(),
                    'feature_ratings' => $featureRatings,
                    'match_score' => $matchScore,
                    'district' => $this->getDistrictName($company->district),
                    'ward' => $this->getWardName($company->ward),
                    'phone' => $company->mobile ?? $company->user->mobile ?? null,
                    'verified' => $company->is_approved ? true : false,
                ];
            }

            // Sort by match score and limit results
            usort($matchingContractors, function($a, $b) {
                // Primary sort by match score
                if ($b['match_score'] !== $a['match_score']) {
                    return $b['match_score'] <=> $a['match_score'];
                }
                // Secondary sort by rating
                return $b['avg_rating'] <=> $a['avg_rating'];
            });

            $matchingContractors = array_slice($matchingContractors, 0, $maxContractors);

            return response()->json([
                'success' => true,
                'contractors' => $matchingContractors,
                'total' => count($matchingContractors),
                'search_criteria' => [
                    'category_id' => $categoryId,
                    'district' => $districtCode,
                    'min_rating' => $minRating,
                    'feature_requirements_count' => count(array_filter($featureRequirements, function($rating) {
                        return $rating > 0;
                    }))
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error finding matching contractors: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi tìm kiếm thợ phù hợp',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    private function getCompanyFeatureRatings($companyId, $categoryId)
    {
        // Cache feature ratings for 30 minutes
        return Cache::remember("company_features_{$companyId}_{$categoryId}", 1800, function() use ($companyId, $categoryId) {
            $featureRatings = DB::table('rating_details')
                ->join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
                ->join('features', 'rating_details.feature_id', '=', 'features.id')
                ->where('ratings.company_id', $companyId)
                ->where('features.category_id', $categoryId)
                ->where('ratings.status', 1)
                ->select(
                    'features.id',
                    'features.name',
                    DB::raw('AVG(rating_details.rating) as rating'),
                    DB::raw('COUNT(rating_details.rating) as rating_count')
                )
                ->groupBy('features.id', 'features.name')
                ->get()
                ->map(function($item) {
                    $item->rating = round($item->rating, 1);
                    return $item;
                });

            return $featureRatings->toArray();
        });
    }

    private function calculateMatchScore($featureRatings, $featureRequirements)
    {
        if (empty($featureRequirements) || empty($featureRatings)) {
            return 70; // Default score when no specific requirements
        }

        $totalScore = 0;
        $maxScore = 0;
        $matchedFeatures = 0;
        $criticalFeaturesMet = 0;
        $criticalFeaturesTotal = 0;

        foreach ($featureRequirements as $featureId => $requiredRating) {
            if ($requiredRating <= 0) continue; // Skip non-important features

            $maxScore += 100;
            
            // Track critical features (rating 5 requirements)
            if ($requiredRating >= 5) {
                $criticalFeaturesTotal++;
            }
            
            // Find feature rating for this company
            $featureRating = collect($featureRatings)->firstWhere('id', $featureId);
            
            if ($featureRating) {
                $actualRating = $featureRating->rating;
                
                if ($actualRating >= $requiredRating) {
                    // Full score if meets requirement
                    $score = 100;
                    
                    // Track critical features met
                    if ($requiredRating >= 5 && $actualRating >= 5) {
                        $criticalFeaturesMet++;
                    }
                    
                    // Bonus for exceeding requirement
                    if ($actualRating > $requiredRating) {
                        $bonus = min(20, ($actualRating - $requiredRating) * 10);
                        $score += $bonus;
                    }
                } else {
                    // Partial score if below requirement
                    $score = ($actualRating / $requiredRating) * 70;
                }
                
                $totalScore += $score;
                $matchedFeatures++;
            } else {
                // No rating data for this feature - low score
                $totalScore += 10;
            }
        }

        if ($maxScore == 0) {
            return 70;
        }

        $matchScore = ($totalScore / $maxScore) * 100;
        
        // Penalty for missing feature data
        if ($matchedFeatures < count($featureRequirements)) {
            $missingPenalty = (count($featureRequirements) - $matchedFeatures) * 5;
            $matchScore = max(20, $matchScore - $missingPenalty);
        }

        // Bonus for meeting all critical requirements
        if ($criticalFeaturesTotal > 0 && $criticalFeaturesMet === $criticalFeaturesTotal) {
            $matchScore += 5; // Small bonus for meeting all critical requirements
        }

        return round(min(100, $matchScore));
    }

    private function getDistrictName($districtCode)
    {
        // Cache district names for better performance
        return Cache::remember("district_name_{$districtCode}", 86400, function() use ($districtCode) {
            // This should be replaced with actual district lookup from a districts table
            // For now, return a formatted version of the code
            if (!$districtCode) return 'N/A';
            
            // Basic formatting - you should replace this with actual database lookup
            $districtNames = [
                'quan-1' => 'Quận 1',
                'quan-2' => 'Quận 2',
                'quan-3' => 'Quận 3',
                'quan-4' => 'Quận 4',
                'quan-5' => 'Quận 5',
                'quan-6' => 'Quận 6',
                'quan-7' => 'Quận 7',
                'quan-8' => 'Quận 8',
                'quan-9' => 'Quận 9',
                'quan-10' => 'Quận 10',
                'quan-11' => 'Quận 11',
                'quan-12' => 'Quận 12',
                'thu-duc' => 'Thủ Đức',
                'binh-thanh' => 'Bình Thạnh',
                'tan-binh' => 'Tân Bình',
                'tan-phu' => 'Tân Phú',
                'phu-nhuan' => 'Phú Nhuận',
                'go-vap' => 'Gò Vấp',
                'binh-tan' => 'Bình Tân',
            ];
            
            return $districtNames[$districtCode] ?? ucfirst(str_replace('-', ' ', $districtCode));
        });
    }

    private function getWardName($wardCode)
    {
        // Cache ward names for better performance
        return Cache::remember("ward_name_{$wardCode}", 86400, function() use ($wardCode) {
            // This should be replaced with actual ward lookup from a wards table
            if (!$wardCode) return 'N/A';
            
            // Basic formatting - you should replace this with actual database lookup
            return ucfirst(str_replace('-', ' ', $wardCode));
        });
    }
} 