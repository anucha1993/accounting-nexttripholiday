# 📘 คู่มือการใช้งาน Sale Helper Functions

## 🎯 วัตถุประสงค์
แก้ปัญหาการแสดง Sale ที่ถูกปิด (inactive) ในระบบ โดย:
- ✅ Dropdown แสดงเฉพาะ Sale ที่ active
- ✅ ข้อมูลเก่ายังแสดง Sale ที่ถูกปิดได้
- ✅ ไม่เกิด Error เมื่อ Sale ถูกปิด

---

## 🔧 Helper Functions ที่มีให้ใช้

### 1. `getActiveSales()` - สำหรับ Dropdown ใหม่
ใช้เมื่อ: สร้างฟอร์มใหม่, แสดง dropdown สำหรับเลือก Sale

```php
// ใน Controller
$sales = getActiveSales();

// ส่งไปยัง View
return view('quotations.create', compact('sales'));
```

```blade
<!-- ใน View -->
<select name="quote_sale" class="form-select">
    @foreach (getActiveSales() as $sale)
        <option value="{{ $sale->id }}">{{ $sale->name }}</option>
    @endforeach
</select>
```

---

### 2. `getSalesForDropdown($currentSaleId)` - สำหรับ Dropdown แก้ไข
ใช้เมื่อ: แก้ไขข้อมูลที่มี Sale เก่า (อาจถูกปิดแล้ว)

```php
// ใน Controller (Edit Form)
$sales = getSalesForDropdown($quotationModel->quote_sale);

return view('quotations.edit', compact('quotationModel', 'sales'));
```

```blade
<!-- ใน View -->
<select name="quote_sale" class="form-select">
    @foreach ($sales as $sale)
        <option value="{{ $sale->id }}" 
            {{ $quotationModel->quote_sale == $sale->id ? 'selected' : '' }}
            @if(isset($sale->status) && $sale->status != 'active') 
                style="color: #dc3545; background-color: #f8d7da;" 
            @endif>
            {{ $sale->name }}
            @if(isset($sale->status) && $sale->status != 'active') 
                (ปิดการใช้งานแล้ว)
            @endif
        </option>
    @endforeach
</select>
```

---

### 3. `getAllSales()` - สำหรับ Report/ดูข้อมูลทั้งหมด
ใช้เมื่อ: ต้องการดูข้อมูล Sale ทั้งหมด รวม inactive

```php
// ใน Controller (Report)
$sales = getAllSales();

return view('reports.sales', compact('sales'));
```

---

### 4. `getSaleById($saleId)` - สำหรับดึงข้อมูล Sale เฉพาะคน
ใช้เมื่อ: ต้องการข้อมูล Sale 1 คน (รวมถึง inactive)

```php
// ดึง Sale แม้จะ inactive
$sale = getSaleById($saleId);

if ($sale) {
    echo $sale->name;
    echo $sale->status; // 'active' หรือ 'inactive'
}
```

---

## 📝 วิธีแก้ไข Controller เดิม

### ❌ เดิม (มีปัญหา)
```php
$sales = saleModel::select('name', 'id')
    ->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])
    ->get();
```

### ✅ ใหม่ (แก้แล้ว)

#### สำหรับฟอร์มสร้างใหม่:
```php
$sales = getActiveSales();
```

#### สำหรับฟอร์มแก้ไข:
```php
$sales = getSalesForDropdown($quotationModel->quote_sale);
```

---

## 🎨 ตัวอย่างการใช้งานใน Views

### 1. Dropdown สร้างใหม่ (แสดงเฉพาะ Active)
```blade
<div class="col-md-2">
    <label>เซลล์ผู้ขาย:</label>
    <select name="quote_sale" class="form-select select2" required>
        @foreach (getActiveSales() as $sale)
            <option value="{{ $sale->id }}">{{ $sale->name }}</option>
        @endforeach
    </select>
</div>
```

### 2. Dropdown แก้ไข (แสดง Sale เดิมแม้จะปิด)
```blade
<div class="col-md-2">
    <label>เซลล์ผู้ขาย:</label>
    <select name="quote_sale" class="form-select select2" required>
        @foreach (getSalesForDropdown($quotationModel->quote_sale) as $sale)
            <option value="{{ $sale->id }}"
                {{ $quotationModel->quote_sale == $sale->id ? 'selected' : '' }}
                @if(isset($sale->status) && $sale->status != 'active')
                    style="color: #dc3545; background-color: #f8d7da;"
                @endif>
                {{ $sale->name }}
                @if(isset($sale->status) && $sale->status != 'active')
                    ⚠️ (ปิดการใช้งาน)
                @endif
            </option>
        @endforeach
    </select>
</div>
```

