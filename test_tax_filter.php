<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require_once 'app/Helpers/statusWhosaleInputTaxHelper.php';
require_once 'app/Helpers/statusCheckListHelper.php';

echo "=== ทดสอบการแก้ไขเงื่อนไข รอใบกำกับภาษีโฮลเซลล์ ===\n\n";

// ทดสอบกับ QT25080005 ที่เราทราบ
$quote = \App\Models\quotations\quotationModel::with(['quoteLogStatus', 'InputTaxVat', 'checkfileInputtax'])
    ->where('quote_number', 'QT25080005')
    ->first();

if ($quote) {
    echo "Testing quote: {$quote->quote_number} (Status: {$quote->quote_status})\n";
    
    $status = getStatusWhosaleInputTax($quote->quote_number);
    echo "getStatusWhosaleInputTax: " . strip_tags($status) . "\n";
    
    $isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
    echo "isWaitingForTaxDocuments: " . ($isWaiting ? 'YES (blocked from report)' : 'NO (will show in report)') . "\n\n";
    
    // ทดสอบด้วย Service เดิม
    echo "Testing with QuotationFilterServiceNew:\n";
    try {
        // Mock Auth
        Auth::shouldReceive('user')->andReturn((object)[
            'roles' => collect(['admin']), 
            'sale_id' => 1
        ]);
        
        $request = new \Illuminate\Http\Request();
        $results = \App\Services\QuotationFilterServiceNew::filter($request);
        $found = $results->where('quote_number', 'QT25080005')->first();
        
        echo "Found in QuotationFilterServiceNew: " . ($found ? 'YES' : 'NO') . "\n";
        
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "QT25080005 not found\n";
}

// ทดสอบกับ quote อื่นที่มี "รอใบกำกับภาษีโฮลเซลล์"
echo "\n=== ทดสอบกับ quote อื่นๆ ===\n";
$quotes = \App\Models\quotations\quotationModel::with(['quoteLogStatus', 'InputTaxVat', 'checkfileInputtax'])
    ->whereIn('quote_status', ['success', 'invoice'])
    ->take(5)
    ->get();

foreach ($quotes as $quote) {
    $status = getStatusWhosaleInputTax($quote->quote_number);
    if (strpos($status, 'รอใบกำกับภาษีโฮลเซลล์') !== false) {
        echo "Quote: {$quote->quote_number}\n";
        echo "  Status: " . strip_tags($status) . "\n";
        
        $isWaiting = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
        echo "  Will show in report: " . ($isWaiting ? 'NO (blocked)' : 'YES') . "\n\n";
    }
}
