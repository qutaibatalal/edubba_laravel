@props([
    'label' => '',
    'value' => 0,
    'icon' => 'bi-activity',
    'tone' => 1,
    'hint' => null,
    'countUp' => true,
])

@php
    $tones = [1 => 'st-1', 2 => 'st-2', 3 => 'st-3', 4 => 'st-4', 5 => 'st-5', 6 => 'st-6'];
    $toneClass = $tones[$tone] ?? 'st-1';
    $displayValue = is_numeric($value) ? number_format((float) $value) : $value;
@endphp

<div {{ $attributes->merge(['class' => 'card stat-card ' . $toneClass]) }}
     style="border-radius:var(--edb-radius);border:1px solid var(--edb-border);box-shadow:var(--edb-shadow);transition:box-shadow .2s ease,transform .2s ease;backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);"
     onmouseover="this.style.boxShadow='var(--edb-shadow-md)';this.style.transform='translateY(-1px)'"
     onmouseout="this.style.boxShadow='var(--edb-shadow)';this.style.transform='translateY(0)'">
    <div class="stat-body">
        <div class="stat-icon"><i class="bi {{ $icon }}"></i></div>
        <div>
            <div class="stat-value num" @if ($countUp && is_numeric($value)) data-count="{{ $value }}" @endif>{{ $displayValue }}</div>
            <div class="stat-label" style="font-size:.8rem;font-weight:500;color:var(--edb-text-3);letter-spacing:.02em;">{{ $label }}</div>
            @if ($hint)<div class="small" style="color:var(--edb-text-3);font-size:.75rem;opacity:.7;margin-top:.15rem;">{{ $hint }}</div>@endif
        </div>
    </div>
</div>
