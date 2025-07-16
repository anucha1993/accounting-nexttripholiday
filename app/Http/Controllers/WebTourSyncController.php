<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class WebTourSyncController extends Controller
{
    // เรียกใช้ใน route /web-tour/sync
    public function syncNow()
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
        $total = 0;
        $updated = 0;
        $inserted = 0;
        $status = 'success';
        $error = null;
        if ($sourceStatus === 'success' && $targetStatus === 'success') {
            try {
                $source = DB::connection('mysql_web_tour')->table('WEB_TOUR')->get();
                $total = $source->count();
                foreach ($source as $row) {
                    $data = (array) $row;
                    $id = $data['id'];
                    $exists = DB::table('WEB_TOUR')->where('id', $id)->exists();
                    if ($exists) {
                        DB::table('WEB_TOUR')->where('id', $id)->update($data);
                        $updated++;
                    } else {
                        DB::table('WEB_TOUR')->insert($data);
                        $inserted++;
                    }
                }
            } catch (\Exception $e) {
                $status = 'error';
                $error = $e->getMessage();
            }
        } else {
            $status = 'error';
            $error = $sourceError ?: $targetError;
        }
        // log
        DB::table('sync_logs')->insert([
            'table_name' => 'WEB_TOUR',
            'synced_at' => Carbon::now(),
            'total_synced' => $total,
            'total_updated' => $updated,
            'total_inserted' => $inserted,
            'status' => $status,
            'error_message' => $error,
        ]);
        // ดึง log ล่าสุด 5 รายการ
        $logs = DB::table('sync_logs')->orderByDesc('synced_at')->limit(5)->get();
        return view('webtour.sync', compact(
            'total', 'updated', 'inserted', 'status', 'error',
            'sourceConfig', 'targetConfig', 'sourceStatus', 'sourceError', 'targetStatus', 'targetError', 'logs'
        ));
    }
}
