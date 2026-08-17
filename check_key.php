<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$keys = ['fees.invoices.method_cash', 'fees.invoices.method_card', 'fees.invoices.method_transfer', 'fees.invoices.method_wallet'];
foreach ($keys as $k) {
    echo $k . ' => ' . trans($k) . "\n";
}
