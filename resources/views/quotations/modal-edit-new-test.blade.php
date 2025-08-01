<div class="modal-body">
    <form id="formEditNewTest">
        <!-- Header -->
        <div class="p-6">
            <h4 class="text-1xl font-semibold">แก้ไขใบเสนอราคา/ใบจองทัวร์ #{{ $quotationModel->quote_number }}</h4>
            <p class="text-sm text-gray-500">{{ $quotationModel->quote_tour_name ?? '' }}</p>
        </div>


        <!-- Quote Cards -->

        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 px-6 row">
            <!-- Card 1 -->
            <div class="bg-white rounded-lg p-4 shadow">
                <span
                    class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded mb-3 inline-block">รายละเอียดแพคเกจทัวร์</span>

                <!-- ✅ ใช้ Grid ใหญ่ชุดเดียว -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">

                    <!-- ชื่อแพคเกจ -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ชื่อแพคเกจทัวร์</label>
                        <input type="text" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $quotationModel->quote_tour_name ?? '' }}" placeholder="ค้นหาชื่อแพคเกจทัวร์">
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
            <h2 class="text-md font-semibold bg-red-100 mb-3 text-gray-800 px-2 py-1 rounded mb-3 inline-block">
                รายละเอียดใบเสนอราคา</h2>

            <div class="overflow-x-auto bg-white rounded-lg shadow p-4">
                <table class="min-w-full table-auto border">
                    <thead class="bg-gray-100 text-gray-700 text-sm">
                        <tr>
                            <th class="border px-3 py-2 text-left">ลำดับ</th>
                            <th class="border px-3 py-2 text-left">รายการสินค้า</th>
                            <th class="border px-3 py-2 text-center">จำนวน</th>
                            <th class="border px-3 py-2 text-center">หน่วย</th>
                            <th class="border px-3 py-2 text-right">ราคา/หน่วย</th>
                            <th class="border px-3 py-2 text-right">ราคารวม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Row 1 -->
                        <tr class="text-sm text-gray-700">
                            <td class="border px-3 py-2">1</td>
                            <td class="border px-3 py-2">
                                <input type="text" class="w-full border border-gray-300 rounded px-2 py-1"
                                    placeholder="ชื่อสินค้า...">
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <input type="number"
                                    class="w-20 border border-gray-300 rounded px-2 py-1 text-center" value="1">
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <select class="w-full border border-gray-300 rounded px-2 py-1">
                                    <option>ชิ้น</option>
                                    <option>กล่อง</option>
                                    <option>ชุด</option>
                                </select>
                            </td>
                            <td class="border px-3 py-2 text-right">
                                <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right"
                                    value="0.00">
                            </td>
                            <td class="border px-3 py-2 text-right text-gray-800 font-medium">0.00</td>
                        </tr>

                        <!-- Add more rows as needed -->
                        <tr class="text-sm text-gray-700">
                            <td class="border px-3 py-2">2</td>
                            <td class="border px-3 py-2">
                                <input type="text" class="w-full border border-gray-300 rounded px-2 py-1"
                                    placeholder="ชื่อสินค้า...">
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <input type="number"
                                    class="w-20 border border-gray-300 rounded px-2 py-1 text-center" value="2">
                            </td>
                            <td class="border px-3 py-2 text-center">
                                <select class="w-full border border-gray-300 rounded px-2 py-1">
                                    <option>ชิ้น</option>
                                    <option>กล่อง</option>
                                    <option>ชุด</option>
                                </select>
                            </td>
                            <td class="border px-3 py-2 text-right">
                                <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right"
                                    value="120.00">
                            </td>
                            <td class="border px-3 py-2 text-right text-gray-800 font-medium">240.00</td>
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
                        </div>

                    </div>

                    <!-- ขวา -->
                    <div class="w-full md:w-1/2 lg:w-1/3 space-y-2">
                        <div class="flex justify-between">
                            <span>ยอดรวมยกเว้นภาษี / Vat-Exempted Amount:</span>
                            <span class="font-semibold text-gray-800">240.00</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax Amount:</span>
                            <span class="font-semibold text-gray-800">16.80</span>
                        </div>
                        <div class="flex justify-between ">
                            <span>ส่วนลด / Discount :</span>
                            <span>256.80</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount:</span>
                            <span>256.80</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ภาษีมูลค่าเพิ่ม VAT : 7%:</span>
                            <span>256.80</span>
                        </div>
                        <div class="flex justify-between">
                            <span>ราคารวมภาษีมูลค่าเพิ่ม / Include VAT:</span>
                            <span>256.80</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold text-blue-700 border-t pt-2 mt-2">
                            <span>จำนวนเงินรวมทั้งสิ้น / Grand Total:</span>
                            <span>256.80</span>
                        </div>

                    </div>
                </div>


            </div>
        </div>

    </form>
</div>

<script src="https://cdn.tailwindcss.com"></script>
