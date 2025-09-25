<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\quotations\quotationModel;
use Illuminate\Support\Facades\Log;

// Set specific quote number
$quoteNumber = 'QT25080076';

// Get the quotation with all relevant relationships
$quote = quotationModel::where('quote_number', $quoteNumber)
    ->with([
        'quoteCheckStatus',
        'quoteLogStatus',
        'InputTaxVat',
        'checkfileInputtax'
    ])
    ->first();

if (!$quote) {
    echo "Quotation $quoteNumber not found!\n";
    exit;
}

echo "Quotation Details:\n";
echo "ID: {$quote->quote_id}\n";
echo "Number: {$quote->quote_number}\n";
echo "Grand Total: {$quote->quote_grand_total}\n\n";

// Check InputTaxVat (Wholesale costs)
echo "InputTaxVat Records: " . ($quote->InputTaxVat ? $quote->InputTaxVat->count() : 0) . "\n";
if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $index => $tax) {
        echo "Tax Record #{$index}:\n";
        echo "  - ID: {$tax->input_tax_id}\n";
        echo "  - Status: " . ($tax->input_tax_status ?? 'NULL') . "\n";
        echo "  - Grand Total: {$tax->input_tax_grand_total}\n";
        echo "  - Type: {$tax->input_tax_type}\n";
    }
}

// Check quoteCheckStatus
echo "\nQuote Check Status:\n";
if ($quote->quoteCheckStatus) {
    echo "wholesale_tax_status: " . ($quote->quoteCheckStatus->wholesale_tax_status ?? 'NULL') . "\n";
} else {
    echo "quoteCheckStatus is NULL\n";
}

// Check quoteLogStatus
echo "\nQuote Log Status:\n";
if ($quote->quoteLogStatus) {
    echo "input_tax_status: " . ($quote->quoteLogStatus->input_tax_status ?? 'NULL') . "\n";
    echo "input_tax_withholding_status: " . ($quote->quoteLogStatus->input_tax_withholding_status ?? 'NULL') . "\n";
} else {
    echo "quoteLogStatus is NULL\n";
}

// Debug checkfileInputtax
echo "\ncheckfileInputtax:\n";
if ($quote->checkfileInputtax) {
    echo "Found checkfileInputtax record\n";
} else {
    echo "No checkfileInputtax record\n";
}

// Manually check tax document status condition
$isWaiting = false;
if ($quote->quoteCheckStatus) {
    $wholesaleTaxStatus = $quote->quoteCheckStatus->wholesale_tax_status;
    $isWaiting = is_null($wholesaleTaxStatus) || trim($wholesaleTaxStatus) !== 'ได้รับแล้ว';
}
echo "\nManual Check: Is waiting for wholesale tax invoice? " . ($isWaiting ? "YES" : "NO") . "\n";

// Test our modified filter logic
function manualCheckTaxStatus($quote) {
    // Check for wholesale costs
    if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
        // Check wholesale tax status
        $wholesaleTaxStatus = isset($quote->quoteCheckStatus) ? 
            $quote->quoteCheckStatus->wholesale_tax_status : null;
        
        // If not 'ได้รับแล้ว' then waiting
        $isWaiting = is_null($wholesaleTaxStatus) || 
                     trim($wholesaleTaxStatus) !== 'ได้รับแล้ว';
        
        if ($isWaiting) {
            return true; // Waiting for wholesale tax invoice
        }
    }
    return false; // Not waiting
}

echo "Testing our modified filter: Should be filtered out? " . 
    (manualCheckTaxStatus($quote) ? "YES" : "NO") . "\n";

// Get raw SQL that would be used for retrieving CheckStatus
$checkStatusQuery = \App\Models\QuoteLogModel::where('quote_id', $quote->quote_id)->toSql();
echo "\nCheckStatus SQL: $checkStatusQuery\n";