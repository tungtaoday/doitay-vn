<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;

class UploadController extends Controller
{
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
                'type' => 'required|string'
            ]);

            $path = 'assets/images/frontend/nicedit';
            $absolutePath = str_replace('\\', '/', 'C:/xampp/htdocs/' . $path);

            // Create directory if it doesn't exist
            if (!file_exists($absolutePath)) {
                if (!mkdir($absolutePath, 0777, true)) {
                    throw new \Exception("Failed to create directory: $absolutePath");
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

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // Move uploaded file
            if ($image->move($absolutePath, $filename)) {
                return response()->json([
                    'success' => true,
                    'url' => asset($path . '/' . $filename)
                ]);
            }

            throw new \Exception('Failed to move uploaded file');

        } catch (\Exception $e) {
            \Log::error('Image upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }
} 