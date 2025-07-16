<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebTourSyncController extends Controller
{
    // เรียกใช้ใน route /web-tour/sync
    public function syncNow(Request $request)
    {
        // ดึง config database ทั้งสองฝั่ง
        $sourceConfig = config('database.connections.mysql_web_tour');
        $targetConfig = config('database.connections.mysql');
        $sourceStatus = 'success';
        $sourceError = null;
        $targetStatus = 'success';
        $targetError = null;
        // ทดสอบการเชื่อมต่อฐานข้อมูลต้นทาง
        try {
            DB::connection('mysql_web_tour')->getPdo();
        } catch (\Exception $e) {
            $sourceStatus = 'error';
            $sourceError = $e->getMessage();
        }
        // ทดสอบการเชื่อมต่อฐานข้อมูลปลายทาง
        try {
            DB::connection('mysql')->getPdo();
        } catch (\Exception $e) {
            $targetStatus = 'error';
            $targetError = $e->getMessage();
        }
        // ดึงรายชื่อ table ทั้งหมดจาก source DB (WEB_TOUR)
        $tableList = [];
        try {
            $allTables = DB::connection('mysql_web_tour')->select('SHOW TABLES');
            $tableKey = 'Tables_in_' . ($sourceConfig['database'] ?? 'vdragon_next');
            $tableList = collect($allTables)->pluck($tableKey)->toArray();
        } catch (\Exception $e) {
            $tableList = [];
        }
        // รับ table ที่เลือกจากฟอร์ม (array) ถ้าไม่เลือกอะไร default เป็น tb_tour
        $selectedTables = $request->input('tables', ['tb_tour']);
        $results = [];
        if ($sourceStatus === 'success' && $targetStatus === 'success') {
            foreach ($selectedTables as $table) {
                $total = 0;
                $updated = 0;
                $inserted = 0;
                $status = 'success';
                $error = null;
                try {
                    $source = DB::connection('mysql_web_tour')->table($table)->get();
                    $total = $source->count();
                    foreach ($source as $row) {
                        $data = (array) $row;
                        $pk = array_key_exists('id', $data) ? 'id' : (array_keys($data)[0] ?? null);
                        if (!$pk) continue;
                        $id = $data[$pk];
                        $exists = DB::table($table)->where($pk, $id)->exists();
                        if ($exists) {
                            DB::table($table)->where($pk, $id)->update($data);
                            $updated++;
                        } else {
                            DB::table($table)->insert($data);
                            $inserted++;
                        }
                    }
                } catch (\Exception $e) {
                    $status = 'error';
                    $error = $e->getMessage();
                }
                // log
                DB::table('sync_logs')->insert([
                    'table_name' => $table,
                    'synced_at' => Carbon::now(),
                    'total_synced' => $total,
                    'total_updated' => $updated,
                    'total_inserted' => $inserted,
                    'status' => $status,
                    'error_message' => $error,
                ]);
                $results[] = compact('table', 'total', 'updated', 'inserted', 'status', 'error');
            }
        } else {
            $status = 'error';
            $error = $sourceError ?: $targetError;
            foreach ($selectedTables as $table) {
                $results[] = [
                    'table' => $table,
                    'total' => 0,
                    'updated' => 0,
                    'inserted' => 0,
                    'status' => 'error',
                    'error' => $error
                ];
            }
        }
        // log ล่าสุด
        $logs = DB::table('sync_logs')->orderByDesc('synced_at')->limit(10)->get();
        return view('webtour.sync', compact(
            'tableList', 'selectedTables', 'results', 'logs',
            'sourceConfig', 'targetConfig', 'sourceStatus', 'sourceError', 'targetStatus', 'targetError'
        ));
    }
}
