@props([
    'columns' => [],
    'emptyText' => __('common.no_data'),
    'emptyIcon' => 'bi-inbox',
])

<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'table table-edb mb-0']) }}>
        @if (count($columns) > 0)
            <thead>
                <tr>
                    @foreach ($columns as $col)
                        <th @isset($col['class']) class="{{ $col['class'] }}" @endisset>{{ is_array($col) ? $col['label'] : $col }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            @if (($rows ?? []) && count($rows) > 0)
                @foreach ($rows as $row)
                    <tr @isset($row['_class']) class="{{ $row['_class'] }}" @endisset>
                        @foreach ($columns as $i => $col)
                            @php
                                $key = is_array($col) ? ($col['key'] ?? $i) : $i;
                                $value = $row[$key] ?? '';
                            @endphp
                            <td @isset($col['class']) class="{{ $col['class'] }}" @endisset>{!! $value !!}</td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ max(count($columns), 1) }}">
                        <div class="empty-state"><i class="bi {{ $emptyIcon }}"></i><p>{{ $emptyText }}</p></div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
