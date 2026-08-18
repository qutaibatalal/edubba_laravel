<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IraqiCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $holidays = [
            ['gregorian_date' => '2026-01-01', 'iraqi_name' => 'رأس السنة الميلادية', 'hijri_date' => null],
            ['gregorian_date' => '2026-01-06', 'iraqi_name' => 'يوم الجيش العراقي', 'hijri_date' => null],
            ['gregorian_date' => '2026-03-21', 'iraqi_name' => 'عيد نوروز', 'hijri_date' => null],
            ['gregorian_date' => '2026-04-09', 'iraqi_name' => 'يوم تحرير العراق', 'hijri_date' => null],
            ['gregorian_date' => '2026-05-01', 'iraqi_name' => 'عيد العمال', 'hijri_date' => null],
            ['gregorian_date' => '2026-07-14', 'iraqi_name' => 'ذكرى ثورة 14 تموز', 'hijri_date' => null],
            ['gregorian_date' => '2026-10-03', 'iraqi_name' => 'عيد الجيش العراقي الثاني', 'hijri_date' => null],
            ['gregorian_date' => '2026-12-25', 'iraqi_name' => 'عيد الميلاد المجيد', 'hijri_date' => null],
            ['gregorian_date' => '2026-01-20', 'iraqi_name' => 'المولد النبوي الشريف', 'hijri_date' => '12 ربيع الأول 1448'],
            ['gregorian_date' => '2026-02-08', 'iraqi_name' => 'رأس السنة الهجرية', 'hijri_date' => '1 محرم 1448'],
            ['gregorian_date' => '2026-02-17', 'iraqi_name' => 'يوم عاشوراء', 'hijri_date' => '10 محرم 1448'],
            ['gregorian_date' => '2026-07-26', 'iraqi_name' => 'عيد الفطر', 'hijri_date' => '1 شوال 1447'],
            ['gregorian_date' => '2026-10-02', 'iraqi_name' => 'عيد الأضحى', 'hijri_date' => '10 ذي الحجة 1447'],
        ];

        foreach ($holidays as $row) {
            DB::table('iraqi_calendars')->updateOrInsert(
                ['gregorian_date' => $row['gregorian_date']],
                array_merge($row, ['is_holiday' => true, 'description' => 'عطلة رسمية في العراق'])
            );
        }
    }
}
