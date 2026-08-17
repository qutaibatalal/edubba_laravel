@props([
    'tone' => 'success',
    'dismissible' => true,
    'icon' => null,
])

@php
    $tones = [
        'success' => ['alert-success', 'bi-check-circle-fill'],
        'danger' => ['alert-danger', 'bi-x-circle-fill'],
        'warning' => ['alert-warning', 'bi-exclamation-triangle-fill'],
        'info' => ['alert-info', 'bi-info-circle-fill'],
    ];
    [$toneClass, $defaultIcon] = $tones[$tone] ?? $tones['info'];
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $toneClass . ' d-flex align-items-center gap-3']) }}
     role="alert"
     style="border-radius:var(--edb-radius);border-width:1px;border-style:solid;box-shadow:none;font-size:.875rem;letter-spacing:.01em;">
    <i class="bi {{ $icon ?? $defaultIcon }} flex-shrink-0" style="font-size:1.1rem;opacity:.85;"></i>
    <div class="flex-grow-1" style="line-height:1.5;">{{ $slot }}</div>
    @if ($dismissible)
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="@lang('common.close')" style="filter:none;opacity:.45;transition:opacity .15s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='0.45'"></button>
    @endif
</div>
