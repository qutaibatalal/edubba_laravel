@props([
    'label' => null,
    'name' => '',
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'icon' => null,
    'error' => null,
])

<div {{ $attributes->whereDoesntStartWith(['class'])->merge(['class' => 'mb-3']) }}>
    @if ($label)<label class="form-label" for="{{ $name }}" style="font-weight:500;font-size:.85rem;color:var(--edb-text-2);letter-spacing:.01em;">{{ $label }}</label>@endif
    <div class="position-relative">
        @if ($icon)<i class="bi {{ $icon }} position-absolute" style="inset-inline-start:12px;top:50%;transform:translateY(-50%);color:var(--edb-text-3);font-size:.9rem;opacity:.7;"></i>@endif
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value }}"
            placeholder="{{ $placeholder }}"
            class="form-control {{ $icon ? 'ps-4' : '' }} @error($name) is-invalid @enderror"
            style="border-radius:var(--edb-radius-sm);border-color:var(--edb-border);font-size:.875rem;padding:.55rem .85rem;transition:border-color .18s ease,box-shadow .18s ease;"
            onfocus="this.style.borderColor='var(--edb-primary)';this.style.boxShadow='0 0 0 3px rgba(var(--edb-primary-rgb,59,130,246),.12)'"
            onblur="this.style.borderColor='var(--edb-border)';this.style.boxShadow='none'">
        @if ($error)<div class="invalid-feedback">{{ $error }}</div>@endif
    </div>
    @error($name)
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
    {{ $slot }}
</div>
