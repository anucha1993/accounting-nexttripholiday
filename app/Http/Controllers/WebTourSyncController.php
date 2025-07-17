<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WebTourSyncController extends Controller
{
    // เรียกใช้ใน route /web-tour/sync
    public function syncNow(Request $request)
    {
        // ดึง config database ทั้งสองฝั่ง
        $sourceConfig = config('database.connections.mysql_web_tour');
        $targetConfig = config('database.connections.mysql2');
        $targetLog = config('database.connections.mysql');
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
        // รองรับทั้งแบบ sync ทีละ table (GET: ?table=xxx) และ sync หลาย table (POST/GET: ?tables[]=xxx)
        $selectedTables = [];
        $results = [];
        if ($request->has('table')) {
            // sync ทีละ table (GET)
            $selectedTables = [$request->input('table')];
        } elseif ($request->has('tables')) {
            // sync หลาย table (POST หรือ GET)
            $selectedTables = $request->input('tables', []);
        } else {
            // default table
             $selectedTables = $request->input('tables', ['tb_tour','tb_booking_form','tb_country','tb_travel_type','tb_wholesale','users','tb_tour_period']);
        }

        if ($request->isMethod('post') || $request->has('table') || $request->has('tables')) {
            if ($sourceStatus === 'success' && $targetStatus === 'success') {
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
                                ->orderBy('id') // เพื่อให้ chunk เรียง id น้อยไปมาก
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
                                            Log::error("[SYNC-ROW-ERROR] table: $table, id: $id, error: " . $ex->getMessage(), ['data' => $data]);
                                            continue;
                                        }
                                    }
                                    return true;
                                });
                        } else {
                            // table อื่น sync ทั้งหมด
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
                                        Log::error("[SYNC-ROW-ERROR] table: $table, id: $id, error: " . $ex->getMessage(), ['data' => $data]);
                                        continue;
                                    }
                                }
                                return true;
                            });
                        }
                    } catch (\Exception $e) {
                        $status = 'error';
                        $error = $e->getMessage();
                        Log::error("[SYNC-TABLE-ERROR] table: $table, error: " . $e->getMessage());
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
        }
        // log ล่าสุด
        $logs = DB::connection('mysql')->table('sync_logs')->orderByDesc('synced_at')->limit(10)->get();
        return view('webtour.sync', compact(
            'tableList', 'selectedTables', 'results', 'logs',
            'sourceConfig', 'targetConfig', 'sourceStatus', 'sourceError', 'targetStatus', 'targetError'
        ));
    }
}
