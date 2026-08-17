<?php

namespace App\Support;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Rejects dangerous uploads on any request (photo/file/document/avatar).
 */
class ValidateUpload
{
    public function handle(Request $request, Closure $next, string $kind = 'image')
    {
        foreach (['photo', 'file', 'document', 'avatar', 'image'] as $field) {
            if ($request->hasFile($field)) {
                try {
                    UploadPolicy::validate($request->file($field), $kind);
                } catch (ValidationException $e) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'status' => 'error',
                            'errors' => $e->errors(),
                        ], 422);
                    }

                    return back()->withErrors($e->errors())->withInput();
                }
            }
        }

        return $next($request);
    }
}
