<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Page;
use App\Models\Review;
use App\Models\Company;
use App\Models\Category;
use App\Models\Frontend;
use App\Models\Language;
use App\Constants\Status;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use App\Models\AdminNotification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Models\Rating;
use App\Models\RatingDetail;
use App\Models\Feature;
use App\Models\RatingReaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Str;
use App\Models\User;

use Illuminate\Support\Facades\DB; // Thêm dòng này


class SiteController extends Controller
{
    public function index()
    {
        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function about()
    {
        $pageTitle = "About Us";
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'about-us')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        // $pageTitle = "About Us";
        
        // // Lấy dữ liệu từ bảng frontends
        // $frontend = Frontend::where('tempname', activeTemplate())
        //                   ->where('data_keys', 'about.content')
        //                   ->first();
                          
        // $content = $frontend ? $frontend->data_values : null;
        
        return view('Template::about_us', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
        // return view('Template::about_us',compact('pageTitle'));

    }

    public function user_rating()
    {
        $ratings = Rating::with(['user', 'company', 'category', 'feature'])->get();
        return view('Template::ratingCompany',compact('ratings'));
    }

    public function pages($slug)
    {
        $page = Page::where('tempname', activeTemplate())->where('slug', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page ? $page->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::contact', compact('pageTitle', 'user', 'sections', 'seoContents', 'seoImage'));
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required|string|max:255',
            'message' => 'required',
        ]);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $random = getNumber();

        $ticket = new SupportTicket();
        $ticket->user_id = auth()->id() ?? 0;
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->priority = Status::PRIORITY_MEDIUM;


        $ticket->ticket = $random;
        $ticket->subject = $request->subject;
        $ticket->last_reply = Carbon::now();
        $ticket->status = Status::TICKET_OPEN;
        $ticket->save();

        $adminNotification = new AdminNotification();
        $adminNotification->user_id = auth()->user() ? auth()->user()->id : 0;
        $adminNotification->title = 'A new contact message has been submitted';
        $adminNotification->click_url = urlPath('admin.ticket.view', $ticket->id);
        $adminNotification->save();

        $message = new SupportMessage();
        $message->support_ticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $notify[] = ['success', 'Ticket created successfully!'];

        return to_route('ticket.view', [$ticket->ticket])->withNotify($notify);
    }

    public function policyPages($slug)
    {
        $policy = Frontend::where('slug', $slug)->where('data_keys', 'policy_pages.element')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy ? $policy->seo_content : null;
        $seoImage = @$seoContents->image ? frontendImage('policy_pages', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::policy', compact('policy', 'pageTitle', 'seoContents', 'seoImage'));
    }

    public function changeLanguage($lang = null)
    {
        $language = Language::where('code', $lang)->first();
        if (!$language) $lang = 'en';
        session()->put('lang', $lang);
        return back();
    }

    public function blogs()
    {
        $pageTitle   = 'Blogs';
        
        // Debug: Log để kiểm tra
        \Log::info('Blogs method called');
        
        // Lấy tất cả blog elements
        $blogs = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate(21));
        
        // Debug: Log số lượng blogs
        \Log::info('Found ' . $blogs->count() . ' blogs');
        
        $latest = Frontend::latest()->where('data_keys', 'blog.element')->limit(10)->get();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        
        // Debug: Log data
        \Log::info('Blogs data:', [
            'count' => $blogs->count(),
            'latest_count' => $latest->count(),
            'sections' => $sections ? 'found' : 'not found',
            'active_template' => activeTemplate()
        ]);
        
        return view(activeTemplate() . 'blog', compact('pageTitle', 'blogs', 'latest', 'sections', 'seoContents', 'seoImage'));
    }

