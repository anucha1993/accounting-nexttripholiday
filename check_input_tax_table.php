<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\inputTax\inputTaxModel;

// ดึงตัวอย่างข้อมูลแรกจากโมเดล
$sample = inputTaxModel::first();

// แสดงข้อมูลของโมเดล
echo "ข้อมูลของโมเดล inputTaxModel:\n";
if ($sample) {
    echo "- ID: {$sample->input_tax_id}\n";
    echo "- Quote ID: {$sample->input_tax_quote_id}\n";
    echo "- ชื่อตาราง: " . (new inputTaxModel)->getTable() . "\n";
    
    // ทดสอบการสร้าง query
    $tableName = (new inputTaxModel)->getTable();
    echo "\nทดสอบ query จากชื่อตาราง {$tableName}:\n";
    
    try {
        $count = \Illuminate\Support\Facades\DB::table($tableName)->count();
        echo "- จำนวนรายการในตาราง: {$count}\n";
        
        // ทดสอบค้นหาข้อมูลที่มี input_tax_file ไม่เป็นค่าว่าง
        $hasFileCount = \Illuminate\Support\Facades\DB::table($tableName)
            ->whereNotNull('input_tax_file')
            ->where('input_tax_file', '!=', '')
            ->count();
        echo "- จำนวนรายการที่มี input_tax_file ไม่ว่างเปล่า: {$hasFileCount}\n";
    } catch (\Exception $e) {
        echo "เกิดข้อผิดพลาด: " . $e->getMessage() . "\n";
    }
} else {
    echo "ไม่พบข้อมูลในตาราง\n";
}