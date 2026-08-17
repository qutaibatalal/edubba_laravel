<?php

use App\Models\MinistryQuestion;
use App\Models\Subject;

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== grades (صفوف) in the system ===\n";

$grades = [
    ['code' => '3', 'name' => 'ثالث المتوسط', 'level' => '3rd Intermediate'],
    ['code' => '6', 'name' => 'السادس الإعدادي', 'level' => '6th Preparatory'],
];

foreach ($grades as $g) {
    echo 'code: '.$g['code'].' | name: '.$g['name'].' | English: '.$g['level']."\n";
}

echo "\n=== Ministry Questions by stage ===\n";

$counts = [];
foreach (['3rd intermediate' => 'الثالث المتوسط', '6th preparatory' => 'السادس الإعدادي'] as $en => $ar) {
    $count = MinistryQuestion::where('stage', $ar)->count();
    echo "Stage: $ar ($en) => $count questions\n";
    $counts[$ar] = $count;
}

echo "\n=== Subject distribution ===\n";

$subjects = Subject::whereIn('code', ['biology', 'chemistry', 'physics', 'math'])->get()->keyBy('code');
foreach ($subjects as $code => $subject) {
    $qcount = MinistryQuestion::where('subject_id', $subject->id)->count();
    echo 'Subject: '.$subject->name." ($code) => $qcount questions\n";
}

echo "\n=== Demo login credentials ===\n";
echo "Admin: admin@edubba.test / password\n";
echo "Student: student1 / password (API user)\n";
echo "Parent: parent1 / password (API user)\n";
