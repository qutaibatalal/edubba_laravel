<?php

namespace App\Services;

use App\Models\ExamResult;
use App\Models\Marksheet;
use App\Models\MarksheetLine;
use Illuminate\Support\Facades\DB;

class ExamService
{
    /**
     * Enter marks for a marksheet line (creating/updating the line) and
     * recompute the parent marksheet.
     */
    public static function enterMarks(Marksheet $marksheet, array $data): MarksheetLine
    {
        return DB::transaction(function () use ($marksheet, $data) {
            $line = MarksheetLine::updateOrCreate(
                ['marksheet_id' => $marksheet->id, 'subject_id' => $data['subject_id'] ?? null, 'course_id' => $data['course_id'] ?? null],
                [
                    'max_marks' => $data['max_marks'] ?? 0,
                    'marks' => $data['marks'] ?? 0,
                    'pass_marks' => $data['pass_marks'] ?? 0,
                ]
            );

            $line->percentage = $line->max_marks > 0 ? round(($line->marks / $line->max_marks) * 100, 2) : 0;
            $line->grade = GradeService::gradeFor($line->percentage);
            $line->passed = $line->marks >= $line->pass_marks;
            $line->save();

            self::recompute($marksheet);

            return $line;
        });
    }

    /**
     * Recompute totals, percentage, grade, result and rank for a marksheet.
     */
    public static function recompute(Marksheet $marksheet): Marksheet
    {
        $lines = $marksheet->lines;

        $marksheet->total_marks = $lines->sum('max_marks');
        $marksheet->obtained_marks = $lines->sum('marks');
        $marksheet->percentage = $marksheet->total_marks > 0
            ? round(($marksheet->obtained_marks / $marksheet->total_marks) * 100, 2)
            : 0;
        $marksheet->grade = GradeService::gradeFor($marksheet->percentage);
        $marksheet->result = $lines->isNotEmpty() && $lines->every(fn ($l) => $l->passed)
            ? Marksheet::RESULT_PASS
            : Marksheet::RESULT_FAIL;
        $marksheet->save();

        self::rankWithinBatch($marksheet);

        return $marksheet;
    }

    /**
     * Rank marksheets within the same exam + batch, descending by percentage.
     */
    public static function rankWithinBatch(Marksheet $marksheet): void
    {
        $peers = Marksheet::query()
            ->where('exam_id', $marksheet->exam_id)
            ->where('batch_id', $marksheet->batch_id)
            ->orderByDesc('percentage')
            ->get();

        $rank = 1;
        foreach ($peers as $peer) {
            $peer->rank = $rank++;
            $peer->save();
        }
    }

    /**
     * Finalize a marksheet and aggregate into exam_results.
     */
    public static function finalize(Marksheet $marksheet): Marksheet
    {
        if ($marksheet->state !== Marksheet::STATE_DRAFT) {
            throw new \DomainException("Marksheet already in state '{$marksheet->state}'.");
        }

        self::recompute($marksheet);
        $marksheet->state = Marksheet::STATE_DONE;
        $marksheet->finalized_at = now();
        $marksheet->save();

        self::aggregateResult($marksheet);

        return $marksheet;
    }

    /**
     * Aggregate a single student's result across exams in the term/year.
     */
    public static function aggregateResult(Marksheet $marksheet): ExamResult
    {
        $doneMarksheets = Marksheet::query()
            ->where('student_id', $marksheet->student_id)
            ->where('state', Marksheet::STATE_DONE)
            ->whereHas('exam', fn ($q) => $q->where('academic_year_id', $marksheet->exam->academic_year_id))
            ->get();

        $total = $doneMarksheets->sum('total_marks');
        $obtained = $doneMarksheets->sum('obtained_marks');
        $average = $total > 0 ? round(($obtained / $total) * 100, 2) : 0;
        $allPassed = $doneMarksheets->isNotEmpty() && $doneMarksheets->every(fn ($m) => $m->result === Marksheet::RESULT_PASS);

        return ExamResult::updateOrCreate(
            [
                'student_id' => $marksheet->student_id,
                'academic_year_id' => $marksheet->exam->academic_year_id,
                'batch_id' => $marksheet->batch_id,
            ],
            [
                'exam_id' => $marksheet->exam_id,
                'term_id' => $marksheet->exam->term_id,
                'total' => $obtained,
                'average' => $average,
                'grade' => GradeService::gradeFor($average),
                'result' => $allPassed ? ExamResult::RESULT_PASS : ExamResult::RESULT_FAIL,
            ]
        );
    }
}
