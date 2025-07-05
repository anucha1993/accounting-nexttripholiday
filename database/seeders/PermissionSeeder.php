<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Quotation
            'quotation-create', 'quotation-edit', 'quotation-delete',
            // User
            'user-create', 'user-edit', 'user-delete',
            // Product
            'product-create', 'product-edit', 'product-delete',
            // Invoice
            'invoice-create', 'invoice-edit', 'invoice-delete',
            // Payment
            'payment-create', 'payment-edit', 'payment-delete',
            // Notification
            'notification-view',
            // Setting
            'setting-edit',
            // Role
            'role-create', 'role-edit', 'role-delete',
            // Report (ละเอียดตาม route)
            'report-inputtax', 'report-invoice', 'report-taxinvoice', 'report-receipt', 'report-saletax', 'report-sales', 'report-payment-wholesale',
            // Export
            'quotation-export',
            // เพิ่มเติมตามต้องการ
        ];

        $permissionLabels = [
            // Quotation
            'quotation-create' => 'เพิ่ม', 'quotation-edit' => 'แก้ไข', 'quotation-delete' => 'ลบ',
            // User
            'user-create' => 'เพิ่ม', 'user-edit' => 'แก้ไข', 'user-delete' => 'ลบ',
            // Product
            'product-create' => 'เพิ่ม', 'product-edit' => 'แก้ไข', 'product-delete' => 'ลบ',
            // Invoice
            'invoice-create' => 'เพิ่ม', 'invoice-edit' => 'แก้ไข', 'invoice-delete' => 'ลบ',
            // Payment
            'payment-create' => 'เพิ่ม', 'payment-edit' => 'แก้ไข', 'payment-delete' => 'ลบ',
            // Notification
            'notification-view' => 'ดูแจ้งเตือน',
            // Setting
            'setting-edit' => 'แก้ไขตั้งค่า',
            // Role
            'role-create' => 'เพิ่ม', 'role-edit' => 'แก้ไข', 'role-delete' => 'ลบ',
            // Report
            'report-inputtax' => 'รายงานภาษีซื้อ',
            'report-invoice' => 'รายงานใบแจ้งหนี้',
            'report-taxinvoice' => 'รายงานใบกำกับภาษี',
            'report-receipt' => 'รายงานใบเสร็จ',
            'report-saletax' => 'รายงานภาษีขาย',
            'report-sales' => 'รายงานยอดขาย',
            'report-payment-wholesale' => 'รายงานจ่ายโฮลเซลล์',
            // Export
            'quotation-export' => 'Export ใบเสนอราคา',
        ];
        foreach ($permissions as $permission) {
            $perm = Permission::findOrCreate($permission);
            if (isset($permissionLabels[$permission])) {
                $perm->label = $permissionLabels[$permission];
                $perm->save();
            }
        }
    }
}
