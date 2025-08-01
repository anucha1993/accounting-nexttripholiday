<div class="modal-body relative">
    <!-- ปุ่มปิดมุมขวาบน -->
    <button type="button" data-bs-dismiss="modal" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-2xl font-bold focus:outline-none" aria-label="Close">
        &times;
    </button>
    <form id="formEditNewTest">
        <!-- Header -->
        <div class="card-header bg-gray-200 shadow rounded mb-4">
           <div class="p-6 ">
            <h4 class="text-1xl font-semibold">แก้ไขใบเสนอราคา/ใบจองทัวร์ #{{ $quotationModel->quote_number }}</h4>
            <p class="text-sm text-gray-500">{{ $quotationModel->quote_tour_name ?? '' }}</p>
        </div>
        </div>
       


        <!-- Quote Cards -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-6 row">
            <!-- Card 1 -->
            <div class="bg-white rounded-lg p-4 shadow">
                <span
                    class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded mb-3 inline-block">รายละเอียดแพคเกจทัวร์</span>

                <!-- ✅ ใช้ Grid ใหญ่ชุดเดียว -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">

                    <!-- ชื่อแพคเกจทัวร์ (Autocomplete/Search) -->
                    <div class="md:col-span-2 relative">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ชื่อแพคเกจทัวร์</label>
                        <div class="flex gap-2">
                            <input type="text" id="tourSearch" autocomplete="off"
                                class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                                value="{{ $quotationModel->quote_tour_name ?? '' }}" placeholder="ค้นหาชื่อแพคเกจทัวร์">
                            <button type="button" id="resetTourSearch" class="px-2 py-1 bg-gray-200 rounded text-xs">ล้าง</button>
                        </div>
                        <!-- Dropdown for search results -->
                        <div id="tourResults" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow mt-1 hidden max-h-48 overflow-y-auto"></div>
                        <!-- Hidden fields for selected tour info -->
                        <input type="hidden" id="tour-id" name="tour_id" value="{{ $quotationModel->tour_id ?? '' }}">
                        <input type="hidden" id="tour-code" name="tour_code" value="{{ $quotationModel->tour_code ?? '' }}">
                        <input type="hidden" id="tour-country" name="tour_country" value="{{ $quotationModel->tour_country ?? '' }}">
                        <input type="hidden" id="tour-wholesaler" name="tour_wholesaler" value="{{ $quotationModel->tour_wholesaler ?? '' }}">
                        <input type="hidden" id="tour-airline" name="tour_airline" value="{{ $quotationModel->tour_airline ?? '' }}">
                    </div>

                    <!-- รหัส API -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">รหัสทัวร์ API แก้ไขไม่ได้*</label>
                        <input type="text"
                            class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-yellow-100"
                            placeholder="รหัสทัวร์ API" readonly>
                    </div>

                    <!-- รหัสกำหนดเอง -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">รหัสทัวร์ กำหนดเอง</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            placeholder="รหัสทัวร์ กำหนดเอง">
                    </div>

                    <!-- ระยะเวลา -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">ระยะเวลาทัวร์ (วัน/คืน)</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            <option value="3">3 วัน 2 คืน</option>
                            <option value="5">5 วัน 4 คืน</option>
                        </select>
                    </div>

                    <!-- ประเทศ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">ประเทศที่เดินทาง</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            <option value="jp">ญี่ปุ่น</option>
                            <option value="kr">เกาหลี</option>
                        </select>
                    </div>

                    <!-- โฮลเซลล์ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">โฮลเซลล์</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            <option value="wh1">Wholesaler 1</option>
                        </select>
                    </div>

                    <!-- สายการบิน -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">สายการบิน</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            <option value="tg">Thai Airways</option>
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">วันออกเดินทาง: เลือกวันที่</label>
                        <input type="date" class="w-full border border-gray-300 rounded px-3 py-1 text-sm">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">วันที่เดินทางกลับ</label>
                        <input type="date" class="w-full border border-gray-300 rounded px-3 py-1 text-sm">
                    </div>


                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-lg p-4 shadow border border-green-100">
                <span class="text-xs bg-green-100  px-2 py-1 rounded mb-3 inline-block">ข้อมูลลูกค้า</span>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ชื่อลูกค้า</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            placeholder="ค้นหาชื่อลูกค้า">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">เลขผู้เสียภาษี</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            placeholder="เลขผู้เสียภาษี">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">อีเมลล์</label>
                        <input type="email" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            placeholder="อีเมลล์ลูกค้า">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            placeholder="เบอร์โทรศัพท์">
                    </div>


                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">เบอร์โทรสาร(Fax)</label>
                        <input type="email" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            placeholder="เบอร์โทรสาร(Fax)">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">ลูกค้าจาก</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Social id</label>
                        <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ที่อยู่ลูกค้า</label>
                        <textarea rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none"
                            placeholder="ที่อยู่ลูกค้า"></textarea>
                    </div>

                </div>


            </div>

            {{-- <!-- Card 3 -->
    <div class="bg-white rounded-lg p-4 shadow">
      <div class="flex justify-between">
        <div>
          <p class="text-sm font-medium text-gray-600">Bulk Quote ID: <span class="text-gray-900">#563248512</span></p>
          <p class="text-xs text-gray-400">20/09/2023, 1:54 PM</p>
        </div>
        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">New</span>
      </div>
      <div class="mt-3 text-sm space-y-1">
        <p>No of Quotes: <span class="font-medium">6</span></p>
        <p>Total Weight: <span class="font-medium">300 KG</span></p>
        <p>Total Volume: <span class="font-medium">3 M³</span></p>
      </div>
      <div class="mt-3 flex justify-between items-center">
        <span class="text-blue-600 text-sm font-semibold">Estimated Quotation</span>
        <span class="text-lg font-bold text-gray-900">$6,859</span>
      </div>
    </div> --}}
        </div>

        <!-- Rejected Quote Section -->
        <!-- Quotation Input Table -->
        <div class="px-6 mt-10">
            <h2 class="text-md font-semibold bg-green-100 mb-3 text-gray-800 px-2 py-1 rounded mb-3 inline-block">
                รายละเอียดใบเสนอราคา</h2>

            <input type="radio" id="commission" name="quote_commission" value="Y"
                class="mt-1.5  focus:ring-blue-500" checked>
            <label for="commission" class="text-gray-800">
                จ่ายค่าคอมมิชชั่น
            </label>
            <input type="radio" id="commission" name="quote_commission" value="N"
                class="mt-1.5  focus:ring-blue-500" checked>
            <label for="commission" class="text-gray-800">
                ไม่จ่ายค่าคอมมิชชั่น
            </label>

            <div class="overflow-x-auto  rounded-lg shadow p-4" style="background-color: rgba(255, 228, 196, 0.062)">
                <table class="min-w-full table-auto border">

                    <thead class="bg-gray-100 text-gray-700 text-sm">
                        <tr>
                            <th class="border px-3 py-2 text-left">ลำดับ</th>
                            <th class="border px-3 py-2 text-left">รายการสินค้า</th>
                            <th class="border px-3 py-2 text-center">รวม 3%</th>
                            <th class="border px-3 py-2 text-center">NonVat</th>
                            <th class="border px-3 py-2 text-center">จำนวน</th>
                            <th class="border px-3 py-2 text-right">ราคา/หน่วย</th>
                            <th class="border px-3 py-2 text-right">ยอดรวม</th>
                            <th class="border px-3 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-rows"></tbody>
                    <tbody>
                        <tr>
                            <td colspan="8">
                                <button type="button" onclick="addProductRow()"
                                    class="mb-2 px-2 py-1 bg-blue-200 text-blue-800 rounded text-xs mt-1">+
                                    เพิ่มรายการค่าบริการ</button>
                            </td>
                        </tr>
                    </tbody>

                    <tbody>
                        <tr>
                            <td colspan="7">
                                <h2 class="text-md font-semibold bg-red-100 mb-3 text-gray-800 px-2 py-1 rounded mb-3 mt-3 inline-block">
                                    รายการส่วนลด</h2>
                            </td>
                        </tr>
                    </tbody>

                    <tbody id="discount-rows"></tbody>

                    <tbody>
                        <tr>
                            <td colspan="7">

                                <button type="button" onclick="addDiscountRow()"
                                    class=" px-2 py-1 bg-red-200 text-red-800 rounded text-xs mt-1">+
                                    เพิ่มส่วนลด</button>
                            </td>
                        </tr>
                    </tbody>
                </table>


                <div class="flex justify-between gap-6 mt-4 text-sm">
                    <!-- ซ้าย -->
                    <div class="w-full md:w-1/2 lg:w-1/3 space-y-2">
                        <div class="text-sm space-y-2">
                            <label class=" text-gray-700 text-blue-600">การคำนวณ VAT:</label>

                            <div class="flex items-start space-x-2">
                                <input type="radio" id="vat_include" name="vat_option" value="include"
                                    class="mt-1.5  focus:ring-blue-500" checked>
                                <label for="vat_include" class="text-gray-800">
                                    คำนวณรวมกับราคาสินค้าและบริการ (VAT Include)
                                </label>
                            </div>

                            <div class="flex items-start space-x-2">
                                <input type="radio" id="vat_exclude" name="vat_option" value="exclude"
                                    class="mt-1.5 focus:ring-blue-500">
                                <label for="vat_exclude" class="text-gray-800">
                                    คำนวณแยกกับราคาสินค้าและบริการ (VAT Exclude)
                                </label>
                            </div>
                            <hr>
                            <div class="flex items-start space-x-2">
                                <input type="checkbox" id="pre-vat" name="pre-vat" value="exclude"
                                    class="mt-1.5 focus:ring-blue-500" onchange="calculateQuotation()">
                                <label for="vat_exclude" class="text-gray-800">
                                    คิดภาษีหัก ณ ที่จ่าย 3% (Withholding Tax)
                                </label>
                            </div>
                            <div class="flex items-start space-x-2">
                                <label for="vat_exclude" class="text-gray-800">
                                    จำนวนเงินภาษีหัก ณ ที่จ่าย 3% : <span id="withholdingTax">0.00</span> บาท
                                </label>
                            </div>
                        </div>

                    </div>

                    <!-- ขวา -->
                    <div class="w-full md:w-1/2 lg:w-1/3 space-y-2">
                        <div class="flex justify-between">
                            <span>ยอดรวมยกเว้นภาษี / Vat-Exempted Amount:</span>
                            <span class="font-semibold text-gray-800" data-summary="vatExempted">0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax Amount:</span>
                            <span class="font-semibold text-gray-800" data-summary="preTax">0.00</span>
                        </div>
                        <div class="flex justify-between ">
                            <span>ส่วนลด / Discount :</span>
                            <span data-summary="discount">0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount:</span>
                            <span data-summary="preVatAmount">0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ภาษีมูลค่าเพิ่ม VAT : 7%:</span>
                            <span data-summary="vatAmount">0.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ราคารวมภาษีมูลค่าเพิ่ม / Include VAT:</span>
                            <span data-summary="includeVat">0.00</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-blue-700 border-t pt-2 mt-2">
                            <span>จำนวนเงินรวมทั้งสิ้น / Grand Total:</span>
                            <span data-summary="grandTotal">0.00</span>
                        </div>

                    </div>
                </div>




                <!-- ✅ รวม 2 กล่องให้อยู่ใน grid เดียว -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-2 mt-3 row">

                    <!-- กล่อง 1 -->
                    <div class="rounded-lg p-4 shadow bg-yellow-100/50">
                        <span class="text-xs bg-yellow-300 px-2 py-1 rounded mb-3 inline-block">
                            เงื่อนไขการชำระเงินมัดจำ
                        </span>

                        <!-- แถวที่ 1 -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                            <div class="col-span-full">
                                <input type="radio" id="payment_condition_deposit" name="" value="include"
                                    class="mt-1.5 focus:ring-blue-500" checked>
                                <label for="payment_condition_deposit" class="text-gray-800">
                                    ชำระเงินมัดจำ
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">ภายในวันที่</label>
                                <input type="date" class="w-full border border-gray-300 rounded px-3 py-1 text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">เรทเงินมัดจำ</label>
                                <select class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                                    <option value="">-- Select --</option>
                                </select>
                            </div>
                        </div>

                        <!-- แถวที่ 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                            <div class="md:col-span-2">
                                <label class="text-gray-800">ชำระเพิ่มเติม</label>
                                <input type="number" value="0"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">จำนวนเงินที่ต้องชำระ</label>
                                <input type="number" value="0"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end">
                            </div>
                        </div>
                       

                    </div>




                    <!-- กล่อง 2 -->
                    <div class="bg-green-100/50 rounded-lg p-4 shadow mt-1">
                        <span
                            class="text-xs bg-yellow-300  px-2 py-1 rounded mb-3 inline-block">เงื่อนไขการชำระเต็มจำนวน</span>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                            <div class="flex items-start space-x-2">
                                <input type="radio" id="payment_condition_full" name="" value="exclude"
                                    class="mt-1.5 focus:ring-blue-500">
                                <label for="payment_condition_full" class="text-gray-800">
                                    ชำระเต็มจำนวน
                                </label>

                            </div>
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3 mt-1">
                            <div class="md:col-span-2">
                                <label class="text-gray-800">ภายในวันที่</label>
                                <input type="date" class="w-full border border-gray-300 rounded px-3 py-1 text-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">จำนวนเงิน</label>
                                <input type="number" value="0"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end">
                            </div>
                        </div>
                         <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3 mt-1">
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">บันทึกเพิ่มเติม</label>
                                <textarea rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none"
                                    placeholder="บันทึกเพิ่มเติม"></textarea>
                            </div>
                        </div>



                    </div>

                </div>



    </form>
        <!-- ปุ่มบันทึกและปุ่มปิดด้านล่าง -->
        <div class="flex flex-col md:flex-row justify-end items-center gap-2 mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                บันทึก
            </button>
            <button type="button" data-bs-dismiss="modal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded shadow">
                ปิด
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.tailwindcss.com"></script>

