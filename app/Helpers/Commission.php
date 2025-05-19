<?php


use App\Models\commissions\commissionGroupModel;

if (!function_exists('calculateCommission')) {
    function calculateCommission(float $profit, int $saleId): float
    {
        $group = commissionGroupModel::whereJsonContains('sale_ids', (string) $saleId)->first();

        if (!$group) {
            // Log::warning("ไม่มี Commission Group สำหรับ Sale ID: $saleId");
            return 0;
        }

        $group->load(['commissionLists' => function ($q) {
            $q->orderBy('min_amount');
        }]);

        foreach ($group->commissionLists as $rate) {
            $min = (float) $rate->min_amount;
            $max = (float) $rate->max_amount;
            $calculate = (float) $rate->commission_calculate;

            // \Log::info("คำนวณคอม", compact('profit', 'min', 'max', 'calculate'));

            if ($profit >= $min && $profit <= $max) {
                return $calculate;
            }
        }

        return 0;
    }
}
