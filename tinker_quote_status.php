<?php
require __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Helpers/statusPaymentHelper.php';

use App\Models\quotations\quotationModel;

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

foreach (quotationModel::all() as $q) {
    echo $q->quote_number . ' => ' . trim(strip_tags(getQuoteStatusPayment($q))) . PHP_EOL;
}
