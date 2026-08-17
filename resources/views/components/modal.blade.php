@props([
    'id' => null,
    'title' => null,
    'size' => null,
    'footer' => null,
])

@php $modalId = $id ?? 'modal-' . str()->random(8); @endphp

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $size === 'lg' ? 'modal-lg' : ($size === 'xl' ? 'modal-xl' : '') }} modal-dialog-centered">
        <div class="modal-content" style="border-radius:var(--edb-radius);border:1px solid var(--edb-border-strong);box-shadow:var(--edb-shadow-lg);overflow:hidden;background:var(--edb-bg);">
            @if ($title)
                <div class="modal-header" style="border-bottom:1px solid var(--edb-border);background:transparent;padding:1rem 1.25rem;">
                    <h5 class="modal-title fw-bold" style="font-size:1.05rem;letter-spacing:.01em;">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('common.close')"></button>
                </div>
            @endif
            <div class="modal-body" style="padding:1.25rem;">{{ $slot }}</div>
            @if ($footer)
                <div class="modal-footer" style="border-top:1px solid var(--edb-border);background:transparent;padding:1rem 1.25rem;">{{ $footer }}</div>
            @endif
        </div>
    </div>
</div>
