<?php


use App\Models\commissions\commissionGroupModel;

if (!function_exists('calculateCommission')) {

    function calculateCommission(float $profit, int $saleId): array
{
    $group = \App\Models\commissions\commissionGroupModel::whereJsonContains('sale_ids', (string) $saleId)->first();

    if (!$group) return ['amount' => 0, 'group_name' => null];

    $group->load(['commissionLists' => function ($q) {
        $q->orderBy('min_amount');
    }]);

    foreach ($group->commissionLists as $rate) {
        $min = (float) $rate->min_amount;
        $max = (float) $rate->max_amount;

        if ($profit >= $min && $profit <= $max) {
            return [
                'amount' => (float) $rate->commission_calculate,
                'group_name' => $group->name,
            ];
        }
    }

    return ['amount' => 0, 'group_name' => $group->name];
}
    

}
