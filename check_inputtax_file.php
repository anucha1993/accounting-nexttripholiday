<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\quotations\quotationModel;

// Set specific quote number
$quoteNumber = 'QT25080076';

// Get the quotation with checkfileInputtax relationship
$quote = quotationModel::where('quote_number', $quoteNumber)
    ->with(['checkfileInputtax'])
    ->first();

if (!$quote) {
    echo "Quotation $quoteNumber not found!\n";
    exit;
}

echo "Quotation $quoteNumber:\n";

// Check checkfileInputtax
if ($quote->checkfileInputtax) {
    echo "checkfileInputtax exists\n";
    echo "input_tax_file: " . ($quote->checkfileInputtax->input_tax_file ?: 'NULL or empty') . "\n";
} else {
    echo "No checkfileInputtax record\n";
}