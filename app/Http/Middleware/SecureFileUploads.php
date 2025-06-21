<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureFileUploads
{
    /**
     * List of allowed mime types for file uploads
     * 
     * @var array
     */
    protected $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/jpg',
    ];

    /**
     * Max file size in KB (2MB)
     * 
     * @var int
     */
    protected $maxFileSize = 2048;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */    public function handle(Request $request, Closure $next): Response
    {
        $fileFields = ['image', 'profile_image'];
        
        foreach ($fileFields as $fieldName) {
            if ($request->hasFile($fieldName)) {
                $file = $request->file($fieldName);
                
                // Check if file is valid
                if (!$file->isValid()) {
                    return redirect()->back()->withErrors([$fieldName => 'File upload failed'])->withInput();
                }
                
                // Check file size
                if ($file->getSize() > ($this->maxFileSize * 1024)) {
                    return redirect()->back()->withErrors([$fieldName => 'File size exceeds the maximum limit of 2MB'])->withInput();
                }
                
                // Check mime type
                $mime = $file->getMimeType();
                if (!in_array($mime, $this->allowedMimeTypes)) {
                    return redirect()->back()->withErrors([$fieldName => 'Invalid file type. Only JPG, JPEG and PNG are allowed'])->withInput();
                }
                
                // Additional security check - validate file extension matches mime type
                $extension = strtolower($file->getClientOriginalExtension());
                $validExtensions = ['jpg', 'jpeg', 'png'];
                
                if (!in_array($extension, $validExtensions)) {
                    return redirect()->back()->withErrors([$fieldName => 'Invalid file extension'])->withInput();
                }
            }
        }
        
        return $next($request);
    }
}
