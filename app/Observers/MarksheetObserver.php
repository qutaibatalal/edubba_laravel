<?php

namespace App\Observers;

use App\Models\Marksheet;
use App\Services\ExamService;

class MarksheetObserver
{
    /**
     * Re-entrancy guard. recompute() calls save(), which fires `updated`
     * before Eloquent syncs the original state, so wasChanged('state') would
     * stay true and loop forever.
     */
    protected static bool $recomputing = false;

    /**
     * When a marksheet is created (e.g. seeded), recompute its derived fields.
     */
    public function created(Marksheet $marksheet): void
    {
        if ($marksheet->lines()->count() > 0) {
            ExamService::recompute($marksheet);
        }
    }

    /**
     * When a marksheet is marked done, recompute derived fields.
     */
    public function updated(Marksheet $marksheet): void
    {
        if (self::$recomputing) {
            return;
        }

        if ($marksheet->wasChanged('state') && $marksheet->state === Marksheet::STATE_DONE) {
            self::$recomputing = true;

            try {
                ExamService::recompute($marksheet);
            } finally {
                self::$recomputing = false;
            }
        }
    }
}
