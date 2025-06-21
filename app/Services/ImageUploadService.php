<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageUploadService
{
    /**
     * Upload an image file and return the path
     *
     * @param UploadedFile|null $file
     * @param string $directory
     * @param string|null $currentImageUrl
     * @return string|null
     */    public function uploadImage(?UploadedFile $file, string $directory = 'fields', ?string $currentImageUrl = null): ?string
    {
        if (!$file || !$file->isValid()) {
            return $currentImageUrl;
        }

        try {
            // Security checks (although middleware should have handled these)
            $this->validateImage($file);
            
            // Generate a unique file name to avoid collisions
            $fileName = time() . '_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            // Save file to storage
            $path = "assets/images/{$directory}";
            $fullPath = public_path($path);
            
            // Make sure the directory exists
            if (!file_exists($fullPath)) {
                mkdir($fullPath, 0755, true);
            }
            
            // Move the uploaded file
            $file->move($fullPath, $fileName);
            
            // Return relative path (not full URL) to avoid domain issues
            return "{$path}/{$fileName}";
            
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage(), [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage()
            ]);
            
            // Return current image URL if upload fails
            return $currentImageUrl;
        }
    }

    /**
     * Upload multiple image files and return array of paths
     *
     * @param array|null $files
     * @param string $directory
     * @param array|null $currentGallery
     * @return array|null
     */
    public function uploadMultipleImages(?array $files, string $directory = 'fields', ?array $currentGallery = null): ?array
    {
        // If no files provided, return current gallery
        if (!$files || empty($files)) {
            return $currentGallery;
        }
        
        $uploadedImages = [];
        
        // Keep existing gallery images if available
        if ($currentGallery && is_array($currentGallery)) {
            $uploadedImages = $currentGallery;
        }
        
        // Process each file
        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                try {
                    // Security checks
                    $this->validateImage($file);
                    
                    // Generate a unique file name
                    $fileName = time() . '_' . mt_rand(1000, 9999) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                    
                    // Save file to storage
                    $path = "assets/images/{$directory}";
                    $file->move(public_path($path), $fileName);
                    
                    // Add image URL to array
                    $uploadedImages[] = asset("{$path}/{$fileName}");
                    
                } catch (\Exception $e) {
                    Log::error('Image upload failed: ' . $e->getMessage(), [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                    // Continue with other files even if one fails
                    continue;
                }
            }
        }
        
        return $uploadedImages;
    }

    /**
     * Validate that the uploaded file is a valid image with allowed dimensions and size
     *
     * @param UploadedFile $file
     * @return bool
     * @throws \Exception
     */
    protected function validateImage(UploadedFile $file): bool
    {
        // Check file size (max 2MB)
        if ($file->getSize() > 2048 * 1024) {
            throw new \Exception('File size exceeds the 2MB limit');
        }
        
        // Verify extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
            throw new \Exception('Invalid file extension');
        }
        
        // Verify mime type
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($file->getMimeType(), $allowedMimes)) {
            throw new \Exception('Invalid file type');
        }
        
        return true;
    }
}