### 3. แสดงชื่อ Sale (ไม่ Error แม้ปิด)
```blade
<!-- เดิม -->
{{ $quotation->Salename->name ?? 'ไม่พบข้อมูล' }}

<!-- ใหม่ (ปลอดภัยกว่า) -->
{{ getSaleById($quotation->quote_sale)?->name ?? 'ไม่พบข้อมูล' }}
```

---

## 🚀 ไฟล์ที่ควรแก้ไข

### Controllers ที่ต้องแก้:
1. ✅ `app/Http/Controllers/quotations/quoteController.php`
2. ✅ `app/Http/Controllers/quotations/QuoteListController.php`
3. ✅ `app/Http/Controllers/CreditNote/creditNoteController.php`
4. ✅ `app/Http/Controllers/DebitNote/debitNoteController.php`
5. ✅ `app/Http/Controllers/UserController.php`
6. ✅ `app/Http/Controllers/reports/saleReportController.php`

### Views ที่ต้องแก้:
1. ✅ `resources/views/quotations/modal-edit.blade.php`
2. ✅ `resources/views/quotations/modal-create.blade.php`
3. ✅ `resources/views/creditnote/*.blade.php`
4. ✅ `resources/views/debitnote/*.blade.php`

---

## ⚡ Quick Fix Pattern

### Pattern 1: Controller ฟอร์มสร้างใหม่
```php
// ❌ เดิม
$sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();

// ✅ ใหม่
$sales = getActiveSales();
```

### Pattern 2: Controller ฟอร์มแก้ไข
```php
// ❌ เดิม
$sales = saleModel::select('name', 'id')->whereNotIn('name', ['admin', 'Admin Liw', 'Admin'])->get();

// ✅ ใหม่
$sales = getSalesForDropdown($model->quote_sale);
// หรือ
$sales = getSalesForDropdown($model->invoice_sale);
// หรือ
$sales = getSalesForDropdown($model->debit_sale);
```

---

## 🔍 การตรวจสอบว่าใช้งานถูกต้อง

### Test Case 1: สร้างใบเสนอราคาใหม่
- ✅ Dropdown ควรแสดงเฉพาะ Sale ที่ active
- ✅ ไม่แสดง Sale ที่ปิด

### Test Case 2: แก้ไขใบเสนอราคาเก่า (Sale ยัง active)
- ✅ Dropdown แสดง Sale ทั้งหมดที่ active
- ✅ Sale ที่เลือกไว้ถูก selected

### Test Case 3: แก้ไขใบเสนอราคาเก่า (Sale ถูกปิดแล้ว)
- ✅ Dropdown แสดง Sale ที่ปิดนั้น (สีแดง + คำเตือน)
- ✅ แสดง Sale อื่นๆ ที่ active
- ✅ Sale ที่เลือกไว้ถูก selected
- ✅ ไม่เกิด Error

### Test Case 4: ดูข้อมูลเก่า
- ✅ แสดงชื่อ Sale ได้ แม้จะถูกปิด
- ✅ ไม่เกิด Error หรือ N/A

---

## 🛠️ Troubleshooting

### ปัญหา: Function ไม่ทำงาน
```bash
# แก้ไข: รัน composer dump-autoload
composer dump-autoload
```

### ปัญหา: Sale ที่ปิดยังแสดงใน Dropdown ใหม่
```php
// ตรวจสอบว่าใช้ getActiveSales() หรือไม่
// ❌ ผิด
$sales = getAllSales();

// ✅ ถูก
$sales = getActiveSales();
```

### ปัญหา: Sale ที่ปิดไม่แสดงในฟอร์มแก้ไข
```php
// ตรวจสอบว่าใช้ getSalesForDropdown() หรือไม่
// ❌ ผิด
$sales = getActiveSales();

// ✅ ถูก
$sales = getSalesForDropdown($model->quote_sale);
```

---

## 📞 สรุป

**ใช้ Helper Functions ตามสถานการณ์:**
- 🆕 ฟอร์มใหม่ → `getActiveSales()`
- ✏️ ฟอร์มแก้ไข → `getSalesForDropdown($currentSaleId)`
- 📊 Report/ดูทั้งหมด → `getAllSales()`
- 🔍 ดึงข้อมูล 1 คน → `getSaleById($id)`

**ผลลัพธ์:**
- ✅ ไม่มี Error เมื่อ Sale ถูกปิด
- ✅ Dropdown แสดงเฉพาะ Sale ที่ใช้งานได้
- ✅ ข้อมูลเก่ายังเข้าถึงได้ปกติ
- ✅ Code สะอาดและบำรุงรักษาง่าย
