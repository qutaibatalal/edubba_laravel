@props([
    'tone' => 'soft',
    'label' => null,
])

@php
    $tones = [
        'success' => 'badge-soft-success',
        'danger' => 'badge-soft-danger',
        'warning' => 'badge-soft-warning',
        'info' => 'badge-soft-info',
        'primary' => 'badge-soft-primary',
        'purple' => 'badge-soft-purple',
        'soft' => 'badge-soft',
    ];
    $toneClass = $tones[$tone] ?? 'badge-soft';
@endphp

<span {{ $attributes->merge(['class' => 'badge ' . $toneClass]) }}
      style="border-radius:999px;font-size:.7rem;font-weight:600;letter-spacing:.03em;padding:.28em .7em;line-height:1.45;">{{ $label ?? $slot }}</span>
