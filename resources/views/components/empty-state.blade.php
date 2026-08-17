@props([
    'text' => __('common.no_data'),
    'sub' => null,
    'icon' => 'bi-inbox',
    'action' => null,
])

<div {{ $attributes->merge(['class' => 'empty-state']) }}
     style="text-align:center;padding:3rem 1.5rem;color:var(--edb-text-3);">
    <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;border-radius:50%;background:var(--edb-border);margin-bottom:1rem;opacity:.55;">
        <i class="bi {{ $icon }}" style="font-size:1.5rem;"></i>
    </div>
    <p style="font-weight:600;color:var(--edb-text-2);margin-bottom:.25rem;">{{ $text }}</p>
    @if ($sub)<small style="color:var(--edb-text-3);font-size:.8rem;opacity:.8;display:block;margin-top:.15rem;">{{ $sub }}</small>@endif
    @if ($action)<div class="mt-3">{{ $action }}</div>@endif
</div>
