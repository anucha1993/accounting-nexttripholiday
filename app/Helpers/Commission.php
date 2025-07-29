<?php
use Illuminate\Support\Facades\Log;
use App\Models\commissions\commissionGroupModel;


if (!function_exists('calculateCommission')) {
    /**
     * คำนวณค่าคอมมิชชั่นตามกลุ่มและช่วงกำไร
     * @param float $profit
     * @param int|string $saleId
     * @param string $mode
     * @param int $people
     * @param string $noCommission
     * @return array
     */
    function calculateCommission(float $profit, $saleId, string $mode = 'qt', int $people = 1, string $noCommission = 'N'): array
    {
        // รองรับทั้ง int และ string, และตรวจสอบชนิดข้อมูลให้ตรงกับฐานข้อมูล (string)
        $saleIdStr = (string) $saleId;
        $group = \App\Models\commissions\commissionGroupModel::whereJsonContains('sale_ids', $saleIdStr)->first();

        if (!$group) {
            // log ทั้ง saleId และ sale_ids ของทุก group เพื่อ debug
            $allGroups = \App\Models\commissions\commissionGroupModel::pluck('sale_ids', 'id')->toArray();
            // Log::debug('Commission group not found', [
            //     'saleId' => $saleIdStr,
            //     'allGroupSaleIds' => $allGroups
            // ]);
            return [
                'amount' => 0,
                'group_name' => null,
                'percent' => 0,
                'calculated' => 0,
                'type' => 'unknown'
            ];
        }

        // ถ้าเลือกไม่จ่ายค่าคอมมิชชั่น (N) return 0 ทันที แต่ให้ group_name ด้วย
        if ($noCommission === 'N') {
            return [
                'amount' => 0,
                'group_name' => $group->name,
                'percent' => 0,
                'calculated' => 0,
                'type' => 'no-commission'
            ];
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

            // กรองเฉพาะ type ที่ตรงกับ mode
            if ($mode === 'qt' && !in_array($type, ['step-QT', 'percent-QT'])) {
                continue;
            }
            if ($mode === 'total' && !in_array($type, ['step-Total', 'percent-Total'])) {
                continue;
            }

            if ($profit >= $min && $profit <= $max) {
                $baseAmount = (float) $rate->commission_calculate;
                $groupName = $group->name;

                // step
                if (str_starts_with($type, 'step')) {
                    return [
                        'amount' => $baseAmount,
                        'calculated' => $mode === 'qt' ? $baseAmount * $people : $baseAmount,
                        'group_name' => $groupName,
                        'type' => $type,
                        'percent' => 0,
                    ];
                }

                // percent
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

        // ถ้าเข้า group แต่ไม่เข้าเงื่อนไขช่วง
        return [
            'amount' => 0,
            'group_name' => $group->name,
            'percent' => 0,
            'calculated' => 0,
            'type' => 'not-matched'
        ];
    }
}