<script>
// --- Autocomplete/Search logic for ชื่อแพคเกจทัวร์ (ใช้ endpoint และโครงสร้างเดียวกับ modal-edit.blade.php) ---
$(function() {
    $('#tourSearch').on('input', function() {
        var searchTerm = $(this).val();
        if (searchTerm.length < 2) {
            $('#tourResults').empty().hide();
            return;
        }
        // ใช้ endpoint API ที่ถูกต้อง
        $.ajax({
            url: '{{ route('api.tours') }}', // ปรับ URL ตาม API ที่ใช้
            method: 'GET',
            data: { q: searchTerm },
            success: function(data) {
                // data = [ { id, name, code, country, wholesaler, airline } ] หรือปรับตามที่ API ส่งกลับ
                var results = Array.isArray(data) ? data : (data.data || []);
                if (results.length === 0) {
                    $('#tourResults').html('<div class="px-3 py-2 text-gray-500">ไม่พบข้อมูล</div>').show();
                    return;
                }
                var html = results.map(function(tour) {
                    return `<a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer" data-id="${tour.id}" data-name="${tour.name}" data-code="${tour.code || ''}" data-country="${tour.country || ''}" data-wholesaler="${tour.wholesaler || ''}" data-airline="${tour.airline || ''}">${tour.name} <span class='text-xs text-gray-400'>(${tour.code || ''})</span></a>`;
                }).join('');
                $('#tourResults').html(html).show();
            },
            error: function() {
                $('#tourResults').html('<div class="px-3 py-2 text-red-500">เกิดข้อผิดพลาด</div>').show();
            }
        });
    });

    // เลือก tour จากผลลัพธ์
    $(document).on('click', '#tourResults a', function(e) {
        e.preventDefault();
        var $a = $(this);
        $('#tourSearch').val($a.data('name'));
        $('#tour-id').val($a.data('id'));
        $('#tour-code').val($a.data('code'));
        $('#tour-country').val($a.data('country'));
        $('#tour-wholesaler').val($a.data('wholesaler'));
        $('#tour-airline').val($a.data('airline'));
        $('#tourResults').empty().hide();
    });

    // ปุ่มล้าง
    $('#resetTourSearch').on('click', function() {
        $('#tourSearch').val('');
        $('#tour-id, #tour-code, #tour-country, #tour-wholesaler, #tour-airline').val('');
        $('#tourResults').empty().hide();
    });

    // ปิดผลลัพธ์เมื่อคลิกนอก
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#tourResults, #tourSearch').length) {
            $('#tourResults').empty().hide();
        }
    });
});
    function calculateQuotation() {
        let vatType = document.querySelector('input[name="vat_option"]:checked').value;
        let vatRate = 0.07;
        let productRows = document.querySelectorAll('#product-rows tr');
        let discountRows = document.querySelectorAll('#discount-rows tr');
        let vatExempted = 0,
            preTax = 0,
            discount = 0;
        productRows.forEach(row => {
            let type = row.querySelector('select')?.value || 'N';
            let qty = parseFloat(row.querySelector('.qty')?.value || 0);
            let price = parseFloat(row.querySelector('.price')?.value || 0);
            let is3 = row.querySelector('input[type="checkbox"]')?.checked;
            let sum = qty * price;
            if (is3) sum = sum * 1.03;
            if (type === 'N') vatExempted += sum;
            else preTax += sum;
            let sumCell = row.querySelector('.sum');
            if (sumCell) sumCell.textContent = sum.toFixed(2);
        });
        discountRows.forEach(row => {
            let qty = parseFloat(row.querySelector('.qty')?.value || 0);
            let price = parseFloat(row.querySelector('.price')?.value || 0);
            let is3 = row.querySelector('input[type="checkbox"]')?.checked;
            let sum = qty * price;
            if (is3) sum = sum * 1.03;
            discount += sum;
            let sumCell = row.querySelector('.sum');
            if (sumCell) sumCell.textContent = sum.toFixed(2);
        });
        // ปรับสูตรส่วนลด: หักกับ Vat ก่อน ถ้าเหลือค่อยไปหักกับ NonVat
        let discountForVat = 0;
        let discountForNonVat = 0;
        if (preTax > 0) {
            discountForVat = Math.min(discount, preTax);
            discountForNonVat = discount - discountForVat;
        } else {
            discountForVat = 0;
            discountForNonVat = discount;
        }
        let preVatAmount = 0;
        let vatExemptedAfterDiscount = Math.max(vatExempted - discountForNonVat, 0);
        let vatAmount = 0,
            includeVat = 0;
        if (vatType === 'exclude') {
            preVatAmount = Math.max(preTax - discountForVat, 0);
            vatAmount = preVatAmount * vatRate;
            includeVat = preVatAmount + vatAmount;
        } else {
            includeVat = Math.max(preTax - discountForVat, 0);
            preVatAmount = includeVat / (1 + vatRate);
            vatAmount = includeVat - preVatAmount;
        }
        let grandTotal = vatExemptedAfterDiscount + includeVat;
        document.querySelector('[data-summary="vatExempted"]').textContent = vatExemptedAfterDiscount.toFixed(2);
        document.querySelector('[data-summary="preTax"]').textContent = preTax.toFixed(2);
        document.querySelector('[data-summary="discount"]').textContent = discount.toFixed(2);
        document.querySelector('[data-summary="preVatAmount"]').textContent = preVatAmount.toFixed(2);
        document.querySelector('[data-summary="vatAmount"]').textContent = vatAmount.toFixed(2);
        document.querySelector('[data-summary="includeVat"]').textContent = includeVat.toFixed(2);
        document.querySelector('[data-summary="grandTotal"]').textContent = grandTotal.toFixed(2);

        // คำนวณภาษีหัก ณ ที่จ่าย 3% (Withholding Tax) ถ้ามีการติ๊ก checkbox
        let withholdingTax = 0;
        let preVatCheckbox = document.getElementById('pre-vat');
        if (preVatCheckbox && preVatCheckbox.checked) {
            withholdingTax = preVatAmount * 0.03;
        } else {
            withholdingTax = 0;
        }
        document.getElementById('withholdingTax').textContent = withholdingTax.toFixed(2);
    }

    function addProductRow() {
        let tbody = document.getElementById('product-rows');
        let idx = tbody.children.length + 1;
        let tr = document.createElement('tr');
        tr.className = 'text-sm text-gray-700';
        tr.innerHTML = `
        <td class="border px-3 py-2 row-index">${idx}</td>
        <td class="border px-3 py-2">
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" placeholder="ชื่อสินค้า..." onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-center">
            <input type="checkbox" class="w-20 border border-gray-300 rounded px-2 py-1 text-center" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-center">
            <select class="w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                <option value="N">NonVat</option>
                <option value="Y">Vat</option>
            </select>
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right qty" value="0.00" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right price" value="0.00" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">0.00</td>
        <td class="border px-3 py-2 text-center">
            <button type="button" class="text-red-500" onclick="removeRow(this, 'product-rows')"><i class="fas fa-trash"></i></button>
        </td>
    `;
        tbody.appendChild(tr);
        calculateQuotation();
    }

    function addDiscountRow() {
        let tbody = document.getElementById('discount-rows');
        let idx = tbody.children.length + 1;
        let tr = document.createElement('tr');
        tr.className = 'text-sm text-gray-700';
        tr.innerHTML = `
        <td class="border px-3 py-2 row-index">${idx}</td>
        <td class="border px-3 py-2">
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" placeholder="ชื่อส่วนลด..." onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-center">
            <input type="checkbox" class="w-20 border border-gray-300 rounded px-2 py-1 text-center" onchange="calculateQuotation()">
            <input type="hidden" value="N">
        </td>
        <td class="border px-3 py-2 text-center">
            <select class="w-full border border-gray-300 rounded px-2 py-1" disabled><option value="N">NonVat</option></select>
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right qty" value="0.00" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right price" value="0.00" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">0.00</td>
        <td class="border px-3 py-2 text-center">
            <button type="button" class="text-red-500" onclick="removeRow(this, 'discount-rows')"><i class="fas fa-trash"></i></button>
        </td>
    `;
        tbody.appendChild(tr);
        calculateQuotation();
    }
    // ลบแถวและอัปเดตลำดับ
    function removeRow(btn, tbodyId) {
        let tr = btn.closest('tr');
        let tbody = document.getElementById(tbodyId);
        tbody.removeChild(tr);
        // อัปเดตลำดับ
        Array.from(tbody.children).forEach((row, i) => {
            let idxCell = row.querySelector('.row-index');
            if (idxCell) idxCell.textContent = i + 1;
        });
        calculateQuotation();
    }
    // Event: คำนวณเมื่อเปลี่ยน VAT Option
    document.querySelectorAll('input[name="vat_option"]').forEach(el => {
        el.addEventListener('change', calculateQuotation);
    });

    // Event delegation: คำนวณเมื่อ input หรือ select ใน tbody มีการเปลี่ยนแปลง (dynamic row)
    document.getElementById('product-rows').addEventListener('input', calculateQuotation);
    document.getElementById('product-rows').addEventListener('change', calculateQuotation);
    document.getElementById('discount-rows').addEventListener('input', calculateQuotation);
    document.getElementById('discount-rows').addEventListener('change', calculateQuotation);

    window.addEventListener('DOMContentLoaded', () => {
        addProductRow();
        addDiscountRow();
    });
</script>

