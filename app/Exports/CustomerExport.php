<?php

namespace App\Exports;

use App\Models\customers\customerModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Support\Responsable;

class CustomerExport implements FromCollection, WithHeadings
{
    private $filters;
    public $fileName = 'customers_export.xlsx';

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = customerModel::query();
        if (!empty($this->filters['name'])) {
            $query->where('customer_name', 'like', '%' . $this->filters['name'] . '%');
        }
        if (!empty($this->filters['email'])) {
            $query->where('customer_email', 'like', '%' . $this->filters['email'] . '%');
        }
        if (!empty($this->filters['phone'])) {
            $query->where('customer_tel', 'like', '%' . $this->filters['phone'] . '%');
        }
        return $query->orderBy('customer_id', 'desc')
            ->get(['customer_id', 'customer_name', 'customer_email', 'customer_tel', 'customer_address', 'customer_date']);
    }

    public function headings(): array
    {
        return [
            'รหัสลูกค้า',
            'ชื่อลูกค้า',
            'Email',
            'เบอร์โทร',
            'ที่อยู่',
            'วันที่สร้าง',
        ];
    }
}
