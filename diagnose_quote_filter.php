<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\quotations\quotationModel;
use App\Services\QuotationFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Set specific quote number to diagnose
$quoteNumber = 'QT25080002';

// Get the quotation with all relevant relationships
$quote = quotationModel::where('quote_number', $quoteNumber)
    ->with([
        'quoteCheckStatus',
        'quoteLogStatus',
        'InputTaxVat',
        'checkfileInputtax',
        'quotePayments',
        'paymentWholesale',
        'quoteInvoice',
        'customer'
    ])
    ->first();

if (!$quote) {
    echo "Quotation $quoteNumber not found!\n";
    exit;
}

echo "=== Quotation Details ===\n";
echo "ID: {$quote->quote_id}\n";
echo "Number: {$quote->quote_number}\n";
echo "Status: {$quote->quote_status}\n";
echo "Grand Total: {$quote->quote_grand_total}\n\n";

// Check customer payments
echo "=== Customer Payments ===\n";
$totalPaid = 0;
$totalRefund = 0;
if ($quote->quotePayments && $quote->quotePayments->count() > 0) {
    foreach ($quote->quotePayments as $index => $payment) {
        $status = $payment->payment_status;
        $type = $payment->payment_type;
        $amount = $payment->payment_total;
        
        echo "Payment #{$index}: Status={$status}, Type={$type}, Amount={$amount}\n";
        
        if ($status != 'cancel') {
            if ($type == 'refund' && !empty($payment->payment_file_path)) {
                $totalRefund += $amount;
            } elseif ($type != 'refund') {
                $totalPaid += $amount;
            }
        }
    }
}
$customerPaidNet = $totalPaid - $totalRefund;
echo "Total Paid: {$totalPaid}\n";
echo "Total Refund: {$totalRefund}\n";
echo "Net Customer Paid: {$customerPaidNet}\n";
echo "Is Customer Paid Full? " . ($customerPaidNet >= $quote->quote_grand_total ? "YES" : "NO") . "\n\n";

// Check wholesale costs and payments
echo "=== Wholesale Costs & Payments ===\n";
$wholesaleCostTotal = 0;
if ($quote->InputTaxVat && $quote->InputTaxVat->count() > 0) {
    foreach ($quote->InputTaxVat as $index => $tax) {
        if (in_array($tax->input_tax_type, [2, 4, 5, 6, 7])) {
            $wholesaleCostTotal += $tax->input_tax_grand_total;
            echo "Wholesale Cost #{$index}: Type={$tax->input_tax_type}, Amount={$tax->input_tax_grand_total}\n";
        }
    }
}
echo "Total Wholesale Cost: {$wholesaleCostTotal}\n";

// Check wholesale payments
$wholesalePaid = 0;
$wholesaleRefund = 0;
if ($quote->paymentWholesale && $quote->paymentWholesale->count() > 0) {
    foreach ($quote->paymentWholesale as $index => $payment) {
        $fileName = $payment->payment_wholesale_file_name ?? '';
        $amount = $payment->payment_wholesale_total;
        $refundAmount = $payment->payment_wholesale_refund_total;
        $refundStatus = $payment->payment_wholesale_refund_status;
        
        echo "Wholesale Payment #{$index}: File=" . ($fileName ? "Yes" : "No") . ", Amount={$amount}\n";
        
        if (!empty($fileName)) {
            $wholesalePaid += $amount;
        }
        
        if ($refundStatus == 'success') {
            $wholesaleRefund += $refundAmount;
            echo "  Refund: Status={$refundStatus}, Amount={$refundAmount}\n";
        }
    }
}
$wholesalePaidNet = $wholesalePaid - $wholesaleRefund;
echo "Total Wholesale Paid: {$wholesalePaid}\n";
echo "Total Wholesale Refund: {$wholesaleRefund}\n";
echo "Net Wholesale Paid: {$wholesalePaidNet}\n";
echo "Is Wholesale Paid Full? " . (abs($wholesalePaidNet - $wholesaleCostTotal) < 0.01 ? "YES" : "NO") . "\n\n";

