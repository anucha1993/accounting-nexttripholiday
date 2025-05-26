<?php

use App\Models\commissions\commissionGroupModel;

if (!function_exists('calculateCommission')) {
function calculateCommission(float $profit, int $saleId): array
{
    $group = \App\Models\commissions\commissionGroupModel::whereJsonContains('sale_ids', (string) $saleId)->first();

    if (!$group) return ['amount' => 0, 'group_name' => null, 'percent' => 0];

    $group->load(['commissionLists' => function ($q) {
        $q->orderBy('min_amount');
    }]);

    foreach ($group->commissionLists as $rate) {
        $min = (float) $rate->min_amount;
        $max = (float) $rate->max_amount;

        if ($profit >= $min && $profit <= $max) {
            if ($group->type === 'percent') {
                $percent = (float) $rate->commission_calculate;
                return [
                    'amount' => $profit * $percent / 100,
                    'group_name' => $group->name,
                    'percent' => $percent
                ];
            } else {
                return [
                    'amount' => (float) $rate->commission_calculate,
                    'group_name' => $group->name,
                    'percent' => 0
                ];
            }
        }
    }

    return ['amount' => 0, 'group_name' => $group->name, 'percent' => 0];
}


}
