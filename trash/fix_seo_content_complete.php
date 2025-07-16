<?php
/*
 * PATCH FILE: Fix seo_content null access issues
 * 
 * This file contains the complete fixes for SiteController.php
 * Copy the content below and replace the corresponding methods in your production SiteController.php
 */

echo "=== SITECONTROLLER.PHP PATCHES ===\n\n";

echo "1. Replace the index() method with:\n";
echo "=====================================\n";
echo '
    public function index()
    {
        $pageTitle = \'Home\';
        $sections = Page::where(\'tempname\', activeTemplate())->where(\'slug\', \'/\')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath(\'seo\') . \'/\' . @$seoContents->image, getFileSize(\'seo\')) : null;
        return view(\'Template::home\', compact(\'pageTitle\', \'sections\', \'seoContents\', \'seoImage\'));
    }
';

echo "\n2. Replace the about() method with:\n";
echo "===================================\n";
echo '
    public function about()
    {
        $pageTitle = "About Us";
        $sections = Page::where(\'tempname\', activeTemplate())->where(\'slug\', \'about-us\')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath(\'seo\') . \'/\' . @$seoContents->image, getFileSize(\'seo\')) : null;
        
        return view(\'Template::about_us\', compact(\'pageTitle\', \'sections\', \'seoContents\', \'seoImage\'));
    }
';

echo "\n3. Replace the pages() method with:\n";
echo "===================================\n";
echo '
    public function pages($slug)
    {
        $page = Page::where(\'tempname\', activeTemplate())->where(\'slug\', $slug)->firstOrFail();
        $pageTitle = $page->name;
        $sections = $page->secs;
        $seoContents = $page ? $page->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath(\'seo\') . \'/\' . @$seoContents->image, getFileSize(\'seo\')) : null;
        return view(\'Template::pages\', compact(\'pageTitle\', \'sections\', \'seoContents\', \'seoImage\'));
    }
';

echo "\n4. Replace the contact() method with:\n";
echo "=====================================\n";
echo '
    public function contact()
    {
        $pageTitle = "Contact Us";
        $user = auth()->user();
        $sections = Page::where(\'tempname\', activeTemplate())->where(\'slug\', \'contact\')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage = @$seoContents->image ? getImage(getFilePath(\'seo\') . \'/\' . @$seoContents->image, getFileSize(\'seo\')) : null;
        return view(\'Template::contact\', compact(\'pageTitle\', \'user\', \'sections\', \'seoContents\', \'seoImage\'));
    }
';

echo "\n5. Replace the policyPages() method with:\n";
echo "=========================================\n";
echo '
    public function policyPages($slug)
    {
        $policy = Frontend::where(\'slug\', $slug)->where(\'data_keys\', \'policy_pages.element\')->firstOrFail();
        $pageTitle = $policy->data_values->title;
        $seoContents = $policy ? $policy->seo_content : null;
        $seoImage = (@$seoContents && @$seoContents->image) ? frontendImage(\'policy_pages\', $seoContents->image, getFileSize(\'seo\'), true) : null;
        return view(\'Template::policy\', compact(\'policy\', \'pageTitle\', \'seoContents\', \'seoImage\'));
    }
';

echo "\n6. Replace the blogs() method with:\n";
echo "===================================\n";
echo '
    public function blogs()
    {
        $pageTitle   = \'Blogs\';
        $blogs       = Frontend::where(\'data_keys\', \'blog.element\')->latest()->paginate(getPaginate(21));
        $latest      = Frontend::latest()->where(\'data_keys\', \'blog.element\')->limit(10)->get();
        $sections    = Page::where(\'tempname\', activeTemplate())->where(\'slug\', \'blog\')->first();
        $seoContents = $sections ? $sections->seo_content : null;
        $seoImage    = (@$seoContents && @$seoContents->image) ? frontendImage(\'blog\', $seoContents->image, getFileSize(\'seo\'), true) : null;
        return view(\'Template::blog\', compact(\'pageTitle\', \'blogs\', \'latest\', \'sections\', \'seoContents\', \'seoImage\'));
    }
';

echo "\n7. Replace the blogDetails() method with:\n";
echo "=========================================\n";
echo '
    public function blogDetails($slug, $id)
    {
        $pageTitle   = \'Blog Details\';
        $blog        = Frontend::where(\'slug\', $slug)->where(\'id\', $id)->where(\'data_keys\', \'blog.element\')->firstOrFail();
        $latestBlogs = Frontend::latest()->where(\'data_keys\', \'blog.element\')->where(\'slug\', \'!=\', $slug)->limit(10)->get();
        $seoContents = $blog ? $blog->seo_content : null;
        $seoImage    = (@$seoContents && @$seoContents->image) ? frontendImage(\'blog\', $seoContents->image, getFileSize(\'seo\'), true) : null;
        return view(\'Template::blog_details\', compact(\'blog\', \'pageTitle\', \'seoContents\', \'seoImage\', \'latestBlogs\'));
    }
';

echo "\n=== END OF PATCHES ===\n";
echo "\nInstructions:\n";
echo "1. Run the SQL files first: create_general_settings_complete.sql and pages_frontends_clean.sql\n";
echo "2. Apply these method patches to your SiteController.php on production\n";
echo "3. Test the website to ensure no more seo_content errors\n";

?> 