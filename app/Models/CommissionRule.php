<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    use HasFactory;
    protected $fillable = [
        'type','min_profit','max_profit','value','unit','status',
    ];

     /**  คืน Rule ที่ตรงกับตัวเลขที่ส่งมา  */
     public static function match(float $amount, string $type = 'step'): ?self
     {
         return self::query()
             ->whereIn('status', ['active', 'Enable'])   // << แก้ตรงนี้
             ->where('type', $type)
             ->where('min_profit', '<=', $amount)
             ->where(function ($q) use ($amount) {
                 $q->whereNull('max_profit')
                   ->orWhere('max_profit', '>=', $amount);
             })
             ->orderBy('min_profit')
             ->first();
     }
     /**  คำนวณเงินคอมฯ ตาม Rule */
     public static function commission(float $amount, string $type = 'step'): float
     {
         $rule = self::match($amount, $type);
 
         if (!$rule) {
             return 0;                         // ไม่ตรงช่วงไหนเลย
         }
 
         return $rule->unit === 'baht'
             ? $rule->value                    // บาท/คน
             : ($amount * ($rule->value / 100)); // % ของยอดรวม
     }


    //   /**
    //  * คืน array ['step' => CommissionRule|null, 'percent' => CommissionRule|null]
    //  *
    //  * @param  float  $profitPerPerson   กำไร/คน  (ใช้หา step)
    //  * @param  float  $totalProfit       กำไรรวม  (ใช้หา percent)
    //  */
    // public static function matchBoth(float $profitPerPerson, float $totalProfit): array
    // {
    //     $rules = self::query()
    //         ->whereIn('status', ['active', 'Enable'])   // เปิดใช้งาน
    //         ->where(function ($q) use ($profitPerPerson, $totalProfit) {
    //             // เงื่อนไขของ step
    //             $q->where(function ($q2) use ($profitPerPerson) {
    //                 $q2->where('type', 'step')
    //                    ->where('min_profit', '<=', $profitPerPerson)
    //                    ->where(function ($qq) use ($profitPerPerson) {
    //                        $qq->whereNull('max_profit')
    //                           ->orWhere('max_profit', '>=', $profitPerPerson);
    //                    });
    //             })
    //             // หรือเงื่อนไขของ percent
    //             ->orWhere(function ($q2) use ($totalProfit) {
    //                 $q2->where('type', 'percent')
    //                    ->where('min_profit', '<=', $totalProfit)
    //                    ->where(function ($qq) use ($totalProfit) {
    //                        $qq->whereNull('max_profit')
    //                           ->orWhere('max_profit', '>=', $totalProfit);
    //                    });
    //             });
    //         })
    //         ->get()
    //         ->keyBy('type');   // ผลลัพธ์จะแยกตาม key 'step' / 'percent'

    //     return [
    //         'step'    => $rules->get('step'),
    //         'percent' => $rules->get('percent'),
    //     ];
    // }

     /* ---------- scope เล็ก ๆ ไว้ใช้ซ้ำ ---------- */
     public function scopeActive($q)
     {
         return $q->whereIn('status', ['active', 'Enable']);
     }
 
     /* ---------- จับ Rule & คำนวณให้ทั้งสองสูตร ---------- */
     public static function matchBoth(float $profitPerPerson, float $totalProfit): array
     {
         /* ===== 1) หา Rule แบบ step ===== */
         $stepRule = self::active()
             ->where('type', 'step')
             ->where('min_profit', '<=', $profitPerPerson)
             ->where(function ($q) use ($profitPerPerson) {
                 $q->whereNull('max_profit')
                   ->orWhere('max_profit', '>=', $profitPerPerson);
             })
             ->orderByDesc('min_profit')        // เผื่อทับกัน เอาช่วงแคบที่สุด
             ->first();
 
         /* ===== 2) หา Rule แบบ percent ===== */
         $percentRule = self::active()
             ->where('type', 'percent')
             ->where('min_profit', '<=', $totalProfit)
             ->where(function ($q) use ($totalProfit) {
                 $q->whereNull('max_profit')
                   ->orWhere('max_profit', '>=', $totalProfit);
             })
             ->orderByDesc('min_profit')
             ->first();
 
         /* ===== 3) คำนวณจำนวนเงินคอมฯ ===== */
         $stepAmount    = $stepRule    ? $stepRule->value                                 : 0;                    // บาท/คน
         $percentAmount = $percentRule ? $totalProfit * ($percentRule->value / 100)       : 0;                    // % ของยอดรวม
 
         return [
             'step' => [
                 'rule'       => $stepRule,
                 'commission' => $stepAmount,
             ],
             'percent' => [
                 'rule'       => $percentRule,
                 'commission' => $percentAmount,
             ],
         ];
     }


   
}

