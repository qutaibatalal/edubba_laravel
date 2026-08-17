<?php

namespace App\Services;

use App\Models\StudyGroupSession;
use App\Models\Tutor;
use App\Models\TutorPayout;
use App\Models\TutorPayoutLine;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TutoringService
{
    /**
     * Complete a study-group session and compute a tutor payout line.
     */
    public static function completeSession(StudyGroupSession $session): StudyGroupSession
    {
        if ($session->state !== StudyGroupSession::STATE_SCHEDULED) {
            throw new \DomainException('Session is not scheduled.');
        }

        $session->state = StudyGroupSession::STATE_DONE;
        $session->save();

        if ($session->tutor_id) {
            self::recordPayoutForSession($session);
        }

        return $session;
    }

    /**
     * Record (or update) the tutor payout for a completed session.
     */
    public static function recordPayoutForSession(StudyGroupSession $session): TutorPayout
    {
        return DB::transaction(function () use ($session) {
            $tutor = Tutor::find($session->tutor_id);
            $hours = self::sessionHours($session);

            $periodStart = $session->date->startOfWeek()->toDateString();
            $periodEnd = $session->date->endOfWeek()->toDateString();

            $payout = TutorPayout::firstOrCreate(
                ['tutor_id' => $session->tutor_id, 'period_start' => $periodStart, 'period_end' => $periodEnd],
                ['reference' => SequenceService::next('tutor_payout', 'TPO'), 'state' => TutorPayout::STATE_DRAFT]
            );

            $line = TutorPayoutLine::updateOrCreate(
                ['tutor_payout_id' => $payout->id, 'study_group_session_id' => $session->id],
                [
                    'hours' => $hours,
                    'rate' => $tutor?->hourly_rate ?? 0,
                    'amount' => $hours * ($tutor?->hourly_rate ?? 0),
                ]
            );

            $payout->total_hours = $payout->lines->sum('hours');
            $payout->amount = $payout->lines->sum('amount');
            $payout->save();

            return $payout;
        });
    }

    protected static function sessionHours(StudyGroupSession $session): float
    {
        if (! $session->start_time || ! $session->end_time) {
            return 1;
        }

        $start = Carbon::parse($session->start_time);
        $end = Carbon::parse($session->end_time);

        return round(max(0, $start->diffInMinutes($end) / 60), 2);
    }
}
