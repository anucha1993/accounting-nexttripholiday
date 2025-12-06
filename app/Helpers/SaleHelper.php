<?php

use App\Models\sales\saleModel;

if (!function_exists('getActiveSales')) {
    /**
     * ดึงข้อมูล Sale ที่ active สำหรับใช้ใน dropdown
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getActiveSales()
    {
        return saleModel::select('name', 'id')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->orderBy('name', 'asc')
            ->get();
    }
}

if (!function_exists('getAllSales')) {
    /**
     * ดึงข้อมูล Sale ทั้งหมด รวมถึงที่ inactive
     * ใช้สำหรับแสดงข้อมูลเก่า
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getAllSales()
    {
        return saleModel::withInactive()
            ->select('name', 'id', 'status')
            ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
            ->orderBy('name', 'asc')
            ->get();
    }
}

if (!function_exists('getSaleById')) {
    /**
     * ดึงข้อมูล Sale ตาม ID (รวมถึงที่ inactive)
     * 
     * @param int $saleId
     * @return \App\Models\sales\saleModel|null
     */
    function getSaleById($saleId)
    {
        return saleModel::withInactive()->find($saleId);
    }
}

if (!function_exists('getSalesForDropdown')) {
    /**
     * ดึงข้อมูล Sale สำหรับ dropdown ในฟอร์มแก้ไข
     * จะแสดง sale ที่เลือกไว้แม้จะ inactive + sale ที่ active ทั้งหมด
     * 
     * @param int|null $currentSaleId
     * @return \Illuminate\Support\Collection
     */
    function getSalesForDropdown($currentSaleId = null)
    {
        $activeSales = getActiveSales();
        
        if ($currentSaleId) {
            $currentSale = getSaleById($currentSaleId);
            
            // ถ้า sale ที่เลือกไว้เป็น inactive ให้เพิ่มเข้าไปในรายการ
            if ($currentSale && $currentSale->status !== 'active' && !$activeSales->contains('id', $currentSale->id)) {
                $activeSales->prepend($currentSale);
            }
        }
        
        return $activeSales;
    }
}
