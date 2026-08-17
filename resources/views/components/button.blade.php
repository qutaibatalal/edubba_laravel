@props([
    'variant' => 'primary',
    'type' => 'button',
    'size' => null,
    'icon' => null,
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'outline' => 'btn-outline-primary',
        'danger' => 'btn-danger',
        'outline-danger' => 'btn-outline-danger',
        'ghost' => 'btn-ghost',
        'success' => 'btn-success',
    ];
    $sizeClass = $size === 'sm' ? 'btn-sm' : ($size === 'lg' ? 'btn-lg' : '');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn ' . ($variants[$variant] ?? 'btn-primary') . ' ' . $sizeClass]) }}
    style="border-radius:var(--edb-radius-sm);font-weight:500;letter-spacing:.01em;transition:all .18s ease;box-shadow:none;outline:none;"
    onmouseover="this.style.boxShadow='0 2px 8px rgba(0,0,0,.08)';this.style.transform='translateY(-1px)'"
    onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'"
    onmousedown="this.style.transform='translateY(0)'">
    @if ($icon)<i class="bi {{ $icon }} me-1"></i>@endif
    {{ $slot }}
</button>
