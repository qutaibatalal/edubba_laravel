@props([
    'name' => null,
    'size' => 'md',
    'tone' => 1,
])

@php
    $sizes = ['sm' => 'avatar-sm', 'md' => '', 'lg' => 'avatar-lg'];
    $tones = ['grad-1', 'grad-2', 'grad-3', 'grad-4', 'grad-5', 'grad-6'];
    $toneClass = $tones[(abs(crc32($name ?? '')) % 6)] ?? 'grad-1';
    $sizeClass = $sizes[$size] ?? '';
    $letter = $name ? mb_substr($name, 0, 1) : '?';
@endphp

<span {{ $attributes->merge(['class' => 'avatar ' . $sizeClass . ' ' . $toneClass]) }}>{{ $letter }}</span>
