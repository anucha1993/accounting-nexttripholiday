<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncWebTour extends Command
{
         protected $signature = 'sync:webtour {table}';
        protected $description = 'Sync WEB_TOUR table from mysql_web_tour to mysql';


    public function handle()
    {
        $this->info('เริ่มต้นการซิงค์ข้อมูล WEB_TOUR...');
        $updated = 0;
        $inserted = 0;
        $status = 'success';
        $error = null;
        $this->info('เริ่มต้นการซิงค์ข้อมูล...');
        $tableArg = $this->argument('table');
        $sourceConfig = config('database.connections.mysql_web_tour');
        $targetConfig = config('database.connections.mysql2');
        $tableList = [];
        try {
            $allTables = DB::connection('mysql_web_tour')->select('SHOW TABLES');
            $tableKey = 'Tables_in_' . ($sourceConfig['database'] ?? 'vdragon_next');
            $tableList = collect($allTables)->pluck($tableKey)->toArray();
        } catch (\Exception $e) {
            $this->error('ไม่สามารถดึงรายชื่อ table ได้: ' . $e->getMessage());
            return 1;
        }
        $selectedTables = [];
        if ($tableArg) {
            $selectedTables = [$tableArg];
        } else {
            // default table
            $selectedTables = ['tb_tour','tb_booking_form','tb_country','tb_travel_type','tb_wholesale','users','tb_tour_period'];
        }
        foreach ($selectedTables as $table) {
            $total = 0;
            $updated = 0;
            $inserted = 0;
            $status = 'success';
            $error = null;
            try {
                if ($table === 'tb_tour_period') {
                    // sync เฉพาะ 2000 row ล่าสุด (id มากสุด)
                    DB::connection('mysql_web_tour')->table($table)
                        ->orderByDesc('id')
                        ->limit(2000)
                        ->orderBy('id')
                        ->chunk(1000, function ($rows) use (&$total, &$updated, &$inserted, $table) {
                            foreach ($rows as $row) {
                                $data = (array) $row;
                                $pk = array_key_exists('id', $data) ? 'id' : (array_keys($data)[0] ?? null);
                                if (!$pk) continue;
                                $id = $data[$pk];
                                try {
                                    $exists = DB::connection('mysql2')->table($table)->where($pk, $id)->exists();
                                    if ($exists) {
                                        DB::connection('mysql2')->table($table)->where($pk, $id)->update($data);
                                        $updated++;
                                    } else {
                                        DB::connection('mysql2')->table($table)->insert($data);
                                        $inserted++;
                                    }
                                    $total++;
                                } catch (\Exception $ex) {
                                    \Log::error("[SYNC-ROW-ERROR] table: $table, id: $id, error: " . $ex->getMessage(), ['data' => $data]);
                                    continue;
                                }
                            }
                            return true;
                        });
                } else {
                    DB::connection('mysql_web_tour')->table($table)->orderBy('id')->chunk(1000, function ($rows) use (&$total, &$updated, &$inserted, $table) {
                        foreach ($rows as $row) {
                            $data = (array) $row;
                            $pk = array_key_exists('id', $data) ? 'id' : (array_keys($data)[0] ?? null);
                            if (!$pk) continue;
                            $id = $data[$pk];
                            try {
                                $exists = DB::connection('mysql2')->table($table)->where($pk, $id)->exists();
                                if ($exists) {
                                    DB::connection('mysql2')->table($table)->where($pk, $id)->update($data);
                                    $updated++;
                                } else {
                                    DB::connection('mysql2')->table($table)->insert($data);
                                    $inserted++;
                                }
                                $total++;
                            } catch (\Exception $ex) {
                                \Log::error("[SYNC-ROW-ERROR] table: $table, id: $id, error: " . $ex->getMessage(), ['data' => $data]);
                                continue;
                            }
                        }
                        return true;
                    });
                }
            } catch (\Exception $e) {
                $status = 'error';
                $error = $e->getMessage();
                \Log::error("[SYNC-TABLE-ERROR] table: $table, error: " . $e->getMessage());
            }
            // log
            DB::connection('mysql')->table('sync_logs')->insert([
                'table_name' => $table,
                'synced_at' => Carbon::now(),
                'total_synced' => $total,
                'total_updated' => $updated,
                'total_inserted' => $inserted,
                'status' => $status,
                'error_message' => $error,
            ]);
            if ($status === 'error') {
                $this->info("\n====================\n");
                $this->info("\033[31mเกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูลต้นทางหรือปลายทาง\033[0m");
                $this->info($error);
                $this->info("\n====================\n");
            } else {
                $this->info("ซิงค์ข้อมูล {$table} เสร็จสิ้น: อัปเดต {$updated} รายการ, เพิ่มใหม่ {$inserted} รายการ, ทั้งหมด {$total} รายการ");
            }
        }
    }
}
