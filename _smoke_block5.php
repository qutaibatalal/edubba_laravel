<?php

require __DIR__.'/vendor/autoload.php';

$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Ensure a parent api token exists
$parent = DB::table('api_users')->where('username', 'parent1')->first();
$token = $parent->api_token;
if (! $token) {
    $token = str()->random(64);
    DB::table('api_users')->where('id', $parent->id)->update(['api_token' => $token]);
}

$dispatch = function (string $method, string $path, array $headers = [], ?array $json = null) use ($token) {
    $request = Request::create($path, $method);
    if ($json !== null) {
        $request->setJson(new JsonResponse($json));
    }
    foreach ($headers as $k => $v) {
        $request->headers->set($k, $v);
    }
    $request->headers->set('Authorization', 'Bearer '.$token);
    $request->headers->set('Accept', 'application/json');

    $response = app()->make(Illuminate\Contracts\Http\Kernel::class)->handle($request);

    return $response;
};

echo "===== GET /v1/question-bank?grade=6 =====\n";
$r = $dispatch('GET', '/api/v1/question-bank?grade=6');
$body = json_decode($r->getContent(), true);
echo 'HTTP '.$r->getStatusCode().' status='.$body['status'].' count='.count($body['data'] ?? [])."\n";
echo (count($body['data']) > 0 ? 'sample: ' : '').(count($body['data']) > 0 ? $body['data'][0]['question'] : '')."\n\n";

echo "===== POST /v1/question-bank/practice =====\n";
$qid = $body['data'][0]['id'] ?? 1;
$correct = DB::table('ministry_questions')->where('id', $qid)->value('answer');
echo 'correct answer key in DB: '.$correct."\n";
$r2 = $dispatch('POST', '/api/v1/question-bank/practice', [], ['question_id' => $qid, 'answer' => $correct]);
$b2 = json_decode($r2->getContent(), true);
echo 'HTTP '.$r2->getStatusCode().' correct='.($b2['data']['correct'] ? 'YES' : 'NO')."\n";
echo 'explanation: '.$b2['data']['explanation']."\n\n";

echo "===== POST /v1/bus/{vehicle}/location (wrong token -> expect 401/403) =====\n";
$r3 = $dispatch('POST', '/api/v1/bus/1/location', ['Authorization' => 'Bearer bad-token'], ['latitude' => '33.315', 'longitude' => '44.3666', 'heading' => 90, 'speed' => 40]);
echo 'HTTP '.$r3->getStatusCode().' body='.substr($r3->getContent(), 0, 120)."\n\n";

echo "===== GET /v1/parent/bus-tracking =====\n";
$r4 = $dispatch('GET', '/api/v1/parent/bus-tracking');
$b4 = json_decode($r4->getContent(), true);
echo 'HTTP '.$r4->getStatusCode().' status='.$b4['status'].' vehicles='.count($b4['data'] ?? [])."\n";
if (count($b4['data']) > 0) {
    echo 'route: '.$b4['data'][0]['route_name'].' plate: '.($b4['data'][0]['vehicle']['plate_number'] ?? '-')."\n";
    echo 'stops: '.count($b4['data'][0]['stops'])."\n";
}
