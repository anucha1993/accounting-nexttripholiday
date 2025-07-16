<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SyncWebTour extends Command
{
    protected $signature = 'sync:webtour';
    protected $description = 'Sync WEB_TOUR table from mysql_web_tour to mysql (vdragon_next)';

    public function handle()
    {
        $this->info('เริ่มต้นการซิงค์ข้อมูล WEB_TOUR...');
        $updated = 0;
        $inserted = 0;
        $status = 'success';
        $error = null;
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
            $this->error($error);
            $total = 0;
        }
        // log
        DB::table('sync_logs')->insert([
            'table_name' => 'WEB_TOUR',
            'synced_at' => Carbon::now(),
            'total_synced' => $total ?? 0,
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
            $this->info("ซิงค์ข้อมูลเสร็จสิ้น: อัปเดต {$updated} รายการ, เพิ่มใหม่ {$inserted} รายการ, ทั้งหมด {$total} รายการ");
        }
    }
}
