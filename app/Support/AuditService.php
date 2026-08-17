<?php

namespace App\Support;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;

/**
 * Audit trail (Block 4 — Audit Log).
 *
 * Persists every meaningful change (create/update/delete + auth events) into
 * the filterable `activities` table: who did what, when, and what changed.
 */
class AuditService
{
    /**
     * Record an activity event.
     *
     * @param  Model|null  $subject  model being changed (polymorphic)
     * @param  string  $type  e.g. created|updated|deleted|login|logout
     * @param  string  $body  human-readable summary
     * @param  array<string, mixed>|null  $changes  before/after diff
     */
    public static function log(?Model $subject, string $type, string $body, ?array $changes = null): void
    {
        try {
            Activity::create([
                'subject_type' => $subject ? $subject->getMorphClass() : null,
                'subject_id' => $subject?->getKey(),
                'type' => $type,
                'body' => $body,
                'user_id' => self::currentUserId(),
                'changes' => $changes,
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Build a compact before/after diff of changed attributes.
     */
    public static function diff(Model $model, array $original): array
    {
        $dirty = $model->getDirty();
        $out = [];

        foreach ($dirty as $key => $value) {
            $out[$key] = ['from' => $original[$key] ?? null, 'to' => $value];
        }

        return $out;
    }

    protected static function currentUserId(): ?int
    {
        $guards = ['web', 'admin', 'sanctum'];

        foreach ($guards as $guard) {
            $user = auth($guard)->user();
            if ($user) {
                return $user->getKey();
            }
        }

        return null;
    }
}
