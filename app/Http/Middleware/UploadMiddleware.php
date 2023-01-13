<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $inputField, $forwardField)
    {
        $appEnv = app()->environment();
        if ($request->hasFile($inputField)) {
            $uploadedFile = $request->file($inputField);

            if (is_array($uploadedFile)) {
                // Handle multiple file upload
                $fileUrls = [];
                foreach ($uploadedFile as $file) {
                    $path = $file->storePublicly("public/{$appEnv}");
                    $fileUrl = Storage::url($path);
                    $fileUrls[] = $fileUrl;
                }
                $request->merge([
                    $forwardField => $fileUrls,
                ]);
            } else {
                // Handle single file upload
                $path = $uploadedFile->storePublicly("public/{$appEnv}");
                $fileUrl = Storage::url($path);

                $request->merge([
                    $forwardField => $fileUrl,
                ]);
            }
        }
        return $next($request);
    }
}
