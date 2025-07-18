<?php

use App\Models\commissions\commissionGroupModel;


if (!function_exists('calculateCommission')) {
    function calculateCommission(float $profit, int $saleId, string $mode = 'qt', int $people = 1, string $noCommission = 'N'): array
    {
        // ถ้าเลือกไม่จ่ายค่าคอมมิชชั่น (Y) return 0 ทันที
        if ($noCommission === 'Y') {
            return ['amount' => 0, 'group_name' => null, 'percent' => 0, 'calculated' => 0, 'type' => 'no-commission'];
        }
        $group = \App\Models\commissions\commissionGroupModel::whereJsonContains('sale_ids', (string) $saleId)->first();

        if (!$group) {
            return ['amount' => 0, 'group_name' => null, 'percent' => 0, 'calculated' => 0, 'type' => 'unknown'];
        }

        $group->load([
            'commissionLists' => function ($q) {
                $q->orderBy('min_amount');
            },
        ]);

        foreach ($group->commissionLists as $rate) {
            $min = (float) $rate->min_amount;
            $max = (float) $rate->max_amount;
            $type = $group->type;

            // ✅ กรองเฉพาะ type ที่ตรงกับ mode
            if ($mode === 'qt' && !in_array($type, ['step-QT', 'percent-QT'])) {
                continue;
            }
            if ($mode === 'total' && !in_array($type, ['step-Total', 'percent-Total'])) {
                continue;
            }

            if ($profit >= $min && $profit <= $max) {
                $baseAmount = (float) $rate->commission_calculate;
                $groupName = $group->name;

                // ✅ step
                if (str_starts_with($type, 'step')) {
                    return [
                        'amount' => $baseAmount,
                        'calculated' => $mode === 'qt' ? $baseAmount * $people : $baseAmount,
                        'group_name' => $groupName,
                        'type' => $type,
                        'percent' => 0,
                    ];
                }

                // ✅ percent
                if (str_starts_with($type, 'percent')) {
                    return [
                        'amount' => $baseAmount,
                        'calculated' => $mode === 'qt'
                            ? ($profit * $baseAmount) / 100 * $people
                            : ($profit * $baseAmount) / 100,
                        'group_name' => $groupName,
                        'type' => $type,
                        'percent' => $baseAmount,
                        'base_amount' => $profit,
                    ];
                }
            }
        }

        return ['amount' => 0, 'group_name' => $group->name, 'percent' => 0, 'calculated' => 0, 'type' => 'not-matched'];
    }
}

