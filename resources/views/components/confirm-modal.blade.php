@props([
    'title' => __('common.confirm_title'),
    'body' => __('common.confirm_body'),
    'confirmText' => __('common.confirm'),
    'cancelText' => __('common.cancel'),
    'tone' => 'danger',
    'confirmAction' => 'confirmAction()',
])

<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="border-radius:var(--edb-radius);border:1px solid var(--edb-border-strong);box-shadow:var(--edb-shadow-lg);overflow:hidden;">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <span class="avatar avatar-lg mx-auto" style="background:rgba(239,68,68,.12);color:#dc2626;border-radius:50%;width:56px;height:56px;display:inline-flex;align-items:center;justify-content:center;font-size:1.3rem;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                    </span>
                </div>
                <h5 class="fw-bold mb-1" style="font-size:1.05rem;letter-spacing:.01em;">{{ $title }}</h5>
                <p class="text-secondary small mb-3" style="line-height:1.6;opacity:.75;">{{ $body }}</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="border-radius:var(--edb-radius-sm);font-weight:500;">{{ $cancelText }}</button>
                    <button type="button" class="btn btn-{{ $tone }}" id="confirmModalOk" style="border-radius:var(--edb-radius-sm);font-weight:500;">{{ $confirmText }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let confirmCallback = null;
    document.getElementById('confirmModalOk').addEventListener('click', function () {
        if (confirmCallback) confirmCallback();
        confirmCallback = null;
        bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
    });
    function openConfirm(cb) { confirmCallback = cb; new bootstrap.Modal(document.getElementById('confirmModal')).show(); }
</script>
@endpush
