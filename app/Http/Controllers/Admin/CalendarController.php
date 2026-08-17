<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\IraqiCalendar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    /**
     * Iraqi calendar + school holidays management.
     */
    public function index(Request $request): View
    {
        $month = $request->input('month', now()->format('Y-m'));
        $start = $month.'-01';
        $end = now()->parse($start)->endOfMonth()->toDateString();

        $iraqiDays = IraqiCalendar::whereBetween('gregorian_date', [$start, $end])
            ->orderBy('gregorian_date')
            ->get();

        $schoolHolidays = Holiday::orderByDesc('date_start')->get();

        return view('admin.calendar.index', compact('month', 'iraqiDays', 'schoolHolidays'));
    }

    /**
     * Add an Iraqi calendar entry (or toggle holiday flag).
     */
    public function storeIraqi(Request $request): RedirectResponse
    {
        $request->validate([
            'gregorian_date' => 'required|date',
            'iraqi_name' => 'nullable|string|max:255',
            'hijri_date' => 'nullable|string|max:255',
            'is_holiday' => 'nullable|boolean',
        ]);

        IraqiCalendar::updateOrCreate(
            ['gregorian_date' => $request->gregorian_date],
            [
                'iraqi_name' => $request->iraqi_name,
                'hijri_date' => $request->hijri_date,
                'is_holiday' => $request->boolean('is_holiday', false),
            ]
        );

        return back()->with('success', 'تم حفظ اليوم في التقويم العراقي.');
    }

    /**
     * Add a school holiday.
     */
    public function storeHoliday(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_start' => 'required|date',
            'date_stop' => 'required|date|after_or_equal:date_start',
        ]);

        Holiday::create($request->only(['name', 'date_start', 'date_stop']));

        return back()->with('success', 'تمت إضافة العطلة.');
    }

    public function destroyHoliday(Holiday $holiday): RedirectResponse
    {
        $holiday->delete();

        return back()->with('success', 'تم حذف العطلة.');
    }
}
