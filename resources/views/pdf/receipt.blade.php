@php
    $primaryColor = cache()->remember('edubba_admin_primary', 3600, fn () => App\Models\MobileAppConfig::configValue('primary_color', '#4f46e5'));
    $schoolName = cache()->remember('edubba_admin_school', 3600, fn () => App\Models\MobileAppConfig::configValue('school_name', 'Edubba School'));
    $logoUrl = App\Models\MobileAppConfig::configValue('logo_url', '');
    if ($logoUrl) {
        $logoContent = @file_get_contents($logoUrl);
        $logoDataUri = $logoContent ? 'data:image/png;base64,' . base64_encode($logoContent) : 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/edubba_app_icon.png')));
    } else {
        $logoDataUri = 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/edubba_app_icon.png')));
    }
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
        .meta { display: flex; gap: 26px; flex-wrap: wrap; margin-bottom: 18px; }
        .meta div { flex: 1; min-width: 160px; }
        .meta label { display: block; font-size: 9px; color: #6b7280; margin-bottom: 2px; }
        .meta strong { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        th, td { border: 1px solid #e5e7eb; padding: 8px 10px; text-align: right; }
        th { background: #f3f4f6; font-size: 10px; color: #374151; }
        td { font-size: 11px; }
        .amount-box { display: flex; justify-content: space-between; align-items: center; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; }
        .amount-box span { color: #374151; font-size: 11px; }
        .amount-box strong { font-size: 18px; color: {{ $primaryColor }}; }
        .meta-row { display: flex; gap: 20px; }
        .meta-col { flex: 1; }
        .stamp { text-align: center; margin-top: 34px; }
        .stamp .line { width: 200px; border-top: 1px solid #9ca3af; margin: 0 auto 4px; }
        .stamp span { font-size: 10px; color: #6b7280; }
        .footer { position: fixed; bottom: -30px; right: 0; left: 0; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #f3f4f6; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="doc-head">
        <div class="school">
            <img class="logo" src="{{ $logoDataUri }}" alt="{{ $schoolName }}">
            <div>
                <h1>{{ $schoolName }}</h1>
                <p>@lang('pdf.receipt.school_tagline')</p>
            </div>
        </div>
        <div class="doc-title">
            <h2>@lang('pdf.receipt.title')</h2>
            <span>@lang('pdf.receipt.title')</span>
        </div>
    </div>

    <div class="meta-row">
        <div class="meta-col">
            <label>@lang('pdf.receipt.receipt_no')</label>
            <strong>{{ $receipt->receipt_no }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.receipt.invoice_no')</label>
            <strong>{{ $receipt->invoice?->number }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.receipt.date')</label>
            <strong>{{ $receipt->date?->format('Y/m/d') }}</strong>
        </div>
        <div class="meta-col">
            <label>@lang('pdf.receipt.student_no')</label>
            <strong>{{ $receipt->invoice?->student?->student_code }}</strong>
        </div>
    </div>

    <div class="meta" style="margin-top:14px">
        <div>
            <label>@lang('pdf.receipt.student_name')</label>
            <strong>{{ $receipt->invoice?->student?->full_name }}</strong>
        </div>
        <div>
            <label>@lang('pdf.receipt.parent_name')</label>
            <strong>{{ $receipt->invoice?->parent?->name }}</strong>
        </div>
        <div>
            <label>@lang('pdf.receipt.payment_method')</label>
            <strong>{{ $receipt->payment?->method }}</strong>
        </div>
        <div>
            <label>@lang('pdf.receipt.payment_ref')</label>
            <strong>{{ $receipt->payment?->reference }}</strong>
        </div>
    </div>

    <div class="amount-box">
        <span>@lang('pdf.receipt.amount_paid')</span>
        <strong>{{ number_format($receipt->amount, 0) }} @lang('pdf.currency')</strong>
    </div>

    <div class="stamp">
        <div class="line"></div>
        <span>@lang('pdf.receipt.cashier_sign')</span>
    </div>

    <div class="footer">{{ $schoolName }} © {{ date('Y') }} — @lang('pdf.footer_system')</div>
</body>
</html>
