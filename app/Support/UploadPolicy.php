<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

/**
 * Centralized file upload policy (Block 4 — File Upload Validation).
 *
 * Accepts only jpg/jpeg/png/pdf by real MIME sniffing (never the extension
 * alone) and enforces size limits: 5MB for images, 10MB for documents.
 */
class UploadPolicy
{
    public const IMAGE_MIMES = ['image/jpeg', 'image/png'];

    public const DOCUMENT_MIMES = ['application/pdf'];

    public const IMAGE_MAX = 5120; // KB

    public const DOCUMENT_MAX = 10240; // KB

    /**
     * Validate an uploaded file.
     *
     * @param  string  $kind  'image' | 'document'
     *
     * @throws ValidationException
     */
    public static function validate(UploadedFile $file, string $kind = 'image'): void
    {
        $mime = self::sniff($file);

        $allowed = $kind === 'document'
            ? self::DOCUMENT_MIMES
            : self::IMAGE_MIMES;

        if (! in_array($mime, $allowed, true)) {
            throw ValidationException::withMessages([
                'file' => ['نوع الملف غير مسموح. المقبول: '.implode(', ', $allowed).'.'],
            ]);
        }

        $maxKb = $kind === 'document' ? self::DOCUMENT_MAX : self::IMAGE_MAX;
        if ($file->getSize() > $maxKb * 1024) {
            throw ValidationException::withMessages([
                'file' => ['حجم الملف يتجاوز الحد المسموح ('.(int) ($maxKb / 1024).'MB).'],
            ]);
        }
    }

    /**
     * Sniff the real MIME type from the file contents.
     */
    public static function sniff(UploadedFile $file): string
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file->getRealPath());
            finfo_close($finfo);

            return $mime ?: $file->getMimeType() ?: '';
        }

        return $file->getMimeType() ?: '';
    }
}
