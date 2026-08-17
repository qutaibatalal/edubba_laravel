@props([
    'value' => 0,
    'max' => 100,
    'tone' => 'primary',
])

@php
    $pct = $max > 0 ? min(100, round(($value / $max) * 100)) : 0;
    $colors = [
        'primary' => 'var(--edb-primary)',
        'success' => '#16a34a',
        'danger' => '#dc2626',
        'warning' => '#f59e0b',
        'info' => '#0ea5e9',
    ];
    $color = $colors[$tone] ?? 'var(--edb-primary)';
@endphp

<div {{ $attributes->merge(['class' => 'progress']) }}
     style="height:8px;background:var(--edb-border);border-radius:999px;overflow:hidden;">
    <div class="progress-bar" role="progressbar"
         style="width:{{ $pct }}%;background:{{ $color }};border-radius:999px;transition:width .4s cubic-bezier(.4,0,.2,1);">
    </div>
</div>
