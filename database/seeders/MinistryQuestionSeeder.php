<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\MinistryQuestion;
use App\Models\Subject;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Block 5 — Ministry question bank (3rd intermediate + 6th preparatory).
 *
 * Each MCQ stores its options as an ordered array and `answer` as the
 * letter (أ/ب/ج/د) of the correct choice so the practice endpoint can
 * grade instantly.
 */
class MinistryQuestionSeeder extends Seeder
{
    private array $subjects = ['biology' => 'علوم نباتية', 'chemistry' => 'كيمياء', 'physics' => 'فيزياء', 'math' => 'رياضيات'];

    private array $stages = ['الثالث المتوسط', 'السادس الإعدادي'];

    public function run(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        MinistryQuestion::query()->delete();

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }

        $yearId = AcademicYear::query()->latest('id')->value('id');

        $dept = Department::firstOrCreate(['name' => 'Sciences'], ['code' => 'SCI', 'active' => true]);

        $subjectMap = collect([]);
        foreach ($this->subjects as $code => $label) {
            $subjectMap[$code] = Subject::firstOrCreate(
                ['code' => $code],
                ['name' => $label, 'department_id' => $dept->id, 'is_language' => false, 'active' => true]
            );
        }
        $subjectMap = $subjectMap->keyBy('code');

        $count = 0;
        foreach ($this->stages as $stage) {
            foreach (array_keys($this->subjects) as $code) {
                $subject = $subjectMap[$code] ?? null;

                foreach ($this->questions()[$code] as $q) {
                    MinistryQuestion::create([
                        'subject_id' => $subject?->id,
                        'academic_year_id' => $yearId,
                        'stage' => $stage,
                        'question_type' => 'mcq',
                        'question' => $q['question'],
                        'options' => $q['options'],
                        'answer' => $q['answer'],
                        'marks' => $q['marks'],
                        'year' => (int) date('Y'),
                    ]);

                    $count++;
                }
            }
        }

        $this->command->info("Seeded {$count} ministry questions.");
    }

    /** Options keys map to answers: 0=>أ 1=>ب 2=>ج 3=>د */
    private function letters(): array
    {
        return ['أ', 'ب', 'ج', 'د'];
    }

    private function answerFor(int $index): string
    {
        return $this->letters()[$index];
    }

    private function questions(): array
    {
        $L = $this->letters();

        return [
            'biology' => [
                ['question' => 'ما هو أهم مركب تركيبي في الخلية؟', 'options' => ['البروتين', 'الكربون', 'الكلوروفيل', 'الـ DNA'], 'answer' => 'د', 'marks' => 1],
                ['question' => 'أي من التالي يحتوي على الحمض النووي؟', 'options' => ['الريسكو بروتين', 'الـ DNA والـ RNA', 'السيتوكروم', 'الأنزيمات'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'ما هو النوع الوظيفي المسؤول عن التمثيل الضوئي؟', 'options' => ['الكاروتين', 'الكلوروفيل', 'الكلوروفيل b', 'البيتوكاروتين'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'كم عدد الكروموسومات في الخلية بشرية ناضجة؟', 'options' => ['46', '44', '48', '23'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'أي عملية تنتج الألسجين (O2)؟', 'options' => ['التمثيل الغذائي', 'التنفس الخلوي', 'التصنيع الضوئي', 'النمو'], 'answer' => 'ج', 'marks' => 1],
                ['question' => 'ما هو دور الكبد؟', 'options' => ['تكوين البروتين الناقل', 'هضم الدهون', 'تكوين الدم', 'كل ما سبق'], 'answer' => 'د', 'marks' => 1],
            ],
            'chemistry' => [
                ['question' => 'ما هو عدد الذرات في مولكول O2؟', 'options' => ['1', '2', '3', '4'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'وحدة الضغط الدولية؟', 'options' => ['Pascal', ' Joule', 'Newton', 'Watt'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'نوع الروابطة H—O—H؟', 'options' => ['أيونية', 'كوvalent', 'حمضية', 'معدنية'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'أيها يتحلل إلى H2 و O2 عند التيار؟', 'options' => ['H2O', 'H2O2', 'HCl', 'NaCl'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'العنصر الأكثر ألكترونية؟', 'options' => ['الأكسجين', 'الكربون', 'الهيدروجين', 'الألكترون'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'ما المعقوم فيها يزيد الرقم الصفري؟', 'options' => ['الذكاء', 'الكثافة', 'التركيز', 'الحرارة'], 'answer' => 'ج', 'marks' => 1],
            ],
            'physics' => [
                ['question' => 'وحدة الطاقة الدولية؟', 'options' => ['Joule', 'Watt', 'Newton', 'Volt'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'sin(30°) = ؟', 'options' => ['½', '√3/2', '√2/2', '1'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'قانون نيوتن الثالث؟', 'options' => ['F=ma', 'لكل فعل رد فعل متكافئ', 'a=F/m', 'الطاقة الحركية'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'أي موجة أطول من الضوء المرئي؟', 'options' => ['أشعة إكس', 'أشعة راديو', 'أشعة غاما', 'فوق بنفسجي'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'طاقة السقوط من ارتفاع h؟', 'options' => ['mgh', '½mv²', 'mv', 'mgh²'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'الضغط عند عمق 10م في الماء (ρ=1000، g=10)؟', 'options' => ['100 kPa', '50 kPa', '200 kPa', '10 kPa'], 'answer' => 'أ', 'marks' => 1],
            ],
            'math' => [
                ['question' => 'جذور معادلة x² - 5x + 6 = 0؟', 'options' => ['2,3', '1,6', '-2,-3', 'لا جذور حقيقية'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'd/dx(sin x) = ؟', 'options' => ['sin x', 'cos x', '-cos x', 'tan x'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'مجموع زوايا المثلث؟', 'options' => ['180°', '90°', '360°', '270°'], 'answer' => 'أ', 'marks' => 1],
                ['question' => 'log10(100) = ؟', 'options' => ['1', '2', '10', '0'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'كم متغير في 3x + 2y - 5؟', 'options' => ['متغير واحد', 'اثنان', 'ثلاثة', 'لا متغيرات'], 'answer' => 'ب', 'marks' => 1],
                ['question' => 'الناتج: ∫ 2x dx؟', 'options' => ['x² + C', '2x² + C', 'x²', '2x + C'], 'answer' => 'أ', 'marks' => 1],
            ],
        ];
    }
}
