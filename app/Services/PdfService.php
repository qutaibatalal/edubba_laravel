<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfService
{
    /**
     * Build an Mpdf instance configured for Arabic (Tajawal) documents.
     */
    public static function mpdf(): Mpdf
    {
        $tempDir = storage_path('framework/cache/mpdf');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        return new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'xbriyaz',
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
            'margin_top' => 20,
            'margin_bottom' => 26,
            'margin_left' => 15,
            'margin_right' => 15,
            'tempDir' => $tempDir,
            'fontDir' => [storage_path('fonts')],
            'fontdata' => [
                'xbriyaz' => [
                    'R' => 'XB Riyaz.ttf',
                    'B' => 'XB RiyazBd.ttf',
                    'useOTL' => 0x80,
                    'useKashida' => 75,
                ],
            ],
        ]);
    }

    /**
     * Render a Blade view to an HTML string for mpdf.
     */
    public static function html(string $view, array $data = []): string
    {
        return view($view, $data)->render();
    }

    /**
     * Render a view into PDF bytes.
     */
    public static function render(string $view, array $data = []): string
    {
        $mpdf = self::mpdf();
        $mpdf->WriteHTML(self::html($view, $data));

        return $mpdf->Output('', Destination::STRING_RETURN);
    }

    /**
     * Return a PDF as a downloadable HTTP response.
     */
    public static function download(string $view, array $data = [], string $filename = 'document.pdf')
    {
        return response(self::render($view, $data), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
