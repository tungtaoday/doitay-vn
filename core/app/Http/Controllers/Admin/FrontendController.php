<?php

namespace App\Http\Controllers\Admin;

use App\Models\Frontend;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class FrontendController extends Controller
{

    public function index()
    {
        $pageTitle = 'Manage Frontend Content';
        return view('admin.frontend.index', compact('pageTitle'));
    }
    public function templates()
    {
        abort(404);
        $pageTitle = 'Templates';
        $temPaths = array_filter(glob('core/resources/views/templates/*'), 'is_dir');
        foreach ($temPaths as $key => $temp) {
            $arr = explode('/', $temp);
            $tempname = end($arr);
            $templates[$key]['name'] = $tempname;
            $templates[$key]['image'] = asset($temp) . '/preview.jpg';
        }
        $extraTemplates = json_decode(getTemplates(), true);
        return view('admin.frontend.templates', compact('pageTitle', 'templates', 'extraTemplates'));
    }

    public function templatesActive(Request $request)
    {
        $general = gs();

        $general->active_template = $request->name;
        $general->save();

        $notify[] = ['success', strtoupper($request->name) . ' template activated successfully'];
        return back()->withNotify($notify);
    }

    public function seoEdit()
    {
        $pageTitle = 'SEO Configuration';
        $seo = Frontend::where('data_keys', 'seo.data')->first();
        if (!$seo) {
            $data_values = '{"keywords":[],"description":"","social_title":"","social_description":"","image":null}';
            $data_values = json_decode($data_values, true);
            $frontend = new Frontend();
            $frontend->data_keys = 'seo.data';
            $frontend->data_values = $data_values;
            $frontend->save();
        }
        return view('admin.frontend.seo', compact('pageTitle', 'seo'));
    }

    public function frontendSections($key)
    {
        $section = @getPageSections()->$key;
        abort_if(!$section || !$section->builder, 404);
        $content = Frontend::where('data_keys', $key . '.content')->where('tempname',activeTemplateName())->orderBy('id', 'desc')->first();
        $elements = Frontend::where('data_keys', $key . '.element')->where('tempname',activeTemplateName())->orderBy('id', 'desc')->get();
        $pageTitle = $section->name;
        return view('admin.frontend.section', compact('section', 'content', 'elements', 'key', 'pageTitle'));
    }

    public function frontendContent(Request $request, $key)
    {
        try {
            \Log::info('Processing frontend content', [
                'key' => $key,
                'type' => $request->type,
                'has_image' => $request->has('has_image'),
                'files' => $request->allFiles(),
                'all_data' => $request->all()
            ]);

            $purifier = new \HTMLPurifier();
            $valInputs = $request->except('_token', 'image_input', 'key', 'status', 'type', 'id', 'slug');
            
            // Initialize inputContentValue array
            $inputContentValue = [];
            
            foreach ($valInputs as $keyName => $input) {
                if ($keyName == 'has_image') {
                    continue;
                }
                if (gettype($input) == 'array') {
                    $inputContentValue[$keyName] = $input;
                    continue;
                }
                $inputContentValue[$keyName] = htmlspecialchars_decode($purifier->purify($input));
            }

            $type = $request->type;
            if (!$type) {
                abort(404);
            }

            $imgJson = @getPageSections()->$key->$type->images;
            $validationRule = [];
            $validationMessage = [];

            foreach ($request->except('_token', 'video') as $inputField => $val) {
                if ($inputField == 'has_image' && $imgJson) {
                    foreach ($imgJson as $imgValKey => $imgJsonVal) {
                        $validationRule['image_input.' . $imgValKey] = ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])];
                        $validationMessage['image_input.' . $imgValKey . '.image'] = keyToTitle($imgValKey) . ' must be an image';
                        $validationMessage['image_input.' . $imgValKey . '.mimes'] = keyToTitle($imgValKey) . ' file type not supported';
                    }
                    continue;
                } elseif ($inputField == 'seo_image') {
                    $validationRule['image_input'] = ['nullable', 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])];
                    continue;
                }
                if ($inputField == 'slug') {
                    $validationRule[$inputField] = ['required', 'string', 'max:255', Rule::unique('frontends')->where(function ($query) use ($request) {
                        return $query->where('id', '!=', $request->id)
                            ->where('tempname', activeTemplateName());
                    })];
                }
            }

            $request->validate($validationRule, $validationMessage, ['image_input' => 'image']);

            if ($request->id) {
                $content = Frontend::findOrFail($request->id);
            } else {
                // Tìm record hiện tại
                $content = Frontend::where('data_keys', $key . '.' . $request->type);
                if ($type != 'data') {
                    $content = $content->where('tempname', activeTemplateName());
                }
                $content = $content->first();

                // Chỉ tạo mới nếu thực sự cần thiết
                if (!$content) {
                    $content = new Frontend();
                    $content->data_keys = $key . '.' . $request->type;
                    // Không save ngay, để tránh tạo record với ID = 0
                }
            }

            if ($type == 'data') {
                $inputContentValue['image'] = @$content->data_values->image;
                if ($request->hasFile('image_input')) {
                    try {
                        $inputContentValue['image'] = fileUploader($request->image_input, getFilePath('seo'), getFileSize('seo'), @$content->data_values->image);
                    } catch (\Exception $exp) {
                        \Log::error('Image upload failed', [
                            'error' => $exp->getMessage(),
                            'trace' => $exp->getTraceAsString()
                        ]);
                        $notify[] = ['error', 'Couldn\'t upload the image'];
                        return back()->withNotify($notify);
                    }
                }
            } else {
                if ($imgJson) {
                    foreach ($imgJson as $imgKey => $imgValue) {
                        $imgData = @$request->image_input[$imgKey];
                        if (is_file($imgData)) {
                            try {
                                $inputContentValue[$imgKey] = $this->storeImage($imgJson, $type, $key, $imgData, $imgKey, @$content->data_values->$imgKey);
                            } catch (\Exception $exp) {
                                \Log::error('Image upload failed', [
                                    'error' => $exp->getMessage(),
                                    'trace' => $exp->getTraceAsString()
                                ]);
                                $notify[] = ['error', 'Couldn\'t upload the image'];
                                return back()->withNotify($notify);
                            }
                        } else if (isset($content->data_values->$imgKey)) {
                            $inputContentValue[$imgKey] = $content->data_values->$imgKey;
                        }
                    }
                }
            }

            $content->data_values = $inputContentValue;
            $content->slug = slug($request->slug);
            if ($type != 'data') {
                $content->tempname = activeTemplateName();
            }
            
            try {
                $content->save();
                \Log::info('Content saved successfully', [
                    'id' => $content->id,
                    'data_keys' => $content->data_keys
                ]);
            } catch (\Exception $e) {
                \Log::error('Failed to save content', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                $notify[] = ['error', 'Failed to save content: ' . $e->getMessage()];
                return back()->withNotify($notify);
            }

            if (!$request->id && @getPageSections()->$key->element->seo && $type != 'content') {
                $notify[] = ['info', 'Configure SEO content for ranking'];
                $notify[] = ['success', 'Content updated successfully'];
                return to_route('admin.frontend.sections.element.seo', [$key, $content->id])->withNotify($notify);
            }

            $notify[] = ['success', 'Content updated successfully'];
            return back()->withNotify($notify);

        } catch (\Exception $e) {
            \Log::error('Unexpected error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $notify[] = ['error', 'An unexpected error occurred: ' . $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    public function frontendElement($key, $id = null)
    {
        $section = @getPageSections()->$key;
        if (!$section) {
            return abort(404);
        }

        unset($section->element->modal);
        unset($section->element->seo);
        $pageTitle = $section->name . ' Items';
        if ($id) {
            $data = Frontend::where('tempname',activeTemplateName())->findOrFail($id);
            return view('admin.frontend.element', compact('section', 'key', 'pageTitle', 'data'));
        }
        return view('admin.frontend.element', compact('section', 'key', 'pageTitle'));
    }

    public function frontendElementSlugCheck($key, $id = null)
    {
        try {
            // Clean HTML from slug
            $slug = strip_tags(request()->slug);
            $slug = preg_replace('/<!--[\s\S]*?-->/', '', $slug); // Remove HTML comments
            $slug = trim($slug); // Remove extra spaces
            
            \Log::info('Checking slug', [
                'original_slug' => request()->slug,
                'cleaned_slug' => $slug,
                'key' => $key,
                'id' => $id
            ]);

            if (empty($slug)) {
                $response = [
                    'exists' => false,
                    'message' => 'Slug is required'
                ];
            } else {
                $content = Frontend::where('data_keys', $key . '.element')
                    ->where('tempname', activeTemplateName())
                    ->where('slug', $slug);

                if ($id) {
                    $content = $content->where('id', '!=', $id);
                }

                $exist = $content->exists();
                \Log::info('Slug check result', [
                    'exists' => $exist,
                    'slug' => $slug
                ]);

                $response = [
                    'exists' => $exist,
                    'message' => $exist ? 'This slug is already taken' : 'Slug is available'
                ];
            }

            return response()->json($response, 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ->header('Content-Type', 'application/json')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0')
                ->header('X-Frame-Options', 'DENY')
                ->header('X-XSS-Protection', '1; mode=block')
                ->header('X-Content-Type-Options', 'nosniff');

        } catch (\Exception $e) {
            \Log::error('Error checking slug', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => true,
                'message' => 'Error checking slug: ' . $e->getMessage()
            ], 500, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                ->header('Content-Type', 'application/json')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0')
                ->header('X-Frame-Options', 'DENY')
                ->header('X-XSS-Protection', '1; mode=block')
                ->header('X-Content-Type-Options', 'nosniff');
        }
    }

    public function frontendSeo($key, $id)
    {
        $hasSeo = @getPageSections()->$key->element->seo;
        if (!$hasSeo) {
            abort(404);
        }
        $data = Frontend::findOrFail($id);
        $pageTitle = 'SEO Configuration';
        return view('admin.frontend.frontend_seo', compact('pageTitle', 'key', 'data'));
    }

    public function frontendSeoUpdate(Request $request, $key, $id)
    {
        $request->validate([
            'image' => ['nullable', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);
        $hasSeo = @getPageSections()->$key->element->seo;
        if (!$hasSeo) {
            abort(404);
        }
        $data = Frontend::findOrFail($id);
        $image = @$data->seo_content->image;
        if ($request->hasFile('image')) {
            try {
                $path = 'assets/images/frontend/' . $key . '/seo';
                $image = fileUploader($request->image, $path, getFileSize('seo'), @$data->seo_content->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the image'];
                return back()->withNotify($notify);
            }
        }
        $data->seo_content = [
            'image' => $image,
            'description' => $request->description,
            'social_title' => $request->social_title,
            'social_description' => $request->social_description,
            'keywords' => $request->keywords,
        ];
        $data->save();

        $notify[] = ['success', 'SEO content updated successfully'];
        return back()->withNotify($notify);
    }


    protected function storeImage($imgJson, $type, $key, $image, $imgKey, $oldImage = null)
    {
        // Define paths for blog uploads
        $basePath = 'assets/images/frontend';
        $relativePath = $basePath . '/' . $key; // This will be 'assets/images/frontend/blog'
        
        // Use XAMPP's htdocs path instead of Laravel's public path
        $absolutePath = str_replace('\\', '/', 'C:/xampp/htdocs/' . $relativePath);
        
        \Log::info('Uploading image', [
            'base_path' => $basePath,
            'relative_path' => $relativePath,
            'absolute_path' => $absolutePath,
            'type' => $type,
            'key' => $key,
            'imgKey' => $imgKey,
            'oldImage' => $oldImage
        ]);

        if ($type == 'element' || $type == 'content') {
            $size = @$imgJson->$imgKey->size;
            $thumb = @$imgJson->$imgKey->thumb;
            \Log::info('Image size and thumb', [
                'size' => $size,
                'thumb' => $thumb
            ]);
        } else {
            $relativePath = getFilePath($key);
            $absolutePath = str_replace('\\', '/', 'C:/xampp/htdocs/' . $relativePath);
            $size = getFileSize($key);
            $thumb = @fileManager()->$key()->thumb;
        }

        try {
            // Create base directory if it doesn't exist
            $baseAbsolutePath = str_replace('\\', '/', 'C:/xampp/htdocs/' . $basePath);
            if (!file_exists($baseAbsolutePath)) {
                if (!mkdir($baseAbsolutePath, 0777, true)) {
                    throw new \Exception("Failed to create base directory: $baseAbsolutePath");
                }
                chmod($baseAbsolutePath, 0777);
            }

            // Create blog directory if it doesn't exist
            if (!file_exists($absolutePath)) {
                if (!mkdir($absolutePath, 0777, true)) {
                    throw new \Exception("Failed to create blog directory: $absolutePath");
                }
                chmod($absolutePath, 0777);
            }

            // Ensure directory is writable
            if (!is_writable($absolutePath)) {
                chmod($absolutePath, 0777);
                if (!is_writable($absolutePath)) {
                    throw new \Exception("Directory is not writable: $absolutePath");
                }
            }

            // Test write permission
            $testFile = $absolutePath . '/test_write.tmp';
            if (file_put_contents($testFile, 'test') === false) {
                throw new \Exception("Cannot write to directory: $absolutePath");
            }
            unlink($testFile);

            // Log directory permissions
            \Log::info('Directory permissions', [
                'path' => $absolutePath,
                'exists' => file_exists($absolutePath),
                'writable' => is_writable($absolutePath),
                'permissions' => substr(sprintf('%o', fileperms($absolutePath)), -4)
            ]);

            // Ensure we're using the correct path for blog uploads
            if ($key === 'blog') {
                $relativePath = 'assets/images/frontend/blog';
            }

            $result = fileUploader($image, $relativePath, $size, $oldImage, $thumb);
            \Log::info('Upload successful', [
                'result' => $result,
                'path' => $relativePath,
                'full_url' => asset($relativePath . '/' . $result)
            ]);
            return $result;
        } catch (\Exception $e) {
            \Log::error('Upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'path' => $absolutePath,
                'permissions' => file_exists($absolutePath) ? substr(sprintf('%o', fileperms($absolutePath)), -4) : 'N/A',
                'directory_exists' => file_exists($absolutePath),
                'is_writable' => is_writable($absolutePath)
            ]);
            throw $e;
        }
    }

    public function remove($id)
    {
        $frontend = Frontend::findOrFail($id);
        $key = explode('.', @$frontend->data_keys)[0];
        $type = explode('.', @$frontend->data_keys)[1];
        if (@$type == 'element' || @$type == 'content') {
            $path = 'assets/images/frontend/' . $key;
            $imgJson = @getPageSections()->$key->$type->images;
            if ($imgJson) {
                foreach ($imgJson as $imgKey => $imgValue) {
                    fileManager()->removeFile($path . '/' . @$frontend->data_values->$imgKey);
                    fileManager()->removeFile($path . '/thumb_' . @$frontend->data_values->$imgKey);
                }
            }
            if (@getPageSections()->$key->element->seo) {
                fileManager()->removeFile($path . '/seo/' . @$frontend->seo_content->image);
            }
        }
        $frontend->delete();
        $notify[] = ['success', 'Content removed successfully'];
        return back()->withNotify($notify);
    }
}
