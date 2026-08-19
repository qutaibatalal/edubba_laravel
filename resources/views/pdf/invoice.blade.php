@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'Edubba School'));
    $logoDataUri = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/edubba_app_icon.png')));
    $statusText = ['draft' => __('pdf.invoice.status_draft'), 'open' => __('pdf.invoice.status_open'), 'paid' => __('pdf.invoice.status_paid'), 'cancel' => __('pdf.invoice.status_cancel')][$invoice->state] ?? $invoice->state;
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: 'xbriyaz', sans-serif; box-sizing: border-box; }
        body { margin: 0; padding: 36px 40px; color: #111827; font-size: 12px; }
        .doc-head { display: flex; align-items: center; justify-content: space-between; border-bottom: 3px solid {{ $primaryColor }}; padding-bottom: 14px; margin-bottom: 22px; }
        .school { display: flex; align-items: center; gap: 12px; }
        .logo { width: 44px; height: 44px; border-radius: 12px; object-fit: cover; }
        .school h1 { margin: 0; font-size: 16px; font-weight: 800; }
        .school p { margin: 2px 0 0; color: #6b7280; font-size: 10px; }
        .doc-title { text-align: center; }
        .doc-title h2 { margin: 0; font-size: 15px; color: {{ $primaryColor }}; font-weight: 800; }
        .doc-title span { color: #6b7280; font-size: 10px; }
        .meta-row { display: flex; gap: 20px; margin-bottom: 18px; }
        .meta-col { flex: 1; }
        .meta-col label { display: block; font-size: 9px; color: #6b7280; margin-bottom: 2px; }
        .meta-col strong { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #e5e7eb; padding: 9px 10px; text-align: right; }
        th { background: #f3f4f6; font-size: 10px; color: #374151; }
        td { font-size: 11px; }
        .num { font-variant-numeric: tabular-nums; }
        .totals { width: 46%; margin-inline-start: auto; }
        .totals td { border: 0; padding: 5px 8px; font-size: 11px; }
        .totals .grand { font-weight: 800; font-size: 13px; color: {{ $primaryColor }}; border-top: 2px solid {{ $primaryColor }}; }
        .status { display: inline-block; padding: 3px 12px; border-radius: 999px; font-size: 10px; font-weight: 700; }
        .status.open { background: rgba(217,119,6,.14); color: #b45309; }
        .status.paid { background: rgba(34,197,94,.14); color: #15803d; }
        .status.draft { background: rgba(120,130,150,.14); color: #5b6472; }
        .status.cancel { background: rgba(239,68,68,.12); color: #b91c1c; }
        .footer { position: fixed; bottom: -30px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="doc-head">
        <div class="school">
            <img class="logo" src="{{ $logoDataUri }}" alt="{{ $schoolName }}">
            <div>
                <h1>{{ $schoolName }}</h1>
                <p>@lang('pdf.invoice.school_tagline')</p>
            </div>
        </div>
        <div class="doc-title">
            <h2>@lang('pdf.invoice.title')</h2>
            <span>@lang('pdf.invoice.title')</span>
        </div>
    </div>

    <div class="meta-row">
        <div class="meta-col">
            <label>@lang('pdf.invoice.invoice_no')</label>
            <strong>{{ $invoice->number }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.invoice.date')</label>
            <strong>{{ $invoice->date?->format('Y/m/d') }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.invoice.due_date')</label>
            <strong>{{ $invoice->due_date?->format('Y/m/d') }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.invoice.status')</label>
            <span class="status {{ $invoice->state }}">{{ $statusText }}</span>
        </div>
    </div>

    <div class="meta-row">
        <div class="meta-col">
            <label>@lang('pdf.invoice.student_name')</label>
            <strong>{{ $invoice->student?->full_name }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.invoice.student_no')</label>
            <strong>{{ $invoice->student?->student_code }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.invoice.batch')</label>
            <strong>{{ $invoice->student?->batch?->name }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.invoice.parent')</label>
            <strong>{{ $invoice->parent?->name }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr><th style="width:10%">#</th><th>@lang('pdf.invoice.col_item')</th><th style="width:12%">@lang('pdf.invoice.col_qty')</th><th style="width:18%">@lang('pdf.invoice.col_price')</th><th style="width:18%">@lang('pdf.invoice.col_total')</th></tr>
        </thead>
        <tbody>
            @forelse ($invoice->lines as $i => $line)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $line->description }}</td>
                    <td class="num">{{ $line->qty }}</td>
                    <td class="num">{{ number_format($line->unit_price, 0) }}</td>
                    <td class="num">{{ number_format($line->amount, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:#9ca3af">@lang('pdf.invoice.empty')</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="totals">
        <tr><td>@lang('pdf.invoice.subtotal')</td><td class="num">{{ number_format($invoice->subtotal, 0) }} @lang('pdf.currency')</td></tr>
        <tr><td>@lang('pdf.invoice.tax')</td><td class="num">{{ number_format($invoice->tax, 0) }} @lang('pdf.currency')</td></tr>
        <tr><td>@lang('pdf.invoice.paid')</td><td class="num">{{ number_format($invoice->paid, 0) }} @lang('pdf.currency')</td></tr>
        <tr class="grand"><td>@lang('pdf.invoice.balance')</td><td class="num">{{ number_format($invoice->balance, 0) }} @lang('pdf.currency')</td></tr>
    </table>

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
