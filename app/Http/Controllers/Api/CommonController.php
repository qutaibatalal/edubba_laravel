<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\MobileAppConfig;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    /**
     * GET /academic-years
     */
    public function academicYears(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => AcademicYear::orderByDesc('date_start')->get()->map(fn ($y) => [
                'id' => $y->id,
                'name' => $y->name,
                'date_start' => $y->date_start?->toDateString(),
                'date_stop' => $y->date_stop?->toDateString(),
                'current' => (bool) $y->current,
            ]),
        ]);
    }

    /**
     * GET /config
     */
    public function appConfig(Request $request): JsonResponse
    {
        $configs = MobileAppConfig::where('active', true)->get();

        $data = [];
        foreach ($configs as $config) {
            $data[$config->config_key] = $config->value;
        }

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }
}