    public function blogDetails($slug, $id)
    {
        $pageTitle   = 'Blog Details';
        
        // Debug: Log parameters
        \Log::info('BlogDetails called with:', [
            'slug' => $slug,
            'id' => $id
        ]);
        
        // Try to find blog
        $blog = Frontend::where('slug', $slug)->where('id', $id)->where('data_keys', 'blog.element')->first();
        
        if (!$blog) {
            \Log::error('Blog not found:', [
                'slug' => $slug,
                'id' => $id,
                'data_keys' => 'blog.element'
            ]);
            
            // Check what exists
            $existingBlog = Frontend::where('id', $id)->first();
            if ($existingBlog) {
                \Log::info('Found blog with different data_keys:', [
                    'id' => $id,
                    'data_keys' => $existingBlog->data_keys,
                    'slug' => $existingBlog->slug
                ]);
            }
            
            // Return debug view instead of abort
            return view(activeTemplate() . 'blog_details', [
                'blog' => null,
                'pageTitle' => 'Blog Not Found',
                'seoContents' => null,
                'seoImage' => null,
                'latestBlogs' => collect(),
                'debug' => [
                    'slug' => $slug,
                    'id' => $id,
                    'existing_blog' => $existingBlog ? [
                        'id' => $existingBlog->id,
                        'slug' => $existingBlog->slug,
                        'data_keys' => $existingBlog->data_keys
                    ] : null
                ]
            ]);
        }
        
        \Log::info('Blog found:', [
            'id' => $blog->id,
            'slug' => $blog->slug,
            'title' => $blog->data_values->title ?? 'No title'
        ]);
        
        $latestBlogs = Frontend::latest()->where('data_keys', 'blog.element')->where('slug', '!=', $slug)->limit(10)->get();
        $seoContents = $blog ? $blog->seo_content : null;
        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        
        return view(activeTemplate() . 'blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'latestBlogs'));
    }

    public function cookieAccept()
    {
        Cookie::queue('gdpr_cookie', gs('site_name'), 43200);
    }

    public function cookiePolicy()
    {
        $cookieContent = Frontend::where('data_keys', 'cookie.data')->first();
        abort_if($cookieContent->data_values->status != Status::ENABLE, 404);
        $pageTitle = 'Cookie Policy';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->first();
        return view('Template::cookie', compact('pageTitle', 'cookie'));
    }

    public function placeholderImage($size = null)
    {
        // For debugging - let's see what's going on
        \Log::info('Placeholder image called with size: ' . $size);
        
        try {
            // Simple validation
            if (!$size) {
                return response('Invalid size parameter', 400);
            }

            // Check if contains 'x'
            if (!str_contains($size, 'x')) {
                return response('Size must contain x', 400);
            }

            $parts = explode('x', $size);
            if (count($parts) != 2) {
                return response('Invalid size format', 400);
        }

            $width = (int) $parts[0];
            $height = (int) $parts[1];
            
            if ($width <= 0 || $height <= 0) {
                return response('Invalid dimensions', 400);
        }

            // Create a simple image with Response instead of direct headers
            $image = imagecreatetruecolor($width, $height);
            $gray = imagecolorallocate($image, 240, 240, 240);
            $text_color = imagecolorallocate($image, 100, 100, 100);
            
            imagefill($image, 0, 0, $gray);
            
            $text = $width . 'x' . $height;
            $font_size = 3;
            
            $text_width = imagefontwidth($font_size) * strlen($text);
            $text_height = imagefontheight($font_size);
            
            $x = ($width - $text_width) / 2;
            $y = ($height - $text_height) / 2;
            
            imagestring($image, $font_size, $x, $y, $text, $text_color);
            
            // Capture image output
            ob_start();
        imagejpeg($image);
            $imageData = ob_get_contents();
            ob_end_clean();
            
        imagedestroy($image);
            
            return response($imageData, 200)
                ->header('Content-Type', 'image/jpeg')
                ->header('Cache-Control', 'public, max-age=86400');
                
        } catch (\Exception $e) {
            \Log::error('Placeholder image error: ' . $e->getMessage());
            return response('Error generating image: ' . $e->getMessage(), 500);
        }
    }

    public function categoryCompany($id)
    {
        $pageTitle      = keyToTitle(last(request()->segments())) . ' Companies';
        $categoryId = $id;
        $companies      = Company::approved()->where('category_id', $id)->withAvg('ratings', 'avg_rating')
            ->withCount('ratings')->with('category')->latest()->paginate(getPaginate());
        $categories     = Category::where('status', Status::ENABLE)->where('id', $id)->with('company')->whereHas('company')->get();
        return view('Template::company.index', compact('pageTitle', 'companies', 'categories', 'categoryId'));
    }

    public function searchFromBanner(Request $request)
    {
        $pageTitle = "Search Companies";
        $categories = Category::where('status', Status::ENABLE)->with('company')->whereHas('company', function ($q) {
            $q->approved();
        })->get();

        $companies = Company::approved()->with('category')->where('name', 'like', "%$request->search%")
            ->orWhereJsonContains('tags', $request->search)
            ->orWhereHas('category', function ($q) use ($request) {
                $q->where('name', $request->search);
            })->latest()->withAvg('ratings', 'avg_rating')->withCount('ratings')->paginate(getPaginate());

        return view('Template::company.index', compact('pageTitle', 'categories', 'companies'));
    }

    public function companies()
    {
        $companies = Company::approved()
                           ->with(['category', 'user', 'ratings'])
                           ->withAvg('ratings', 'avg_rating')
                           ->withCount('ratings')
                           ->latest()
                           ->paginate(12);
                           
        $categories = Category::where('status', Status::ENABLE)
                            ->with('company')
                            ->whereHas('company', function ($q) {
                                $q->approved();
                            })
                            ->withCount(['company' => function($q) {
                                $q->approved();
                            }])
                            ->get();

        // Add location data for filtering
        $locations = Company::approved()
                           ->select('city')
                           ->whereNotNull('city')
                           ->where('city', '!=', '')
                           ->groupBy('city')
                           ->pluck('city')
                           ->sort()
                           ->values();

        // Add experience levels
        $experienceLevels = [
            '0-1' => 'Dưới 1 năm',
            '1-3' => '1-3 năm',
            '3-5' => '3-5 năm', 
            '5-10' => '5-10 năm',
            '10+' => 'Trên 10 năm'
        ];

        // Add sorting options
        $sortOptions = [
            'latest' => 'Mới nhất',
            'rating' => 'Đánh giá cao nhất',
            'name' => 'Tên A-Z',
            'experience' => 'Kinh nghiệm nhiều nhất'
        ];

        $pageTitle = 'Tất cả thợ chuyên nghiệp';
        $seoContents = (object) [
            'title' => 'Tìm thợ chuyên nghiệp uy tín tại Việt Nam',
            'description' => 'Khám phá hàng ngàn thợ chuyên nghiệp được xác minh. Xem đánh giá, so sánh giá và đặt hẹn ngay.',
            'keywords' => 'thợ chuyên nghiệp, sửa chữa, bảo trì, dịch vụ, tại nhà'
        ];
        
        return view('Template::company.index', compact(
            'pageTitle', 'companies', 'categories', 'locations', 
            'experienceLevels', 'sortOptions', 'seoContents'
        ));
    }

    public function filterCompanies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'   => 'nullable|exists:categories,id',
            'rating'        => 'nullable|min:1|max:5',
            'location'      => 'nullable|string',
            'experience'    => 'nullable|string',
            'sort_by'       => 'nullable|string|in:latest,rating,name,experience',
            'search'        => 'nullable|string|max:255'
        ]);

        $query = Company::approved()
                       ->with(['category', 'user', 'ratings'])
                       ->withAvg('ratings', 'avg_rating')
                       ->withCount('ratings');

        // Search functionality
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%")
                  ->orWhereHas('category', function($cat) use ($search) {
                      $cat->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category filter
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Location filter
        if ($request->location) {
            $query->where('city', 'like', "%{$request->location}%");
        }

        // Rating filter
        if ($request->rating) {
            $minRating = (float)$request->rating;
            $query->having('ratings_avg_avg_rating', '>=', $minRating);
        }

        // Experience filter
        if ($request->experience) {
            $experienceRange = explode('-', $request->experience);
            if (count($experienceRange) == 2) {
                $minYears = (int)$experienceRange[0];
                $maxYears = $experienceRange[1] === '+' ? 100 : (int)$experienceRange[1];
                
                $startDate = now()->subYears($maxYears);
                $endDate = now()->subYears($minYears);
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Sorting
        switch ($request->sort_by) {
            case 'rating':
                $query->orderBy('ratings_avg_avg_rating', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'experience':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
        }

        $companies = $query->paginate(12);

        // For AJAX requests
        if ($request->ajax()) {
            return view('Template::company.companies', compact('companies'))->render();
        }

        // For regular requests
        $categories = Category::where('status', Status::ENABLE)
                            ->withCount(['company' => function($q) {
                                $q->approved();
                            }])
                            ->get();

        return view('Template::company.companies', compact('categories', 'companies'));
    }

    public function companyDetails(Request $request, $id, $slug)
    {
        $company = Company::where('id', $id)->approved()->with('portfolios')->firstOrFail();

        $ratings = Rating::where('company_id', $company->id)->with('user', 'company')->where('status', 1)->latest()->take(20)->get();

        $myReview = Rating::where('user_id', auth()->id() ?? 0)->where('company_id', $company->id)->with('ratingDetails.feature')->first();

        $reviews = Rating::where('company_id', $company->id)->with(['user', 'ratingDetails.feature'])->where('status', 1)->latest()->paginate(10);

        $pageTitle = $company->name;

        $avgRating = Rating::where('company_id', $company->id)->avg('avg_rating') ?? 0;

        $averageRatings = [];
        // Only get features that belong to the company's category
        $features = Feature::where('status', Status::ENABLE)
                          ->where('category_id', $company->category_id)
                          ->get();
        foreach ($features as $feature) {
            $averageRatings[$feature->id] = RatingDetail::where('feature_id', $feature->id)
                ->whereHas('rating', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->avg('rating') ?? 0;
        }

        return view('Template::company.details', compact('pageTitle', 'company', 'reviews', 'ratings', 'features', 'myReview', 'averageRatings', 'avgRating'));
    }
    
    /**
     * Contractor search functionality for UX flow
     */
    public function contractorSearch(Request $request)
    {
        $pageTitle = 'Tìm Thợ Chuyên Nghiệp';
        
        $query = Company::with(['user', 'category', 'ratings'])
                        ->approved();
        
        // Search filters
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('category', function($cat) use ($request) {
                      $cat->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->district) {
            $query->whereHas('user', function($u) use ($request) {
                $u->where('district', $request->district);
            });
        }
        
        if ($request->rating) {
            $query->whereHas('ratings', function($r) use ($request) {
                $r->havingRaw('AVG(avg_rating) >= ?', [$request->rating]);
            });
        }
        
        // Sorting
        switch ($request->sort) {
            case 'rating':
                $query->withAvg('ratings', 'avg_rating')->orderBy('ratings_avg_avg_rating', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'experience':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->withAvg('ratings', 'avg_rating')->orderBy('ratings_avg_avg_rating', 'desc');
        }
        
        $contractors = $query->paginate(12);
        $categories = Category::where('status', 1)->get();
        
        return view('Template::contractors.search', compact(
            'pageTitle', 'contractors', 'categories'
        ));
    }
    
    /**
     * Contractors by category
     */
    public function contractorsByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $pageTitle = 'Thợ ' . $category->name;
        
        $companies = Company::with(['user', 'ratings'])
                             ->where('category_id', $categoryId)
                             ->where('status', Status::APPROVED)
                             ->withAvg('ratings', 'avg_rating')
                             ->orderBy('ratings_avg_avg_rating', 'desc')
                             ->paginate(12);
                             
        $categories = Category::where('status', 1)->get();
        
        return view('Template::company.index', compact(
            'pageTitle', 'companies', 'category', 'categories', 'categoryId'
        ));
    }
    
    /**
     * Individual contractor profile with booking option
     */
    public function contractorProfile($id)
    {
        $company = Company::with(['user', 'category', 'ratings.user'])
                          ->where('status', Status::APPROVED)
                          ->findOrFail($id);
                          
        $pageTitle = $company->name;
        
        // Calculate ratings
        $avgRating = $company->ratings->avg('avg_rating') ?? 0;
        $totalReviews = $company->ratings->count();
        
        // Get feature ratings for this company's category only
        $features = Feature::where('status', Status::ENABLE)
                          ->where('category_id', $company->category_id)
                          ->get();
        $averageRatings = [];
        foreach ($features as $feature) {
            $averageRatings[$feature->id] = RatingDetail::where('feature_id', $feature->id)
                ->whereHas('rating', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->avg('rating') ?? 0;
        }
        
        // Recent reviews
        $recentReviews = $company->ratings()
                               ->with('user')
                               ->where('status', 1)
                               ->latest()
                               ->take(5)
                               ->get();
        
        // Check if user has reviewed this contractor
        $myReview = null;
        if (auth()->check()) {
            $myReview = Rating::where('user_id', auth()->id())
                             ->where('company_id', $company->id)
                             ->first();
        }
        
        return view('Template::contractors.profile', compact(
            'pageTitle', 'company', 'avgRating', 'totalReviews', 
            'features', 'averageRatings', 'recentReviews', 'myReview'
        ));
    }

    public function review(Request $request, $id)
    {
        // Log dữ liệu đầu vào để debug
        #Log::info('Review request data:', $request->all());
    
        // Validate dữ liệu đầu vào
        $request->validate([
            'rating' => 'required|array', // Yêu cầu phải có mảng rating
            'rating.*' => 'required|integer|min:1|max:5', // Mỗi rating phải là số hợp lệ
            'review' => 'required|string', // Nội dung review tổng quan
        ]);    
    
        // Xác định công ty
        $company = Company::where('status', Status::APPROVED)->findOrFail($id);

        // Tạo hoặc cập nhật bảng ratings
        $rating = Rating::updateOrCreate(
            attributes: [
                'company_id' => $id,
                'user_id' => auth()->id(),
            ],
            values: [
                'suggest' => $request->review,
                'status' => 1,
            ]
        );
    
        // Xóa các rating_detail cũ liên quan đến rating này
        RatingDetail::where('rating_id', $rating->id)->delete();

        // Lưu thông tin từng feature vào rating_details
        foreach ($request->rating as $featureId => $score) {
            RatingDetail::create([
                'rating_id' => $rating->id,
                'feature_id' => $featureId,
                'rating' => (float)$score,
            ]);
        }
    
        // Tính lại avg_rating cho bản ghi ratings
        $avgRating = RatingDetail::where('rating_id', $rating->id)->avg('rating');

        // Cập nhật avg_rating vào bảng ratings
        $rating->avg_rating = round($avgRating, 2);
        $rating->save();
        
        // Tính lại avg_rating của công ty từ bảng rating_details
        $averageRating = RatingDetail::join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
            ->where('ratings.company_id', $id)
            ->avg('rating_details.rating');

        // Cập nhật avg_rating vào bảng company (nếu cần)
        $company->avg_rating = $averageRating ? round($averageRating, 2) : 0;
        $company->save();

        $notify[] = ['success', 'Review submitted successfully'];
        return back()->withNotify($notify);
    }
    
    public function getReactionsByRating(Request $request, $rating_id)
    {
        $reactions = RatingReaction::where('rating_id', $rating_id)
                                  ->with('user', 'reactionType')
                                  ->get();

        return response()->json(['reactions' => $reactions]);
    }

    public function companyRating($id)
    {
        $company = Company::where('status', Status::APPROVED)->findOrFail($id);
        $pageTitle = 'Rating for ' . $company->name;

        return view('Template::rating.form', compact('pageTitle', 'company'));
    }

    public function addClick($id)
    {
        $company = Company::findOrFail($id);
        $company->increment('total_click');
        return response()->json(['success' => true]);
    }

    public function becomeContractor()
    {
        $pageTitle = 'Become a Contractor';
        $categories = Category::where('status', 1)->get();
        
        // Get statistics for the hero section
        $totalJobs = \App\Models\Appointment::count();
        $activeContractors = \App\Models\Company::where('status', 1)->count();
        $averageEarning = 15000000; // 15M VND average monthly earning
        
        // Check if user is authenticated and has company
        $isAuthenticated = Auth::check();
        $hasCompany = false;
        $existingCompany = null;
        
        if ($isAuthenticated) {
            $existingCompany = Company::where('user_id', Auth::id())->first();
            $hasCompany = $existingCompany ? true : false;
        }
        
        return view('Template::become_contractor', compact(
            'pageTitle', 
            'categories', 
            'totalJobs', 
            'activeContractors', 
            'averageEarning',
            'isAuthenticated',
            'hasCompany',
            'existingCompany'
        ));
    }

    public function becomeContractorRegister(Request $request)
    {
        $action = $request->input('action');
        
        switch ($action) {
            case 'login':
                return $this->handleLogin($request);
            case 'register':
                return $this->handleRegister($request);
            case 'create_contractor':
                return $this->handleCreateContractor($request);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid action'
                ]);
        }
    }
    
    private function handleLogin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'login_password' => 'required|string',
        ]);

        $credentials = [];
        $username = $request->username;
        
        // Check if username is email or mobile
        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $username;
        } else {
            $credentials['mobile'] = $username;
        }
        $credentials['password'] = $request->login_password;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if user already has a company
            $existingCompany = Company::where('user_id', $user->id)->first();
            
            if ($existingCompany) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công! Bạn đã có hồ sơ thợ.',
                    'redirect' => route('user.company.index')
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công! Hãy tạo hồ sơ thợ.',
                    'next_step' => 'create_contractor'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không chính xác'
            ]);
        }
    }
    
    private function handleRegister(Request $request)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:20|unique:users,mobile',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Create user account
            $user = User::create([
                'name' => $request->fullname,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
            ]);

            // Auto login the user
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công! Hãy tạo hồ sơ thợ.',
                'next_step' => 'create_contractor'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.'
            ]);
        }
    }
    
    private function handleCreateContractor(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập trước khi tạo hồ sơ thợ'
            ]);
        }
        
        $request->validate([
            'company_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
        ]);

        try {
            // Check if user already has a company
            $existingCompany = Company::where('user_id', Auth::id())->first();
            
            if ($existingCompany) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã có hồ sơ thợ rồi!'
                ]);
            }

            // Create company profile
            $company = Company::create([
                'user_id' => Auth::id(),
                'category_id' => $request->category_id,
                'name' => $request->company_name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->mobile ?? '',
                'description' => $request->description,
                'experience' => 0,
                'address' => '',
                'city' => '',
                'district' => '',
                'ward' => '',
                'status' => Status::PENDING, // Pending approval
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tạo hồ sơ thợ thành công! Chúng tôi sẽ xem xét và liên hệ với bạn sớm.',
                'redirect' => route('user.company.edit', $company->id)
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tạo hồ sơ. Vui lòng thử lại.'
            ]);
        }
    }

    private function getPopularCategories()
    {
        return Category::withCount('companies')
                      ->where('status', 1)
                      ->orderBy('companies_count', 'desc')
                      ->take(8)
                      ->get();
    }

    private function getFeaturedCompanies()
    {
        return Company::with(['user', 'category'])
                     ->where('status', Status::APPROVED)
                     ->where('featured', 1)
                     ->latest()
                     ->take(12)
                     ->get();
    }

    private function getTopRatedCompanies()
    {
        return Company::with(['user', 'category', 'ratings'])
                     ->where('status', Status::APPROVED)
                     ->withAvg('ratings', 'avg_rating')
                     ->orderBy('ratings_avg_avg_rating', 'desc')
                     ->take(8)
                     ->get();
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        return view('maintenance', compact('pageTitle'));
    }

    // ===========================
    // V2 COMPANY CREATE METHODS
    // ===========================
    
    public function createCompanyV2()
    {
        $pageTitle = 'Create Company Profile V2';
        $categories = Category::where('status', Status::ENABLE)->get();
        
        // Get existing companies for testing
        $sampleCompanies = Company::with(['category', 'ratings'])
                                 ->where('status', Status::APPROVED)
                                 ->take(3)
                                 ->get();
        
        return view(activeTemplate() . 'company.create_v2', compact('pageTitle', 'categories', 'sampleCompanies'));
    }
    
    public function storeCompanyV2(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'experience' => 'required|integer|min:0|max:50',
            'address' => 'required|string',
            'city' => 'required|string',
            'district' => 'nullable|string',
            'ward' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'specialty_services' => 'nullable|array|max:6',
            'specialty_services.*' => 'nullable|string|max:100',
            'weekday_start' => 'required|date_format:H:i',
            'weekday_end' => 'required|date_format:H:i',
            'weekend_start' => 'required|date_format:H:i',
            'weekend_end' => 'required|date_format:H:i',
            'available_247' => 'nullable|boolean',
            'project_images' => 'nullable|array|max:10',
            'project_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'featured_project_description' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();
            
            // Create company
            $company = new Company();
            $company->user_id = auth()->id() ?? 1; // Fallback for testing
            $company->category_id = $request->category_id;
            $company->name = $request->name;
            $company->email = $request->email;
            $company->phone = $request->phone;
            $company->description = $request->description;
            $company->experience = $request->experience;
            $company->address = $request->address;
            $company->city = $request->city;
            $company->district = $request->district;
            $company->ward = $request->ward;
            $company->status = Status::PENDING;
            
            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('assets/images/company'), $imageName);
                $company->image = $imageName;
            }
            
            $company->save();
            
            // Handle services
            if ($request->services) {
                // You can create a pivot table or store as JSON
                // For now, let's store as JSON in a services column
                // $company->services = json_encode($request->services);
                // $company->save();
            }
            
            // Handle certificates
            if ($request->hasFile('certificates')) {
                foreach ($request->file('certificates') as $certificate) {
                    $certName = time() . '_' . uniqid() . '.' . $certificate->getClientOriginalExtension();
                    $certificate->move(public_path('assets/images/certificates'), $certName);
                    
                    // Store in certificates table if exists
                    // Certificate::create([
                    //     'company_id' => $company->id,
                    //     'file_name' => $certName,
                    //     'original_name' => $certificate->getClientOriginalName(),
                    // ]);
                }
            }
            
            // Handle portfolio images
            if ($request->hasFile('portfolio_images')) {
                foreach ($request->file('portfolio_images') as $portfolio) {
                    $portfolioName = time() . '_' . uniqid() . '.' . $portfolio->getClientOriginalExtension();
                    $portfolio->move(public_path('assets/images/portfolios'), $portfolioName);
                    
                    // Store in portfolios table if exists
                    // Portfolio::create([
                    //     'company_id' => $company->id,
                    //     'image' => $portfolioName,
                    //     'description' => $request->portfolio_descriptions[$key] ?? '',
                    // ]);
                }
            }
            
            DB::commit();
            
            $notify[] = ['success', 'Company profile created successfully! It will be reviewed by our team.'];
            return redirect()->route('company.preview.v2', ['id' => $company->id])->withNotify($notify);
            
        } catch (\Exception $e) {
            DB::rollback();
            
            $notify[] = ['error', 'An error occurred while creating the company profile. Please try again.'];
            return back()->withInput()->withNotify($notify);
        }
    }
    
    public function previewCompanyV2(Request $request)
    {
        $companyId = $request->get('id');
        
        if ($companyId) {
            $company = Company::with(['category', 'ratings'])->findOrFail($companyId);
        } else {
            // Create a sample company for preview
            $company = (object) [
                'id' => 0,
                'name' => 'Sample Company Name',
                'description' => 'This is a sample description for the company profile preview.',
                'experience' => 5,
                'address' => '123 Sample Street, Sample District',
                'city' => 'Ho Chi Minh City',
                'phone' => '0123456789',
                'email' => 'sample@company.com',
                'image' => 'default-company.jpg',
                'avg_rating' => 4.5,
                'category' => (object) ['name' => 'Sample Category'],
                'ratings' => collect([]),
            ];
        }
        
        $pageTitle = 'Preview Company Profile V2';
        
        return view(activeTemplate() . 'company.preview_v2', compact('pageTitle', 'company'));
    }
}
