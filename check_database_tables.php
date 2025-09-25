<?php
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking all tables in database\n";
$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
echo "Total tables: " . count($tables) . "\n";

// แสดงชื่อตารางทั้งหมด
foreach ($tables as $table) {
    foreach ($table as $key => $name) {
        echo "Table: $name\n";
    }
}

// ค้นหาตารางที่มีชื่อเกี่ยวกับ input และ tax
echo "\nSearching for input/tax related tables:\n";
foreach ($tables as $table) {
    foreach ($table as $key => $name) {
        if (strpos($name, 'input') !== false || strpos($name, 'tax') !== false) {
            echo "Found: $name\n";
            
            // นับจำนวนเรคอร์ดในตาราง
            $count = \Illuminate\Support\Facades\DB::table($name)->count();
            echo "  Records: $count\n";
        }
    }
}

// ค้นหาตารางที่มีคอลัมน์เกี่ยวกับ input_tax
echo "\nSearching for tables with input_tax columns:\n";
foreach ($tables as $table) {
    foreach ($table as $key => $name) {
        try {
            $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM `$name`");
            $found = false;
            foreach ($columns as $column) {
                if (strpos($column->Field, 'input_tax') !== false) {
                    if (!$found) {
                        echo "Table: $name\n";
                        $found = true;
                    }
                    echo "  Column: {$column->Field}, Type: {$column->Type}\n";
                }
            }
        } catch (\Exception $e) {
            // Skip if error
        }
    }
}