<?php

namespace App\Services;

use App\Models\Sequence;
use Illuminate\Support\Facades\DB;

class SequenceService
{
    /**
     * Generate the next sequential reference (e.g. INV/00001) atomically.
     */
    public static function next(string $name, ?string $prefix = null): string
    {
        return DB::transaction(function () use ($name, $prefix) {
            $seq = Sequence::where('name', $name)->first();

            if (! $seq) {
                $seq = Sequence::create([
                    'name' => $name,
                    'prefix' => $prefix,
                    'next' => 1,
                    'padding' => 5,
                ]);
            }

            $number = $seq->next;
            $seq->increment('next');

            $effectivePrefix = $prefix ?? $seq->prefix ?? '';

            return $effectivePrefix.'/'.str_pad((string) $number, $seq->padding, '0', STR_PAD_LEFT);
        });
    }
}
