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
use App\Models\ReactionType;
use Illuminate\Support\Facades\DB; // Thêm dòng này


class SiteController extends Controller
{
    public function index()
    {
        $pageTitle = 'Home';
        $sections = Page::where('tempname', activeTemplate())->where('slug', '/')->first();
        $seoContents = $sections->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::home', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function about()
    {
        $pageTitle = "About Us";
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'about-us')->first();
        $seoContents = $sections->seo_content;
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
        $seoContents = $page->seo_content;
        $seoImage = @$seoContents->image ? getImage(getFilePath('seo') . '/' . @$seoContents->image, getFileSize('seo')) : null;
        return view('Template::pages', compact('pageTitle', 'sections', 'seoContents', 'seoImage'));
    }

    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where('tempname', activeTemplate())->where('slug', 'contact')->first();
        $seoContents = $sections->seo_content;
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
        $seoContents = $policy->seo_content;
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
        $blogs       = Frontend::where('data_keys', 'blog.element')->latest()->paginate(getPaginate(21));
        $latest      = Frontend::latest()->where('data_keys', 'blog.element')->limit(10)->get();
        $sections    = Page::where('tempname', activeTemplate())->where('slug', 'blog')->first();
        $seoContents = $sections->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog', compact('pageTitle', 'blogs', 'latest', 'sections', 'seoContents', 'seoImage'));
    }

    public function blogDetails($slug)
    {
        $pageTitle   = 'Blog Details';
        $blog        = Frontend::where('slug', $slug)->where('data_keys', 'blog.element')->firstOrFail();
        $latestBlogs = Frontend::latest()->where('data_keys', 'blog.element')->where('slug', '!=', $slug)->limit(10)->get();
        $seoContents = $blog->seo_content;
        $seoImage    = @$seoContents->image ? frontendImage('blog', $seoContents->image, getFileSize('seo'), true) : null;
        return view('Template::blog_details', compact('blog', 'pageTitle', 'seoContents', 'seoImage', 'latestBlogs'));
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
        $imgWidth  = explode('x', $size)[0];
        $imgHeight = explode('x', $size)[1];
        $text      = $imgWidth . '×' . $imgHeight;
        $fontFile  = realpath('assets/font/solaimanLipi_bold.ttf');
        $fontSize  = round(($imgWidth - 50) / 8);
        if ($fontSize <= 9) {
            $fontSize = 9;
        }
        if ($imgHeight < 100 && $fontSize > 30) {
            $fontSize = 30;
        }

        $image     = imagecreatetruecolor($imgWidth, $imgHeight);
        $colorFill = imagecolorallocate($image, 100, 100, 100);
        $bgFill    = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $bgFill);
        $textBox    = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth  = abs($textBox[4] - $textBox[0]);
        $textHeight = abs($textBox[5] - $textBox[1]);
        $textX      = ($imgWidth - $textWidth) / 2;
        $textY      = ($imgHeight + $textHeight) / 2;
        header('Content-Type: image/jpeg');
        imagettftext($image, $fontSize, 0, $textX, $textY, $colorFill, $fontFile, $text);
        imagejpeg($image);
        imagedestroy($image);
    }

    public function categoryCompany($id)
    {
        $pageTitle      = keyToTitle(last(request()->segments())) . ' Companies';
        $categoryId = $id;
        $companies      = Company::approved()->where('category_id', $id)->withAvg('reviews', 'rating')
            ->withCount('reviews')->with('category')->latest()->paginate(getPaginate());
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
            })->latest()->withAvg('reviews', 'rating')->withCount('reviews')->paginate(getPaginate());

        return view('Template::company.index', compact('pageTitle', 'categories', 'companies'));
    }

    public function companies()
    {
        $companies      = Company::approved()->withAvg('reviews', 'rating')->withCount('reviews')->with('category')->latest()->paginate(getPaginate());
        // dd($companies);
        $categories     = Category::where('status', Status::ENABLE)->with('company')->whereHas('company', function ($q) {
            $q->approved();
        })->get();
        $pageTitle      = 'All Companies';
        return view('Template::company.index', compact('pageTitle', 'companies', 'categories'));
    }

    public function filterCompanies(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'   => 'nullable|exists:categories,id',
            'rating'        => 'nullable|min:1|max:5',
            'review_time'   => 'nullable|integer',
            'reg_start'     => 'nullable|integer',
            'reg_end'       => 'nullable|integer'
        ]);

        $query = Company::approved()->with('category')->withAvg('reviews', 'rating')->withCount('reviews');

        if ($request->search_key) {
            $query = $query->where('name', 'like', "%$request->search_key%")->orWhere('tags', 'like', "%$request->search_key%")->orWhereHas('category', function ($q) use ($request) {
                $q->where('name', $request->search_key);
            });
        }

        if ($request->category_id) {
            $query = $query->where('category_id', $request->category_id);
        }

        if ($request->rating) {
            $query = $query->whereBetween('avg_rating', [$request->rating - 1 + .1, $request->rating]);
        }

        if ($request->review_time) {
            $startMonth = now()->subMonths($request->review);
            $endMonth =  now();

            $query = $query->whereHas('reviews', function ($q) use ($startMonth, $endMonth) {
                $q->whereBetween('created_at', [$startMonth, $endMonth]);
            });
        }

        if ($request->reg_start && $request->reg_end) {
            $start = now()->subYear($request->reg_end);
            $end   = now()->subYear($request->reg_start);
            $query = $query->whereBetween('created_at', [$start, $end]);
        } elseif ($request->reg_end) {
            $start = now()->subYear($request->reg_end);
            $end   = now();
            $query = $query->whereBetween('created_at', [$start, $end]);
        } elseif ($request->reg_start) {
            $year = now()->subYear($request->reg_start);
            $query = $query->whereDate('created_at', '<', $year);
        } else {
            $query = $query;
        }

        $companies  = $query->latest()->with('category')->paginate(getPaginate());

        $categories   = Category::where('status', Status::ENABLE)->with('company')->whereHas('company', function ($q) {
            $q->approved();
        })->get();

        return view('Template::company.companies', compact('categories', 'companies'));
    }

    public function companyDetails(Request $request, $id, $slug)
    {
        $company = Company::where('id', $id)->active()->verified()->firstOrFail();

        $ratings = Rating::where('company_id', $company->id)->with('user', 'company')->where('status', 1)->latest()->take(20)->get();

        $my_review = Rating::where('user_id', auth()->id() ?? 0)->where('company_id', $company->id)->first();

        $reviews = Review::where('company_id', $company->id)->with('user')->latest()->get();

        $reactionTypes = ReactionType::all();

        $pageTitle = $company->name;

        $avgRating = Rating::where('company_id', $company->id)->avg('rating') ?? 0;

        $averageRatings = [];
        $features = Feature::where('status', Status::ENABLE)->get();
        foreach ($features as $feature) {
            $averageRatings[$feature->id] = RatingDetail::where('feature_id', $feature->id)
                ->whereHas('rating', function ($query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->avg('rating') ?? 0;
        }

        return view('Template::company.details', compact('pageTitle', 'company', 'reviews', 'ratings', 'features', 'my_review', 'averageRatings', 'reactionTypes'));
    }
    
    /**
     * Contractor search functionality for UX flow
     */
    public function contractorSearch(Request $request)
    {
        $pageTitle = 'Tìm Thợ Chuyên Nghiệp';
        
        $query = Company::with(['user', 'category', 'ratings'])
                        ->active()
                        ->verified();
        
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
        
        $contractors = Company::with(['user', 'ratings'])
                             ->where('category_id', $categoryId)
                             ->active()
                             ->verified()
                             ->withAvg('ratings', 'avg_rating')
                             ->orderBy('ratings_avg_avg_rating', 'desc')
                             ->paginate(12);
                             
        $categories = Category::where('status', 1)->get();
        
        return view('Template::contractors.category', compact(
            'pageTitle', 'contractors', 'category', 'categories'
        ));
    }
    
    /**
     * Individual contractor profile with booking option
     */
    public function contractorProfile($id)
    {
        $company = Company::with(['user', 'category', 'ratings.user'])
                          ->active()
                          ->verified()
                          ->findOrFail($id);
                          
        $pageTitle = $company->name;
        
        // Calculate ratings
        $avgRating = $company->ratings->avg('avg_rating') ?? 0;
        $totalReviews = $company->ratings->count();
        
        // Get feature ratings
        $features = Feature::where('status', Status::ENABLE)->get();
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
        $company = Company::approved()->findOrFail($id);

        // Tạo hoặc cập nhật bảng ratings
        $rating = Rating::updateOrCreate(
            attributes: [
                'company_id' => $id,
                'user_id' => auth()->id(),
            ],
            values: [
                'suggest' => $request->review,
                'status' => 1, // Hoặc giá trị trạng thái mong muốn
            ]
        );
    
         // Xóa các rating_detail cũ liên quan đến rating này
        RatingDetail::where('rating_id', $rating->id)->delete();

        // Lưu thông tin từng feature vào rating_details
        foreach ($request->rating as $featureId => $score) {
            try {
            RatingDetail::create([
                'rating_id' => $rating->id,
                'feature_id' => $featureId,
                'rating' => (int)$score,
            ]);
            } catch (\Exception $e) {
            dd([
                'rating_id' => $rating->id,
                'feature_id' => $featureId,
                'rating' => $score,
                'Error Message' => $e->getMessage(),
            ]);
            }
        }
    
        // Tính lại avg_rating cho bản ghi ratings
        $avgRating = RatingDetail::where('rating_id', $rating->id)->avg('rating');

        // Cập nhật avg_rating vào bảng ratings
        $rating->avg_rating = round($avgRating, 2);
        $rating->save();
        
        // Tính lại avg_rating của công ty từ bảng rating_details

        $averageRating = RatingDetail::join('ratings', 'rating_details.rating_id', '=', 'ratings.id')
        ->where('ratings.company_id', $company->id)
        ->avg('rating');

        // Cập nhật avg_rating vào bảng công ty
        $company->avg_rating = round($averageRating, 2);
        $company->save();
    
        // Trả về thông báo thành công
        $notify[] = ['success', 'Thanks for your detailed review'];
        return back()->withNotify($notify);
    }   
    
    
    public function getReactionsByRating(Request $request, $rating_id)
{
    // 1. Tìm rating theo id
    $rating = Rating::find($rating_id);

    // 2. Nếu không tồn tại, trả về lỗi
    if (!$rating) {
        return response()->json(['error' => 'Rating not found'], 404);
    }

    // 3. Tính số lượng các loại reaction liên quan đến rating này
    $reactionCounts = RatingReaction::where('rating_id', $rating_id)
        ->select('reaction_type_id', DB::raw('COUNT(*) as count')) // Đếm số lượng
        ->groupBy('reaction_type_id') // Nhóm theo reaction_type_id
        ->get()
        ->keyBy('reaction_type_id'); // Tổ chức dữ liệu để dễ truy cập

    $userReaction = RatingReaction::where('rating_id', $rating_id)
        ->where('user_id', auth()->id()) // Lọc theo người dùng hiện tại
        ->first();

    // 4. Trả về dữ liệu
    return response()->json([
        'success' => true,
        'rating_id' => $rating_id,
        'reaction_counts' => $reactionCounts, // Số lượng phản ứng theo loại
        'user_active_reaction' => $userReaction ? $userReaction->reaction_type_id : null, // Loại phản ứng của người dùng hiện tại

    ]);
}



    public function companyRating($id)
    {
        header("Access-Control-Allow-Origin: *");
        $id   = Crypt::decrypt($id);
        $info = Company::where('id', $id)->where('status', 1)->withAvg('reviews', 'rating')->withCount('reviews')->first();
        return response()->json([
            'rating'  => $info->avg_rating,
            'outOf'   => ' (' . $info->reviews_count . ' Ratings)',
            'success' => true,
        ]);
    }

    public function addClick($id)
    {
        $advertisement = Advertisement::find($id);

        if ($advertisement) {
            $advertisement->click += 1;
            $advertisement->save();
        }
        return response()->json([
            'success' => true,
            'data' => $advertisement
        ]);
    }

    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        if (gs('maintenance_mode') == Status::DISABLE) {
            return to_route('home');
        }
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->first();
        return view('Template::maintenance', compact('pageTitle', 'maintenance'));
    }
}
