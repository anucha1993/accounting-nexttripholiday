<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class SaleReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    protected $data;
    protected $mode;
    protected $campaignSource;

    public function __construct($data, $mode = 'all', $campaignSource = null)
    {
        $this->data = $data;
        $this->mode = $mode;
        $this->campaignSource = $campaignSource;
    }

    public function collection()
    {
        try {
            // สร้าง collection ใหม่ที่รวมข้อมูลปกติ
            $normalData = $this->data instanceof \Illuminate\Support\Collection ? $this->data : collect($this->data);

        // ตรวจสอบว่าข้อมูลเป็น object ที่มี method ที่ต้องการหรือไม่
        if ($normalData->isNotEmpty() && is_object($normalData->first())) {
            // คำนวณค่าต่างๆ สำหรับแถวสรุป
            $totalCommission = $normalData->sum(function($item) {
                if (!method_exists($item, 'getNetProfit') || !method_exists($item, 'getNetProfitPerPax')) {
                    return 0;
                }
                $commission = calculateCommission(
                    $this->mode === 'total' ? $item->getNetProfit() : $item->getNetProfitPerPax(),
                    $item->quote_sale,
                    $this->mode,
                    $item->quote_pax_total,
                    $item->quote_commission ?? 'Y'
                );
                return $commission['calculated'] ?? 0;
            });
        } else {
            $totalCommission = 0;
        }

        // สร้างแถวสรุป
        $summary = [
            'รวม',  // Quotes
            '',     // ช่วงเวลาเดินทาง
            '',     // โฮลเซลล์
            '',     // ชื่อลูกค้า
            '',     // ประเทศ
            '',     // แพคเกจทัวร์
            '',     // ที่มา
            '',     // เซลล์ผู้ขาย
            $normalData->sum('quote_pax_total'), // PAX
            number_format($normalData->sum(function($item) { 
                return is_object($item) ? ($item->quote_grand_total + $item->quote_discount) : 0;
            }), 2),  // ค่าบริการ
            number_format($normalData->sum(function($item) {
                return is_object($item) ? $item->quote_discount : 0;
            }), 2), // ส่วนลด
            number_format($normalData->sum(function($item) {
                return is_object($item) ? $item->quote_grand_total : 0;
            }), 2), // ยอดรวมสุทธิ
            number_format($normalData->sum(function($item) {
                return is_object($item) && method_exists($item, 'getWholesalePaidNet') ? $item->getWholesalePaidNet() : 0;
            }), 2),  // ยอดชำระโฮลเซลล์
            number_format($normalData->sum(function($item) {
                return is_object($item) && method_exists($item, 'getTotalOtherCost') ? $item->getTotalOtherCost() : 0;
            }), 2),  // ต้นทุนอื่นๆ
            number_format($normalData->sum(function($item) {
                return is_object($item) && method_exists($item, 'getTotalCostAll') ? $item->getTotalCostAll() : 0;
            }), 2),  // ต้นทุนรวม
            number_format($normalData->sum(function($item) {
                return is_object($item) && method_exists($item, 'getNetProfit') ? $item->getNetProfit() : 0;
            }), 2),  // กำไร
            number_format($normalData->sum(function($item) {
                return is_object($item) && method_exists($item, 'getNetProfitPerPax') ? $item->getNetProfitPerPax() : 0;
            }), 2),  // กำไรเฉลี่ย:คน
            number_format($totalCommission, 2),  // คอมมิชชั่นทั้งสิ้น
            ''      // CommissionGroup
        ];

            // สร้าง collection ใหม่ที่รวมข้อมูลปกติและแถวสรุป
            return $normalData->push($summary);
        } catch (\Exception $e) {
            // ถ้าเกิด error ให้ส่งค่าว่างกลับไป
            return collect([array_fill(0, 19, '')]);
        }
    }

    public function headings(): array
    {
        return [
            'Quotes',
            'ช่วงเวลาเดินทาง',
            'โฮลเซลล์',
            'ชื่อลูกค้า',
            'ประเทศ',
            'แพคเกจทัวร์ที่ซื้อ',
            'ที่มา',
            'เซลล์ผู้ขาย',
            'PAX',
            'ค่าบริการ',
            'ส่วนลด',
            'ยอดรวมสุทธิ',
            'ยอดชำระโฮลเซลล์',
            'ต้นทุนอื่นๆ',
            'ต้นทุนรวม',
            'กำไร',
            'กำไรเฉลี่ย:คน',
            'คอมมิชชั่นทั้งสิ้น',
            'CommissionGroup'
        ];
    }

    public function map($item): array
    {
        // ตรวจสอบว่าเป็นแถวสรุปหรือไม่
        if (is_array($item)) {
            return $item;
        }

        try {
            $commission = calculateCommission(
                $this->mode === 'total' ? $item->getNetProfit() : $item->getNetProfitPerPax(),
                $item->quote_sale,
                $this->mode,
                $item->quote_pax_total,
                $item->quote_commission ?? 'Y'
            );

            $sourceName = '';
            if (isset($item->customer->customer_campaign_source) && 
                !empty($item->customer->customer_campaign_source) &&
                $this->campaignSource) {
                $source = $this->campaignSource->firstWhere('id', $item->customer->customer_campaign_source);
                $sourceName = $source ? $source->campaign_source_name : '';
            }
        } catch (\Exception $e) {
            // ถ้าเกิด error ให้ส่งค่าว่างกลับไป
            return array_fill(0, 19, '');
        }

        return [
            $item->quote_number,
            date('d/m/Y', strtotime($item->quote_date_start)) . '-' . date('d/m/Y', strtotime($item->quote_date_end)),
            $item->quoteWholesale->code ?? '',
            $item->customer->customer_name ?? '',
            $item->quoteCountry->iso2 ?? '',
            $item->quote_tour_name ?? $item->quote_tour_name1 ?? '',
            $sourceName ?: 'none',
            $item->Salename->name ?? '',
            $item->quote_pax_total,
            number_format($item->quote_grand_total + $item->quote_discount, 2),
            number_format($item->quote_discount, 2),
            number_format($item->quote_grand_total, 2),
            number_format($item->getWholesalePaidNet(), 2),
            number_format($item->getTotalOtherCost(), 2),
            number_format($item->getTotalCostAll(), 2),
            number_format($item->getNetProfit(), 2),
            number_format($item->getNetProfitPerPax(), 2),
            number_format($commission['calculated'] ?? 0, 2),
            $item->quote_commission === 'N' ? 
                "ไม่จ่ายค่าคอมมิชชั่น : {$item->quote_note_commission}" :
                ($commission['group_name'] ?? 'ไม่ได้กำหนด')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // สไตล์สำหรับหัวตาราง
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4a6cf7']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]);

        $sheet->getStyle('A1:S' . ($this->data->count() + 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                ]
            ]
        ]);

        // สไตล์สำหรับแถวสรุป (แถวสุดท้าย)
        $lastRow = $this->data->count() + 2; // +2 เพราะมีหัวตารางและ index เริ่มที่ 1
        $sheet->getStyle("A{$lastRow}:S{$lastRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'CCCCCC']
            ]
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // Quotes
            'B' => 25, // ช่วงเวลาเดินทาง
            'C' => 15, // โฮลเซลล์
            'D' => 25, // ชื่อลูกค้า
            'E' => 10, // ประเทศ
            'F' => 30, // แพคเกจทัวร์
            'G' => 15, // ที่มา
            'H' => 20, // เซลล์ผู้ขาย
            'I' => 10, // PAX
            'J' => 15, // ค่าบริการ
            'K' => 15, // ส่วนลด
            'L' => 15, // ยอดรวมสุทธิ
            'M' => 20, // ยอดชำระโฮลเซลล์
            'N' => 15, // ต้นทุนอื่นๆ
            'O' => 15, // ต้นทุนรวม
            'P' => 15, // กำไร
            'Q' => 15, // กำไรเฉลี่ย:คน
            'R' => 20, // คอมมิชชั่นทั้งสิ้น
            'S' => 30, // CommissionGroup
        ];
    }
}
