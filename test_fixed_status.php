<?php

require_once 'bootstrap/app.php';
require_once 'app/Helpers/statusWhosaleInputTaxHelper.php';

// ทดสอบ QT25080148
$quoteNumber = 'QT25080148';

echo "Testing getStatusWhosaleInputTax with database-only check:\n";
echo "Quote Number: {$quoteNumber}\n";

$status = getStatusWhosaleInputTax($quoteNumber);
echo "Status: {$status}\n";

// ตรวจสอบข้อมูลในฐานข้อมูล
$records = \Illuminate\Support\Facades\DB::table('input_tax')
    ->where('input_tax_quote_number', $quoteNumber)
    ->where('input_tax_status', 'success')
    ->where('input_tax_type', 4) // ต้องเป็นประเภทโฮลเซล
    ->whereNotNull('input_tax_file')
    ->where('input_tax_file', '!=', '')
    ->get();

echo "\nDatabase records found: " . $records->count() . "\n";

foreach ($records as $record) {
    echo "File: {$record->input_tax_file}\n";
    $fullPath = public_path($record->input_tax_file);
    echo "Full path: {$fullPath}\n";
    echo "File exists: " . (file_exists($fullPath) ? 'YES' : 'NO') . "\n";
    echo "---\n";
}

// ทดสอบกับ object
$quotation = \App\Models\quotations\quotationModel::where('quote_number', $quoteNumber)->first();
if ($quotation && isset($quotation->checkfileInputtax)) {
    echo "\nTesting with checkfileInputtax object:\n";
    $objectStatus = getStatusWhosaleInputTax($quotation->checkfileInputtax);
    echo "Object status: {$objectStatus}\n";
}

?>
