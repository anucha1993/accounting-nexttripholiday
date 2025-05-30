<?php

use App\Models\commissions\commissionGroupModel;

if (!function_exists('calculateCommission')) {
    function calculateCommission(float $profit, int $saleId, string $mode = 'qt', int $people = 1): array
    {
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

            if ($profit >= $min && $profit <= $max) {
                $baseAmount = (float) $rate->commission_calculate;
                $groupName = $group->name;
                $type = $group->type;

                // ✅ หาจำนวนค่าคอมจริงที่ต้องจ่าย
                if ($mode === 'qt' || $mode === 'total') {
                    if ($type === 'step-QT' || $type === 'step-Total') {
                        return [
                            'amount' => $baseAmount,
                            'calculated' => $baseAmount * $people,
                            'group_name' => $groupName,
                            'type' => $group?->type ?? 'unknown',
                            'percent' => 0,
                        ];
                    } elseif ($type === 'percent-QT' || $type === 'percent-Total') {
                        return [
                            'amount' => $baseAmount, // อัตราคอมมิชชั่น เช่น 10%
                            'calculated' => ($profit * $baseAmount) / 100, // จำนวนเงินที่ผู้ขายจะได้รับจริง
                            'group_name' => $groupName,
                            'type' => $group->type . '-' . $mode, // เช่น 'percent-QT', 'step-QT', 'percent-Total'
                            'base_amount' => $profit, // เพื่อความชัดเจนว่าคูณกับอะไร
                        ];
                    }
                } else {
                    // กรณี total ใช้ amount ตรง ๆ
                    return [
                        'amount' => $baseAmount,
                        'calculated' => $baseAmount,
                        'group_name' => $groupName,
                        'percent' => $type === 'percent' ? $baseAmount : 0,
                    ];
                }
            }
        }

        return ['amount' => 0, 'group_name' => $group->name, 'percent' => 0, 'calculated' => 0];
    }
}
