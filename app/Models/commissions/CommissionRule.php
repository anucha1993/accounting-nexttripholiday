<?php

namespace App\Models\commissions;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommissionRule extends Model
{
    use HasFactory;
     protected $fillable = ['commission_group_id','min_amount','max_amount','rate'];

    public function group()
    {
        return $this->belongsTo(CommissionGroup::class);
    }

    /**
     * Match commission rules for both step (per person) and percent (of profit)
     * @param float $profitPerPerson
     * @param float $profit
     * @return array
     */
    public static function matchBoth($profitPerPerson, $profit)
    {
        // กรณีไม่มี type ใน rule: สมมติว่า step = per person, percent = % ของ profit
        // ถ้ามี field type ใน rule สามารถ filter ได้
        $rules = self::all();
        $stepRule = null;
        $percentRule = null;

        // หา rule แบบ step (match จาก profitPerPerson)
        foreach ($rules as $rule) {
            if ($profitPerPerson >= $rule->min_amount && $profitPerPerson <= $rule->max_amount) {
                $stepRule = $rule;
                break;
            }
        }

        // หา rule แบบ percent (match จาก profit)
        foreach ($rules as $rule) {
            if ($profit >= $rule->min_amount && $profit <= $rule->max_amount) {
                $percentRule = $rule;
                break;
            }
        }

        $stepCommission = $stepRule ? $stepRule->rate : 0;
        $percentCommission = $percentRule ? ($percentRule->rate * $profit / 100) : 0;

        return [
            'step' => [
                'rule' => $stepRule ? true : false,
                'commission' => $stepCommission,
            ],
            'percent' => [
                'rule' => $percentRule ? true : false,
                'commission' => $percentCommission,
            ],
        ];
    }
}