// Check status checklist
echo "=== Status Checks ===\n";
if ($quote->quoteCheckStatus) {
    echo "booking_email_status: " . ($quote->quoteCheckStatus->booking_email_status ?? 'NULL') . "\n";
    echo "quote_status: " . ($quote->quoteCheckStatus->quote_status ?? 'NULL') . "\n";
    echo "inv_status: " . ($quote->quoteCheckStatus->inv_status ?? 'NULL') . "\n";
    echo "depositslip_status: " . ($quote->quoteCheckStatus->depositslip_status ?? 'NULL') . "\n";
    echo "fullslip_status: " . ($quote->quoteCheckStatus->fullslip_status ?? 'NULL') . "\n";
    echo "passport_status: " . ($quote->quoteCheckStatus->passport_status ?? 'NULL') . "\n";
    echo "appointment_status: " . ($quote->quoteCheckStatus->appointment_status ?? 'NULL') . "\n";
    echo "wholesale_skip_status: " . ($quote->quoteCheckStatus->wholesale_skip_status ?? 'NULL') . "\n";
    echo "withholding_tax_status: " . ($quote->quoteCheckStatus->withholding_tax_status ?? 'NULL') . "\n";
    echo "wholesale_tax_status: " . ($quote->quoteCheckStatus->wholesale_tax_status ?? 'NULL') . "\n";
} else {
    echo "No quoteCheckStatus found\n";
}

// Check tax documents
echo "\n=== Tax Documents ===\n";
if ($quote->quoteLogStatus) {
    echo "input_tax_status: " . ($quote->quoteLogStatus->input_tax_status ?? 'NULL') . "\n";
    echo "input_tax_withholding_status: " . ($quote->quoteLogStatus->input_tax_withholding_status ?? 'NULL') . "\n";
} else {
    echo "No quoteLogStatus found\n";
}

// Check for input tax file
echo "\ncheckfileInputtax: ";
if ($quote->checkfileInputtax) {
    echo "Found\n";
    echo "input_tax_file: " . ($quote->checkfileInputtax->input_tax_file ?: 'NULL or empty') . "\n";
} else {
    echo "Not found\n";
}

// Run filter conditions manually
echo "\n=== Testing Filter Conditions ===\n";

// 1. Check if status badges are complete
if (function_exists('getStatusBadgeCount') && $quote->quoteCheckStatus) {
    $badgeCount = getStatusBadgeCount($quote->quoteCheckStatus, $quote);
    echo "1. Status Badge Count: {$badgeCount} (Should be 0 to pass)\n";
    if ($badgeCount > 0) {
        echo "   FAILED: Quote has incomplete status items\n";
    }
}

// 2. Check if waiting for tax documents
if (function_exists('isWaitingForTaxDocuments')) {
    $waitingForTax = isWaitingForTaxDocuments($quote->quoteLogStatus, $quote);
    echo "2. Waiting For Tax Documents: " . ($waitingForTax ? "YES" : "NO") . " (Should be NO to pass)\n";
    if ($waitingForTax) {
        echo "   FAILED: Quote is waiting for tax documents\n";
    }
}

// 3. Check if customer has paid in full
echo "3. Customer Paid Full: " . ($customerPaidNet >= $quote->quote_grand_total ? "YES" : "NO") . " (Should be YES to pass)\n";
if ($customerPaidNet < $quote->quote_grand_total) {
    echo "   FAILED: Customer hasn't paid in full\n";
}

// 4. Check if wholesale is paid (if there are wholesale costs)
if ($wholesaleCostTotal > 0) {
    $wholesalePaymentCount = $quote->paymentWholesale ? 
        $quote->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                              ->where('payment_wholesale_file_name', '!=', null)
                              ->count() : 0;
                              
    echo "4a. Wholesale Payment Count: {$wholesalePaymentCount}\n";
    echo "4b. Wholesale Cost Total: {$wholesaleCostTotal}\n";
    echo "4c. Wholesale Paid Net: {$wholesalePaidNet}\n";
    
    if ($wholesalePaymentCount > 0 && $wholesaleCostTotal > 0) {
        $isWholesalePaidFull = abs($wholesalePaidNet - $wholesaleCostTotal) < 0.01;
        echo "4. Wholesale Paid Full: " . ($isWholesalePaidFull ? "YES" : "NO") . " (Should be YES to pass)\n";
        if (!$isWholesalePaidFull) {
            echo "   FAILED: Wholesale hasn't been paid in full\n";
        }
    }
} else {
    echo "4. No wholesale costs - skipping wholesale payment check\n";
}

// Check for input_tax_file
echo "\n5. Has input_tax_file: " . (!empty($quote->checkfileInputtax) && !empty($quote->checkfileInputtax->input_tax_file) ? "YES" : "NO") . " (Should be YES if has wholesale costs)\n";

