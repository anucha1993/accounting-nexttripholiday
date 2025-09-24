<?php

namespace App\Exports;

use App\Models\withholding\WithholdingTaxDocument;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class WithholdingTaxExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    private $documentsArray;
    private $documents;
    private $num = 0;
    private $filters;

    public function __construct($documentsArray = null, $filters = [])
    {
        $this->documentsArray = $documentsArray;
        $this->filters = $filters;
    }

    public function collection()
    {
        if ($this->documentsArray && is_array($this->documentsArray) && count($this->documentsArray) > 0) {
            // Export selected documents
            $this->documents = WithholdingTaxDocument::with(['customer', 'wholesale', 'quote'])
                ->whereIn('id', $this->documentsArray)
                ->orderBy('document_doc_date', 'desc')
                ->orderBy('document_number', 'desc')
                ->get();
        } else {
            // Export all documents with filters
            $query = WithholdingTaxDocument::with(['customer', 'wholesale', 'quote']);
            
            // Apply filters
            if (!empty($this->filters['document_number'])) {
                $query->where('document_number', 'LIKE', '%' . trim($this->filters['document_number']) . '%');
            }
            
            if (!empty($this->filters['ref_number'])) {
                $query->where('ref_number', 'LIKE', '%' . trim($this->filters['ref_number']) . '%');
            }
            
            if (!empty($this->filters['withholding_form'])) {
                $query->where('withholding_form', $this->filters['withholding_form']);
            }
            
            if (!empty($this->filters['document_date_start']) && !empty($this->filters['document_date_end'])) {
                $query->whereBetween('document_doc_date', [$this->filters['document_date_start'], $this->filters['document_date_end']]);
            } elseif (!empty($this->filters['document_date_start'])) {
                $query->whereDate('document_doc_date', '>=', $this->filters['document_date_start']);
            } elseif (!empty($this->filters['document_date_end'])) {
                $query->whereDate('document_doc_date', '<=', $this->filters['document_date_end']);
            }
            
            if (!empty($this->filters['customer'])) {
                $query->where(function($q) {
                    $q->where('customer_id', $this->filters['customer'])
                      ->orWhere('wholesale_id', $this->filters['customer']);
                });
            }
            
            $this->documents = $query->orderBy('document_doc_date', 'desc')
                                   ->orderBy('document_number', 'desc')
                                   ->get();
        }
        
        return $this->documents;
    }

    public function headings(): array
    {
        return [
            'ลำดับ',
            'เลขที่เอกสาร',
            'Ref.Number',
            'แบบฟอร์ม ภงด',
            'Quote.Ref',
            'ชื่อผู้จอง',
            'ชื่อผู้ถูกหัก',
            'วันที่ออกเอกสาร',
            'ยอดชำระ (บาท)',
            'ยอดหัก ณ ที่จ่าย (บาท)',
            'หมายเหตุ',
            'ผู้จัดทำ',
            'วันที่สร้าง'
        ];
    }

   

    public function map($document): array
    {

          if ($document->quote_id) 
          {
            $Documentname = $document->wholesale->wholesale_name_th ?? $document->customer->customer_name ?? '-';
          }else{
            $Documentname = $document->customer->customer_name ?? '-';
          }


        return [
            ++$this->num,
            $document->document_number ?? '-',
            $document->ref_number ?? '-',
            $document->withholding_form ?? '-',
            optional($document->quote)->quote_number ?? '-',
            optional($document->customer)->customer_name ?? optional($document->wholesale)->wholesale_name_th ?? '-',
            $Documentname,
            
            $document->document_doc_date ? date('d/m/Y', strtotime($document->document_doc_date)) : '-',
            $document->total_payable ? number_format($document->total_payable, 2) : '0.00',
            $document->total_withholding_tax ? number_format($document->total_withholding_tax, 2) : '0.00',
            $document->withholding_note ?? '-',
            $document->created_by ?? '-',
            $document->created_at ? date('d/m/Y H:i', strtotime($document->created_at)) : '-'
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // ลำดับ
            'B' => 18,  // เลขที่เอกสาร
            'C' => 18,  // Ref.Number
            'D' => 12,  // แบบฟอร์ม ภงด
            'E' => 18,  // Quote.Ref
            'F' => 25,  // ชื่อผู้จอง
            'G' => 25,  // ชื่อผู้ถูกหัก
            'H' => 15,  // วันที่ออกเอกสาร
            'I' => 18,  // ยอดชำระ
            'J' => 20,  // ยอดหัก ณ ที่จ่าย
            'K' => 30,  // หมายเหตุ
            'L' => 15,  // ผู้จัดทำ
            'M' => 18   // วันที่สร้าง
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000']
                    ]
                ]
            ],
            // Data rows styling
            'A2:M' . ($this->documents ? $this->documents->count() + 1 : 2) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC']
                    ]
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            // Center align for specific columns
            'A:A' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // ลำดับ
            'D:D' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // แบบฟอร์ม
            'H:H' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // วันที่
            'I:J' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT]], // ยอดเงิน
            'M:M' => ['alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]], // วันที่สร้าง
        ];
    }
}
