<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageUploader
{
    /**
     * Upload an image file. If filesystem is writable (e.g. localhost), stores in public/uploads/{folder}.
     * If filesystem is read-only (e.g. Vercel serverless), converts to base64 Data URI for direct DB storage.
     */
    public static function upload(UploadedFile $file, string $folder = 'products'): string
    {
        $uploadDirectory = public_path("uploads/{$folder}");
        $canWriteToPublic = false;

        try {
            if (! File::isDirectory($uploadDirectory)) {
                @File::makeDirectory($uploadDirectory, 0755, true, true);
            }
            $canWriteToPublic = is_dir($uploadDirectory) && is_writable($uploadDirectory);
        } catch (\Throwable $e) {
            $canWriteToPublic = false;
        }

        if ($canWriteToPublic) {
            try {
                $filename = Str::singular($folder).'_'.time().'_'.Str::random(10).'.'.$file->getClientOriginalExtension();
                $file->move($uploadDirectory, $filename);

                return "uploads/{$folder}/".$filename;
            } catch (\Throwable $e) {
                // Fallback to base64 if move fails
            }
        }

        // Serverless (Vercel) base64 fallback
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $contents = file_get_contents($file->getRealPath());
        $base64 = base64_encode($contents);

        return "data:{$mime};base64,{$base64}";
    }

    /**
     * Delete an existing local image file if it exists and is not a URL/base64.
     */
    public static function delete(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        if (str_starts_with($path, 'data:') || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        try {
            $fullPath = public_path($path);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        } catch (\Throwable $e) {
            // Ignore deletion errors on serverless
        }
    }
}