// Additional debugging information
echo "\n=== DEBUG INFO ===\n";
if (!empty($quote->InputTaxVat)) {
    foreach ($quote->InputTaxVat as $index => $tax) {
        echo "InputTaxVat #{$index}:\n";
        echo "  - ID: {$tax->input_tax_id}\n";
        echo "  - Type: {$tax->input_tax_type}\n";
        echo "  - Status: " . ($tax->input_tax_status ?? 'NULL') . "\n";
        echo "  - File: " . ($tax->input_tax_file ?: 'NULL or empty') . "\n";
        echo "  - Grand Total: {$tax->input_tax_grand_total}\n";
    }
}

// Test the filter service directly
echo "\n=== Testing QuotationFilterService ===\n";
try {
    $request = new Request();
    $testCollection = collect([$quote]);
    $filterService = QuotationFilterService::class;
    
    // Create an array with just this quote
    $filteredCollection = $testCollection->filter(function($item) {
        try {
            // Customer paid check
            $customerPaid = ($item->quotePayments ? $item->quotePayments->where('payment_status', '!=', 'cancel')
                                                ->where('payment_type', '!=', 'refund')
                                                ->sum('payment_total') : 0)
                        - ($item->quotePayments ? $item->quotePayments->where('payment_status', '!=', 'cancel')
                                               ->where('payment_type', '=', 'refund')
                                               ->whereNotNull('payment_file_path')
                                               ->sum('payment_total') : 0);
            $grandTotal = $item->quote_grand_total ?? 0;
            
            if ($customerPaid < $grandTotal) {
                echo "FILTER: Failed customer paid check\n";
                return false;
            }
            
            // Status badge check
            if (function_exists('getStatusBadgeCount') && $item->quoteCheckStatus) {
                $statusCount = getStatusBadgeCount($item->quoteCheckStatus, $item);
                if ($statusCount > 0) {
                    echo "FILTER: Failed status badge count ({$statusCount})\n";
                    return false;
                }
            }
            
            // Tax documents check
            if (function_exists('isWaitingForTaxDocuments')) {
                $waitingForTax = isWaitingForTaxDocuments($item->quoteLogStatus, $item);
                if ($waitingForTax) {
                    echo "FILTER: Failed tax documents check\n";
                    return false;
                }
            }
            
            // Wholesale payment check
            $inputtaxTotal = $item->InputTaxVat ? $item->InputTaxVat->whereIn('input_tax_type', [2, 4, 5, 6, 7])
                                               ->sum('input_tax_grand_total') : 0;
            $countPayment = $item->paymentWholesale ? $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                                  ->where('payment_wholesale_file_name', '!=', null)
                                                  ->count() : 0;
            $wholesalePaid = $item->paymentWholesale ? $item->paymentWholesale->where('payment_wholesale_file_name', '!=', '')
                                                  ->where('payment_wholesale_file_name', '!=', null)
                                                  ->sum('payment_wholesale_total') : 0;
            $wholesaleRefund = $item->paymentWholesale ? $item->paymentWholesale->where('payment_wholesale_refund_status', '=', 'success')
                                                    ->sum('payment_wholesale_refund_total') : 0;
            $wholesalePaidNet = $wholesalePaid - $wholesaleRefund;
            
            if ($countPayment > 0 && $inputtaxTotal > 0) {
                $isWholesalePaidFull = abs($wholesalePaidNet - $inputtaxTotal) < 0.01;
                if (!$isWholesalePaidFull) {
                    echo "FILTER: Failed wholesale payment check (Net paid: {$wholesalePaidNet}, Cost: {$inputtaxTotal})\n";
                    return false;
                }
            } elseif ($inputtaxTotal == 0 && $customerPaid > 0) {
                echo "FILTER: No wholesale costs, customer paid - should pass\n";
                return true;
            } else {
                $isWholesalePaidFull = abs($inputtaxTotal - $wholesalePaidNet) < 0.01;
                if (!$isWholesalePaidFull) {
                    echo "FILTER: Failed general payment check\n";
                    return false;
                }
            }
            
            echo "FILTER: All checks passed - should be included in sales report\n";
            return true;
            
        } catch (\Exception $e) {
            echo "FILTER ERROR: " . $e->getMessage() . "\n";
            return false;
        }
    });
    
    echo "Quote should be " . ($filteredCollection->count() > 0 ? "INCLUDED" : "EXCLUDED") . " in the sales report\n";
    
} catch (\Exception $e) {
    echo "Error testing filter: " . $e->getMessage() . "\n";
}