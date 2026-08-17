@props([
    'title' => null,
    'icon' => null,
    'actions' => null,
    'bodyClass' => null,
    'hoverable' => false,
    'glass' => false,
])

@php
    $class = 'card ' . ($hoverable ? 'hoverable' : '');
    $style = 'border-radius:var(--edb-radius);border:1px solid var(--edb-border);box-shadow:var(--edb-shadow);';
    if ($glass) {
        $style .= 'backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);background:rgba(255,255,255,.65);';
    }
@endphp

<div {{ $attributes->merge(['class' => $class]) }}" style="{{ $style }}">
    @if ($title || $icon || $actions || isset($header))
        <div class="card-header d-flex justify-content-between align-items-center" style="border-bottom:1px solid var(--edb-border);background:transparent;padding:1rem 1.25rem;">
            <div class="d-flex align-items-center gap-2">
                @if ($icon)<i class="bi {{ $icon }} text-secondary" style="font-size:.95rem;opacity:.7;"></i>@endif
                <span style="font-weight:600;font-size:.9rem;letter-spacing:.01em;">{{ $title }}</span>
            </div>
            @if ($actions){{ $actions }}@endif
        </div>
    @endif
    <div class="card-body {{ $bodyClass }}">{{ $slot }}</div>
</div>
