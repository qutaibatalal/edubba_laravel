<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\IraqiCalendar;
use App\Models\MobileAppConfig;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
    /**
     * GET /academic-years — cached for 24h (reference data, rarely changes).
     */
    public function academicYears(Request $request): JsonResponse
    {
        $years = Cache::remember('academic_years_all', 86400, fn () =>
            AcademicYear::orderByDesc('date_start')->get()->map(fn ($y) => [
                'id' => $y->id,
                'name' => $y->name,
                'date_start' => $y->date_start?->toDateString(),
                'date_stop' => $y->date_stop?->toDateString(),
                'current' => (bool) $y->current,
            ])->toArray()
        );

        return response()->json([
            'status' => 'success',
            'data' => $years,
        ]);
    }

    /**
     * GET /calendar?month=2026-09 — Iraqi calendar for a month.
     * Returns study days and holidays.
     */
    public function calendar(Request $request): JsonResponse
    {
        $month = $request->string('month', now()->format('Y-m'))->toString();
        $start = Carbon::parse($month.'-01');
        $end = $start->copy()->endOfMonth();

        $cacheKey = "calendar_{$start->format('Y-m')}";

        $days = Cache::remember($cacheKey, 3600, function () use ($start, $end) {
            return IraqiCalendar::whereBetween('gregorian_date', [$start->toDateString(), $end->toDateString()])
                ->orderBy('gregorian_date')
                ->get()
                ->map(fn ($d) => [
                    'date' => $d->gregorian_date->toDateString(),
                    'hijri_date' => $d->hijri_date,
                    'name' => $d->iraqi_name,
                    'is_holiday' => $d->is_holiday,
                    'description' => $d->description,
                ])
                ->toArray();
        });

        $studyDays = collect($days)->where('is_holiday', false)->count();
        $holidays = collect($days)->where('is_holiday', true);

        return response()->json([
            'status' => 'success',
            'data' => [
                'month' => $start->format('Y-m'),
                'total_days' => count($days),
                'study_days' => $studyDays,
                'holidays_count' => $holidays->count(),
                'holidays' => $holidays->values(),
                'days' => $days,
            ],
        ]);
    }

    /**
     * GET /config — cached for 1h (app branding/config rarely changes).
     */
    public function appConfig(Request $request): JsonResponse
    {
        $data = Cache::remember('app_config_public', 3600, function () {
            $configs = MobileAppConfig::where('active', true)->get();
            $data = [];
            foreach ($configs as $config) {
                $data[$config->config_key] = $config->value;
            }
            return $data;
        });

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
