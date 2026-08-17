@props([
    'rows' => 3,
    'cols' => 6,
])

<div {{ $attributes->merge(['class' => 'table-responsive']) }}>
    <table class="table table-edb mb-0">
        <tbody>
            @for ($r = 0; $r < $rows; $r++)
                <tr>
                    @for ($c = 0; $c < $cols; $c++)
                        <td>
                            <span class="skeleton-line d-block rounded"
                                  style="height:14px;width:{{ rand(55, 90) }}%;background:var(--edb-border);border-radius:var(--edb-radius-xs);position:relative;overflow:hidden;"
                            >
                                <span style="position:absolute;inset:0;background:linear-gradient(90deg,transparent,rgba(255,255,255,.5),transparent);animation:shimmer 1.6s infinite;"></span>
                            </span>
                        </td>
                    @endfor
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<style>
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
</style>
