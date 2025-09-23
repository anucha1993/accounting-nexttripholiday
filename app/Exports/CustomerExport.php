<?php

namespace App\Exports;

use App\Models\customers\customerModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\Support\Responsable;

class CustomerExport implements FromCollection, WithHeadings, WithMapping
{
    private $filters;
    public $fileName = 'customers_export.xlsx';

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = customerModel::with('campaign_source');
        
        if (!empty($this->filters['name'])) {
            $query->where('customer_name', 'like', '%' . $this->filters['name'] . '%');
        }
        if (!empty($this->filters['email'])) {
            $query->where('customer_email', 'like', '%' . $this->filters['email'] . '%');
        }
        if (!empty($this->filters['phone'])) {
            $query->where('customer_tel', 'like', '%' . $this->filters['phone'] . '%');
        }
        
        return $query->orderBy('customer_id', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'รหัสลูกค้า',
            'ชื่อลูกค้า',
            'Email',
            'ลูกค้ามาจาก',
            'เบอร์โทร',
            'ที่อยู่',
            'วันที่สร้าง',
        ];
    }
    
    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->customer_id,
            $row->customer_number,
            $row->customer_name,
            $row->customer_email,
            $row->campaign_source ? $row->campaign_source->campaign_source_name : 'ไม่ระบุ',
            $row->customer_tel,
            $row->customer_address,
            date('d-m-Y', strtotime($row->created_at)),
        ];
    }
}
