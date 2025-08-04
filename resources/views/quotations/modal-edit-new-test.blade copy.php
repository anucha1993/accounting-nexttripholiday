<div class="modal-body relative">
    <!-- ปุ่มปิดมุมขวาบน -->
    <button type="button" data-bs-dismiss="modal" class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-2xl font-bold focus:outline-none" aria-label="Close">
        &times;
    </button>
    <form id="formEditNewTest" method="POST" action="{{ route('quote.update', $quotationModel->quote_id) }}">
        <!-- Hidden summary fields for backend -->
        <input type="hidden" name="quote_vat_exempted_amount" id="quote_vat_exempted_amount" value="0">
        <input type="hidden" name="quote_pre_tax_amount" id="quote_pre_tax_amount" value="0">
        <input type="hidden" name="quote_discount" id="quote_discount" value="0">
        <input type="hidden" name="quote_pre_vat_amount" id="quote_pre_vat_amount" value="0">
        <input type="hidden" name="quote_vat" id="quote_vat" value="0">
        <input type="hidden" name="quote_include_vat" id="quote_include_vat" value="0">
        <input type="hidden" name="quote_grand_total" id="quote_grand_total" value="0">
        <input type="hidden" name="quote_withholding_tax" id="quote_withholding_tax" value="0">
        @csrf
        @method('PUT')
        <!-- Hidden Fields -->
        <input type="hidden" name="quote_id" value="{{ $quotationModel->quote_id }}">
        <input type="hidden" name="quote_status" value="{{ $quotationModel->quote_status }}">
        <input type="hidden" name="quote_payment_status" value="{{ $quotationModel->quote_payment_status }}">
        <input type="hidden" name="quote_tour_code" value="{{ $quotationModel->quote_tour_code }}">
        <input type="hidden" name="quote_booking" value="{{ $quotationModel->quote_booking }}">
        <input type="hidden" name="customer_id" value="{{ $quotationModel->customer_id }}">
        <input type="hidden" name="created_by" value="{{ Auth::user()->name }}">

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
                        <input type="text" id="tour-api-code"  name="quote_tour"  value="{{ $quotationModel->quote_tour ?? '' }}"
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
                        <label class="block text-sm font-medium text-gray-600 mb-1">ระยะเวลาทัวร์ (วัน/คืน)
                          
                        </label>
                        <select name="numday" id="numday" class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            @foreach($numDays as $numDay)
                                {{-- <option value="{{ $numDay->num_day_total }}" {{ $quotationModel->numday == $numDay->num_day_total ? 'selected' : '' }}>
                                    {{ $numDay->num_day_total }} วัน {{ $numDay->num_night_total }} คืน
                                </option> --}}

                             <option data-day="{{ $numDay->num_day_total }}"
                                            value="{{ $numDay->num_day_name }}"
                                            {{ isset($quotationModel) && $quotationModel->quote_numday == $numDay->num_day_name ? 'selected' : '' }}>
                                            {{ $numDay->num_day_name }}</option>

                            @endforeach
                        </select>
                    </div>

                    <!-- ประเทศ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">ประเทศที่เดินทาง</label>
                        <select name="quote_country" class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            @foreach($country as $c)
                                <option value="{{ $c->id }}" {{ $quotationModel->quote_country == $c->id ? 'selected' : '' }}>
                                    {{ $c->country_name_th }}
                                </option>
                                
                            @endforeach
                        </select>
                    </div>

                    <!-- โฮลเซลล์ -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">โฮลเซลล์</label>
                        <select name="quote_wholesale" class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            @foreach($wholesale as $ws)
                                <option value="{{ $ws->id }}" {{ $quotationModel->quote_wholesale == $ws->id ? 'selected' : '' }}>
                                    {{ $ws->code }}-{{ $ws->wholesale_name_th }}
                                </option>

                           
                            @endforeach
                        </select>
                    </div>

                    <!-- สายการบิน -->
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">สายการบิน</label>
                        <select name="quote_airline" class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            @foreach($airline as $air)
                                <option value="{{ $air->id }}" {{ $quotationModel->quote_airline == $air->id ? 'selected' : '' }}>
                                    {{ $air->travel_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">วันออกเดินทาง</label>
                          <a href="javascript:void(0)" class="text-blue-500 hover:underline">Api วันที่เดินทาง</a>
                        <input type="date" id="date-start-display" name="quote_date_start" 
                            class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_start)) }}">
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">วันที่เดินทางกลับ</label>
                        <input type="date" id="date-end-display" name="quote_date_end" 
                            class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_end)) }}">
                    </div>


                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-lg p-4 shadow border border-green-100">
                <span class="text-xs bg-green-100  px-2 py-1 rounded mb-3 inline-block">ข้อมูลลูกค้า</span>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ชื่อลูกค้า</label>
                        <div class="flex gap-2">
                            <input type="text" id="customerSearch" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                                value="{{ $customer->customer_name ?? '' }}" placeholder="ค้นหาชื่อลูกค้า">
                            <button type="button" id="btn-new-customer" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                                ลูกค้าใหม่
                            </button>
                        </div>
                        <div id="customerResults" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow mt-1 hidden"></div>
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">เลขผู้เสียภาษี</label>
                        <input type="text" name="texid" id="texid" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $customer->texid ?? '' }}" placeholder="เลขผู้เสียภาษี">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">อีเมลล์</label>
                        <input type="email" name="customer_email" id="customer_email" 
                            class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $customer->customer_email ?? '' }}" placeholder="อีเมลล์ลูกค้า">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">เบอร์โทรศัพท์</label>
                        <input type="text" name="customer_tel" id="customer_tel" 
                            class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $customer->customer_tel ?? '' }}" placeholder="เบอร์โทรศัพท์">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">เบอร์โทรสาร(Fax)</label>
                        <input type="text" name="fax" id="fax" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $customer->fax ?? '' }}" placeholder="เบอร์โทรสาร(Fax)">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">ลูกค้าจาก</label>
                        <select name="customer_campaign_source" class="w-full border border-gray-300 rounded px-3 py-1 text-sm bg-white">
                            <option value="">-- Select --</option>
                            @foreach($campaignSource as $source)
                                <option value="{{ $source->campaign_source_id }}" 
                                    {{ ($customer->customer_campaign_source ?? '') == $source->campaign_source_id ? 'selected' : '' }}>
                                    {{ $source->campaign_source_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    

                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Social id</label>
                        <input type="text" name="customer_social_id" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $customer->customer_social_id ?? '' }}" placeholder="Social ID">
                    </div>

                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ที่อยู่ลูกค้า</label>
                        <textarea name="customer_address" id="customer_address" rows="2" 
                            class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none"
                            placeholder="ที่อยู่ลูกค้า">{{ $customer->customer_address ?? '' }}</textarea>
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
                    <tbody>
                        @if(isset($quoteProducts) && count($quoteProducts) > 0)
                            @php $rowNum = 1; @endphp
                            @foreach($quoteProducts as $row)
                            <tr class="text-sm text-gray-700">
                                <td class="border px-3 py-2 row-index">{{ $rowNum++ }}</td>
                                <td class="border px-3 py-2">
                                    <select name="product_id[]" class="product-select select2 w-full border border-gray-300 rounded px-2 py-1" style="width:100%">
                                        <option value="">--เลือกสินค้า--</option>
                                        @foreach($products as $product)
                                            <option data-pax="{{ $product->product_pax }}" value="{{ $product->id }}" {{ $row->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <input type="checkbox" name="withholding_tax[]" class="vat-3 w-20 border border-gray-300 rounded px-2 py-1 text-center" value="Y" {{ $row->withholding_tax == 'Y' ? 'checked' : '' }}>
                                    <input type="hidden" name="withholding_tax[]" value="N" {{ $row->withholding_tax == 'Y' ? 'disabled' : '' }}>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <select name="vat_status[]" class="vat-status w-full border border-gray-300 rounded px-2 py-1">
                                        <option value="nonvat" {{ $row->vat_status == 'nonvat' ? 'selected' : '' }}>nonVat</option>
                                        <option value="vat" {{ $row->vat_status == 'vat' ? 'selected' : '' }}>Vat</option>
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="quantity[]" class="quantity w-24 border border-gray-300 rounded px-2 py-1 text-right" value="{{ $row->product_qty ?? 1 }}" min="0" step="any">
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="price_per_unit[]" class="price-per-unit w-24 border border-gray-300 rounded px-2 py-1 text-right" value="{{ $row->product_price ?? 0 }}" min="0" step="any">
                                </td>
                                <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">
                                    <input type="number" name="total_amount[]" class="total-amount w-full border-0 text-end bg-transparent" value="{{ $row->product_sum ?? 0 }}" readonly>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <button type="button" class="text-red-500" onclick="removeRow(this, 'product-rows')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
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

                    <tbody>
                        @if(isset($quoteProductsDiscount) && count($quoteProductsDiscount) > 0)
                            @php $rowNum = 1; @endphp
                            @foreach($quoteProductsDiscount as $row)
                            <tr class="text-sm text-gray-700">
                                <td class="border px-3 py-2 row-index">{{ $rowNum++ }}</td>
                                <td class="border px-3 py-2">
                                    <select name="product_id[]" class="product-select select2 w-full border border-gray-300 rounded px-2 py-1" style="width:100%">
                                        <option value="">--เลือกส่วนลด--</option>
                                        @foreach($productDiscount as $product)
                                            <option value="{{ $product->id }}" {{ $row->product_id == $product->id ? 'selected' : '' }}>{{ $product->product_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <input type="hidden" name="withholding_tax[]" value="N">
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <select name="vat_status[]" class="vat-status w-full border border-gray-300 rounded px-2 py-1" style="width:180%">
                                        <option value="nonvat" {{ $row->vat_status == 'nonvat' ? 'selected' : '' }}>nonVat</option>
                                        <option value="vat" {{ $row->vat_status == 'vat' ? 'selected' : '' }}>Vat</option>
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="quantity[]" class="quantity w-24 border border-gray-300 rounded px-2 py-1 text-right" value="{{ $row->product_qty ?? 1 }}" min="0" step="any">
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="price_per_unit[]" class="price-per-unit w-24 border border-gray-300 rounded px-2 py-1 text-right" value="{{ $row->product_price ?? 0 }}" min="0" step="any">
                                </td>
                                <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">
                                    <input type="number" name="total_amount[]" class="total-amount w-full border-0 text-end bg-transparent" value="{{ $row->product_sum ?? 0 }}" readonly>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <button type="button" class="text-red-500" onclick="removeRow(this, 'discount-rows')"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        @endif

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
                                <input type="radio" id="quote-payment-deposit" name="quote_payment_type" value="deposit"
                                    class="mt-1.5 focus:ring-blue-500" 
                                    {{ $quotationModel->quote_payment_type == 'deposit' ? 'checked' : '' }}>
                                <label for="quote-payment-deposit" class="text-gray-800">
                                    ชำระเงินมัดจำ
                                </label>
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">ภายในวันที่</label>
                                <input type="datetime-local" id="quote-payment-date" name="quote_payment_date"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                                    value="{{ $quotationModel->quote_payment_date ? date('Y-m-d\TH:i', strtotime($quotationModel->quote_payment_date)) : '' }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">เรทเงินมัดจำ</label>
                                <input type="number" id="quote-payment-price" name="quote_payment_price"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end"
                                    value="{{ $quotationModel->quote_payment_price }}" step="0.01">
                            </div>
                        </div>

                        <!-- แถวที่ 2 -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                            <div class="md:col-span-2">
                                <label class="text-gray-800">ชำระเพิ่มเติม</label>
                                <input type="number" id="pay-extra" name="pay_extra"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end"
                                    value="{{ $quotationModel->pay_extra }}" step="0.01">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">จำนวนเงินที่ต้องชำระ</label>
                                <input type="number" id="payment-amount" name="payment_amount" readonly
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end bg-gray-100"
                                    value="0">
                            </div>
                        </div>

                    </div>




                    <!-- กล่อง 2 -->
                    <div class="bg-green-100/50 rounded-lg p-4 shadow mt-1">
                        <span
                            class="text-xs bg-yellow-300 px-2 py-1 rounded mb-3 inline-block">เงื่อนไขการชำระเต็มจำนวน</span>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                            <div class="flex items-start space-x-2">
                                <input type="radio" id="quote-payment-full" name="quote_payment_type" value="full"
                                    class="mt-1.5 focus:ring-blue-500"
                                    {{ $quotationModel->quote_payment_type == 'full' ? 'checked' : '' }}>
                                <label for="quote-payment-full" class="text-gray-800">
                                    ชำระเต็มจำนวน
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3 mt-1">
                            <div class="md:col-span-2">
                                <label class="text-gray-800">ภายในวันที่</label>
                                <input type="datetime-local" id="quote-payment-date-full" name="quote_payment_date_full"
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                                    value="{{ $quotationModel->quote_payment_date_full ? date('Y-m-d\TH:i', strtotime($quotationModel->quote_payment_date_full)) : '' }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-gray-800">จำนวนเงิน</label>
                                <input type="number" id="full-payment-amount" name="full_payment_amount" readonly
                                    class="w-full border border-gray-300 rounded px-3 py-1 text-sm text-end bg-gray-100"
                                    value="{{ $quotationModel->quote_grand_total }}">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3 mt-1">
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">บันทึกเพิ่มเติม</label>
                                <textarea id="payment-note" name="payment_note" rows="2" 
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none"
                                    placeholder="บันทึกเพิ่มเติม">{{ $quotationModel->payment_note }}</textarea>
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
// --- Autocomplete/Search logic for ชื่อลูกค้า ---
$(function() {
    $('#customerSearch').on('input', function() {
        var searchTerm = $(this).val();
        if (searchTerm.length < 2) {
            $('#customerResults').empty().hide();
            return;
        }
        $.ajax({
            url: '{{ route('api.customer') }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(data) {
                $('#customerResults').empty();
                if (data.length > 0) {
                    $.each(data, function(index, item) {
                        $('#customerResults').append(`
                            <a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer" data-id="${item.customer_id}" data-name="${item.customer_name}" data-email="${item.customer_email}" data-taxid="${item.customer_texid}" data-tel="${item.customer_tel}" data-fax="${item.customer_fax}" data-address="${item.customer_address}">${item.customer_name} (${item.customer_email})</a>
                        `);
                    });
                }
                $('#customerResults').show();
            }
        });
    });
    $(document).on('click', '#customerResults a', function(e) {
        e.preventDefault();
        var $a = $(this);
        $('#customerSearch').val($a.data('name'));
        $('#customer_email').val($a.data('email'));
        $('#texid').val($a.data('taxid'));
        $('#customer_tel').val($a.data('tel'));
        $('#fax').val($a.data('fax'));
        $('#customer_address').val($a.data('address'));
        $('#customerResults').empty().hide();
    });
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#customerResults, #customerSearch').length) {
            $('#customerResults').empty().hide();
        }
    });
    $('#btn-new-customer').on('click', function() {
        $('#customerSearch, #customer_email, #texid, #customer_tel, #fax, #customer_address').val('');
    });
});

// --- Autocomplete/Search logic for ชื่อแพคเกจทัวร์ ---
$(function() {
    $('#tourSearch').on('input', function() {
        var searchTerm = $(this).val();
        if (searchTerm.length < 2) {
            $('#tourResults').empty().hide();
            return;
        }
        $.ajax({
            url: '{{ route('api.tours') }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(data) {
                $('#tourResults').empty();
                if (data.length > 0) {
                    var limited = data.slice(0, 5);
                    $.each(limited, function(index, item) {
                        $('#tourResults').append(
                            `<a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer"
                                data-id="${item.id}"
                                data-code="${item.code}"
                                data-name="${item.code} - ${item.code1} - ${item.name}"
                                data-country="${item.country_id || ''}"
                                data-wholesaler="${item.wholesale_id || ''}"
                                data-airline="${item.airline_id || ''}">
                                ${item.code} - ${item.code1} - ${item.name}
                            </a>`
                        );
                    });
                }
                // Add custom option
                $('#tourResults').append(
                    `<a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer" data-name="${searchTerm}">กำหนดเอง</a>`
                );
                $('#tourResults').show();
            }
        });
    });
    $(document).on('click', '#tourResults a', function(e) {
        e.preventDefault();
        var $a = $(this);
        $('#tourSearch').val($a.data('name'));
        $('#tour-id').val($a.data('id'));
        $('#tour-code').val($a.data('code'));
        $('#tour-country').val($a.data('country'));
        $('#tour-wholesaler').val($a.data('wholesaler'));
        $('#tour-airline').val($a.data('airline'));
        $('#tour-api-code').val($a.data('code'));
        $('#tourResults').empty().hide();
    });
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#tourResults, #tourSearch').length) {
            $('#tourResults').empty().hide();
        }
    });
    $('#resetTourSearch').on('click', function() {
        $('#tourSearch, #tour-id, #tour-code, #tour-country, #tour-wholesaler, #tour-airline,#tour-api-code').val('');
        $('#tourResults').empty().hide();
    });
});

// --- คำนวณยอดและ sync เงื่อนไขชำระเงิน ---
function calculateQuotation() {
    let vatType = document.querySelector('input[name="vat_option"]:checked')?.value || 'include';
    let vatRate = 0.07;
    let sumTotalNonVat = 0, sumTotalVat = 0, sumDiscount = 0, sumPreVat = 0, sumVat = 0, sumIncludeVat = 0, grandTotal = 0, withholdingAmount = 0, paxTotal = 0;
    // Loop รายการบริการและส่วนลด
    document.querySelectorAll('tr.text-sm').forEach(row => {
        let selectProduct = row.querySelector('select[name="product_id[]"]');
        if (!selectProduct) return;
        let isDiscount = row.closest('tbody').previousElementSibling && row.closest('tbody').previousElementSibling.innerText.includes('รายการส่วนลด');
        let qty = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);
        let price = parseFloat(row.querySelector('input[name="price_per_unit[]"]')?.value || 0);
        let vatStatus = row.querySelector('select[name="vat_status[]"]')?.value || 'nonvat';
        let isWithholding = row.querySelector('input.vat-3')?.checked;
        let rowTotal = qty * price;
        if (isWithholding) rowTotal += rowTotal * 0.03;
        let sumCell = row.querySelector('input[name="total_amount[]"]');
        if (sumCell) sumCell.value = rowTotal.toFixed(2);
        if (isDiscount) {
            sumDiscount += rowTotal;
        } else {
            if (vatStatus === 'vat') {
                sumTotalVat += rowTotal;
            } else {
                sumTotalNonVat += rowTotal;
            }
            let selectedOption = selectProduct.options[selectProduct.selectedIndex];
            if (selectedOption && selectedOption.getAttribute('data-pax') === 'Y') {
                paxTotal += qty;
            }
        }
    });
    // VAT Calculation
    if (sumTotalVat === 0) {
        sumPreVat = 0;
        sumVat = 0;
        sumIncludeVat = 0;
        grandTotal = sumTotalNonVat - sumDiscount;
    } else {
        if (vatType === 'include') {
            let vatBase = sumTotalVat - sumDiscount;
            sumPreVat = vatBase * 100 / 107;
            sumVat = sumPreVat * vatRate;
            sumIncludeVat = sumPreVat + sumVat;
            grandTotal = sumTotalNonVat + sumIncludeVat;
        } else {
            if (sumDiscount < sumTotalVat) {
                sumPreVat = sumTotalVat - sumDiscount;
                sumVat = sumPreVat * vatRate;
                sumIncludeVat = sumPreVat + sumVat;
                grandTotal = sumTotalNonVat + sumIncludeVat;
            } else {
                sumPreVat = 0;
                sumVat = 0;
                sumIncludeVat = 0;
                grandTotal = sumTotalNonVat;
            }
        }
    }
    // withholding tax 3%
    let withholdingCheckbox = document.getElementById('pre-vat');
    if (withholdingCheckbox && withholdingCheckbox.checked) {
        withholdingAmount = sumPreVat * 0.03;
    }
    document.querySelector('[data-summary="vatExempted"]').textContent = sumTotalNonVat.toFixed(2);
    document.querySelector('[data-summary="preTax"]').textContent = sumTotalVat.toFixed(2);
    document.querySelector('[data-summary="discount"]').textContent = sumDiscount.toFixed(2);
    document.querySelector('[data-summary="preVatAmount"]').textContent = sumPreVat.toFixed(2);
    document.querySelector('[data-summary="vatAmount"]').textContent = sumVat.toFixed(2);
    document.querySelector('[data-summary="includeVat"]').textContent = sumIncludeVat.toFixed(2);
    document.querySelector('[data-summary="grandTotal"]').textContent = grandTotal.toFixed(2);
    document.getElementById('withholdingTax').textContent = withholdingAmount.toFixed(2);
    if (document.getElementById('quote-pax-total')) document.getElementById('quote-pax-total').value = paxTotal;
    // Sync hidden fields for backend
    document.getElementById('quote_vat_exempted_amount').value = sumTotalNonVat.toFixed(2);
    document.getElementById('quote_pre_tax_amount').value = sumTotalVat.toFixed(2);
    document.getElementById('quote_discount').value = sumDiscount.toFixed(2);
    document.getElementById('quote_pre_vat_amount').value = sumPreVat.toFixed(2);
    document.getElementById('quote_vat').value = sumVat.toFixed(2);
    document.getElementById('quote_include_vat').value = sumIncludeVat.toFixed(2);
    document.getElementById('quote_grand_total').value = grandTotal.toFixed(2);
    document.getElementById('quote_withholding_tax').value = withholdingAmount.toFixed(2);
    syncDepositAndFullPayment();
// --- อัปเดตเลขลำดับ row อัตโนมัติ ---
function updateRowNumbers() {
    document.querySelectorAll('#product-rows tr, #discount-rows tr').forEach((row, i) => {
        let idxCell = row.querySelector('.row-index');
        if (idxCell) idxCell.textContent = i + 1;
    });
}

// เรียกทุกครั้งที่เพิ่ม/ลบ row
const origAddProductRow = addProductRow;
addProductRow = function() {
    origAddProductRow();
    updateRowNumbers();
}
const origAddDiscountRow = addDiscountRow;
addDiscountRow = function() {
    origAddDiscountRow();
    updateRowNumbers();
}
const origRemoveRow = removeRow;
removeRow = function(btn, tbodyId) {
    origRemoveRow(btn, tbodyId);
    updateRowNumbers();
}
    // ป้องกัน submit form เมื่อกด Enter ในช่องค้นหาแพคเกจทัวร์
    $('#tourSearch').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            let $first = $('#tourResults a').first();
            if ($first.length) $first.trigger('click');
        }
    });
}

function syncDepositAndFullPayment() {
    let paxTotal = parseFloat($('#quote-pax-total').val()) || 0;
    let depositPerPax = parseFloat($('#quote-payment-price').val()) || 0;
    let payExtra = parseFloat($('#pay-extra').val()) || 0;
    let depositTotal = (paxTotal * depositPerPax) + payExtra;
    if ($('#quote-payment-deposit').is(':checked')) {
        $('#payment-amount').val(depositTotal.toFixed(2));
    }
    let grandTotal = parseFloat($('[data-summary="grandTotal"]').text()) || 0;
    $('#full-payment-amount').val(grandTotal.toFixed(2));
}

// --- คำนวณวันออกเดินทาง/วันกลับ/ระยะเวลาทัวร์ ---
$('#date-start-display').on('change', function() {
    let startVal = $(this).val();
    let numDays = parseInt($('#numday').val()) || 0;
    if (startVal && numDays > 0) {
        let startDate = new Date(startVal);
        let endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + numDays - 1);
        $('#date-end-display').val(endDate.toISOString().slice(0, 10));
    }
});
$('#date-end-display').on('change', function() {
    let endVal = $(this).val();
    let numDays = parseInt($('#numday').val()) || 0;
    if (endVal && numDays > 0) {
        let endDate = new Date(endVal);
        let startDate = new Date(endDate);
        startDate.setDate(endDate.getDate() - numDays + 1);
        $('#date-start-display').val(startDate.toISOString().slice(0, 10));
    }
});
$('#numday').on('change', function() {
    let startVal = $('#date-start-display').val();
    let numDays = parseInt($(this).val()) || 0;
    if (startVal && numDays > 0) {
        let startDate = new Date(startVal);
        let endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + numDays - 1);
        $('#date-end-display').val(endDate.toISOString().slice(0, 10));
    }
});

// --- Event binding ทุก input/select ---
$(document).on('input change', '.quantity, .price-per-unit, .vat-status, .vat-3', function() {
    calculateQuotation();
});
$(document).on('change', 'input[name="vat_option"]', function() {
    calculateQuotation();
});
$('#pre-vat').on('change', function() {
    calculateQuotation();
});
$('#quote-payment-deposit, #quote-payment-full').on('change', function() {
    let isDeposit = $('#quote-payment-deposit').is(':checked');
    $('#quote-payment-price').prop('disabled', !isDeposit);
    $('#pay-extra').prop('disabled', !isDeposit);
    syncDepositAndFullPayment();
});
$('#quote-payment-price, #pay-extra, #quote-pax-total').on('input', function() {
    syncDepositAndFullPayment();
});

// --- คำนวณยอดทันทีเมื่อโหลดหน้า ---
$(document).ready(function() {
    calculateQuotation();
});
    function calculateQuotation() {
        let vatType = document.querySelector('input[name="vat_option"]:checked').value;
        let vatRate = 0.07;
        let productRows = document.querySelectorAll('tbody tr');
        let sumTotalNonVat = 0, sumTotalVat = 0, sumDiscount = 0, sumPreVat = 0, sumVat = 0, sumIncludeVat = 0, grandTotal = 0, withholdingAmount = 0, paxTotal = 0;
        // Loop รายการบริการ
        productRows.forEach(row => {
            // เฉพาะ row ที่มี select[name="product_id[]"] (บริการ) หรือ select[name="product_id[]"] (ส่วนลด)
            let selectProduct = row.querySelector('select[name="product_id[]"]');
            if (!selectProduct) return;
            let isDiscount = row.closest('tbody').previousElementSibling && row.closest('tbody').previousElementSibling.innerText.includes('รายการส่วนลด');
            let qty = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);
            let price = parseFloat(row.querySelector('input[name="price_per_unit[]"]')?.value || 0);
            let vatStatus = row.querySelector('select[name="vat_status[]"]')?.value || 'nonvat';
            let isWithholding = row.querySelector('input.vat-3')?.checked;
            let rowTotal = qty * price;
            if (isWithholding) rowTotal += rowTotal * 0.03;
            // อัปเดตยอดรวมในช่อง sum
            let sumCell = row.querySelector('input[name="total_amount[]"]');
            if (sumCell) sumCell.value = rowTotal.toFixed(2);
            if (isDiscount) {
                sumDiscount += rowTotal;
            } else {
                if (vatStatus === 'vat') {
                    sumTotalVat += rowTotal;
                } else {
                    sumTotalNonVat += rowTotal;
                }
                // ถ้าเป็นสินค้าแบบ pax
                let selectedOption = selectProduct.options[selectProduct.selectedIndex];
                if (selectedOption && selectedOption.getAttribute('data-pax') === 'Y') {
                    paxTotal += qty;
                }
            }
        });
        // VAT Calculation
        if (sumTotalVat === 0) {
            sumPreVat = 0;
            sumVat = 0;
            sumIncludeVat = 0;
            grandTotal = sumTotalNonVat - sumDiscount;
        } else {
            if (vatType === 'include') {
                let vatBase = sumTotalVat - sumDiscount;
                sumPreVat = vatBase * 100 / 107;
                sumVat = sumPreVat * vatRate;
                sumIncludeVat = sumPreVat + sumVat;
                grandTotal = sumTotalNonVat + sumIncludeVat;
            } else {
                if (sumDiscount < sumTotalVat) {
                    sumPreVat = sumTotalVat - sumDiscount;
                    sumVat = sumPreVat * vatRate;
                    sumIncludeVat = sumPreVat + sumVat;
                    grandTotal = sumTotalNonVat + sumIncludeVat;
                } else {
                    sumPreVat = 0;
                    sumVat = 0;
                    sumIncludeVat = 0;
                    grandTotal = sumTotalNonVat;
                }
            }
        }
        // withholding tax 3%
        let withholdingCheckbox = document.getElementById('pre-vat');
        if (withholdingCheckbox && withholdingCheckbox.checked) {
            withholdingAmount = sumPreVat * 0.03;
        }
        // set ค่า summary
        document.querySelector('[data-summary="vatExempted"]').textContent = sumTotalNonVat.toFixed(2);
        document.querySelector('[data-summary="preTax"]').textContent = sumTotalVat.toFixed(2);
        document.querySelector('[data-summary="discount"]').textContent = sumDiscount.toFixed(2);
        document.querySelector('[data-summary="preVatAmount"]').textContent = sumPreVat.toFixed(2);
        document.querySelector('[data-summary="vatAmount"]').textContent = sumVat.toFixed(2);
        document.querySelector('[data-summary="includeVat"]').textContent = sumIncludeVat.toFixed(2);
        document.querySelector('[data-summary="grandTotal"]').textContent = grandTotal.toFixed(2);
        document.getElementById('withholdingTax').textContent = withholdingAmount.toFixed(2);
        // set pax
        if (document.getElementById('quote-pax-total')) document.getElementById('quote-pax-total').value = paxTotal;
    }

    function addProductRow() {
        let tbody = document.getElementById('product-rows');
        let idx = tbody.children.length + 1;
        let tr = document.createElement('tr');
        tr.className = 'text-sm text-gray-700';
        tr.innerHTML = `
        <td class="border px-3 py-2 row-index">${idx}</td>
        <td class="border px-3 py-2">
            <select name="product_id[]" class="product-select select2 w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                <option value="">-- เลือกรายการ --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-vat="{{ $product->vat_type }}">
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>
        </td>
        <td class="border px-3 py-2 text-center">
            <input type="checkbox" name="wht_3[]" class="w-20 border border-gray-300 rounded px-2 py-1 text-center" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-center">
            <select name="vat_type[]" class="w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
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

    // ฟังก์ชันคำนวณยอดเงินมัดจำและ sync ช่องชำระเต็มจำนวน
    function syncDepositAndFullPayment() {
        let paxTotal = parseFloat($('#quote-pax-total').val()) || 0;
        let depositPerPax = parseFloat($('#quote-payment-price').val()) || 0;
        let payExtra = parseFloat($('#pay-extra').val()) || 0;
        let depositTotal = (paxTotal * depositPerPax) + payExtra;
        
        if ($('#quote-payment-deposit').is(':checked')) {
            $('#payment-amount').val(depositTotal.toFixed(2));
        }
        
        // อัปเดตยอดชำระเต็มจำนวน
        let grandTotal = parseFloat($('[data-summary="grandTotal"]').text()) || 0;
        $('#full-payment-amount').val(grandTotal.toFixed(2));
    }

    // Event handlers สำหรับการชำระเงิน
    $('#quote-payment-deposit, #quote-payment-full').on('change', function() {
        let isDeposit = $('#quote-payment-deposit').is(':checked');
        $('#quote-payment-price').prop('disabled', !isDeposit);
        $('#pay-extra').prop('disabled', !isDeposit);
        syncDepositAndFullPayment();
    });

    $('#quote-payment-price, #pay-extra, #quote-pax-total').on('input', function() {
        syncDepositAndFullPayment();
    });

    window.addEventListener('DOMContentLoaded', () => {
        // Convert PHP data to JSON
        const quoteProducts = @json($quoteProducts ?? []);
        const quoteProductsDiscount = @json($quoteProductsDiscount ?? []);

        // Load existing product rows
        if (quoteProducts && quoteProducts.length > 0) {
            quoteProducts.forEach(product => {
                addProductRowWithData({
                    name: product.product_name,
                    wht3: Boolean(product.wht_3),
                    vatType: product.vat_type,
                    quantity: parseFloat(product.quantity),
                    price: parseFloat(product.price_per_unit),
                    productId: parseInt(product.product_id)
                });
            });
        }

        // Load existing discount rows
        if (quoteProductsDiscount && quoteProductsDiscount.length > 0) {
            quoteProductsDiscount.forEach(discount => {
                addDiscountRowWithData({
                    name: discount.product_name,
                    wht3: Boolean(discount.wht_3),
                    quantity: parseFloat(discount.quantity),
                    price: parseFloat(discount.price_per_unit)
                });
            });
        }

        // If no rows exist, add empty ones
        if (!document.querySelector('#product-rows tr')) {
            addProductRow();
        }
        if (!document.querySelector('#discount-rows tr')) {
            addDiscountRow();
        }

        // Set initial values
        document.querySelector('input[name="vat_option"][value="{{ $quotationModel->vat_type }}"]').checked = true;
        document.querySelector('input[name="quote_commission"][value="{{ $quotationModel->quote_commission }}"]').checked = true;
        document.getElementById('note-commission').value = '{{ $quotationModel->quote_note_commission }}';
        document.getElementById('withholding-tax').checked = {{ $quotationModel->withholding_tax ? 'true' : 'false' }};
        
        // Calculate initial totals
        calculateQuotation();
    });

    function addProductRowWithData(data) {
        let tbody = document.getElementById('product-rows');
        let idx = tbody.children.length + 1;
        let tr = document.createElement('tr');
        tr.className = 'text-sm text-gray-700';
        tr.innerHTML = `
        <td class="border px-3 py-2 row-index">${idx}</td>
        <td class="border px-3 py-2">
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" value="${data.name}" onchange="calculateQuotation()">
            <input type="hidden" name="product_id[]" value="${data.productId}">
        </td>
        <td class="border px-3 py-2 text-center">
            <input type="checkbox" class="w-20 border border-gray-300 rounded px-2 py-1 text-center" ${data.wht3 ? 'checked' : ''} onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-center">
            <select class="w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                <option value="N" ${data.vatType === 'N' ? 'selected' : ''}>NonVat</option>
                <option value="Y" ${data.vatType === 'Y' ? 'selected' : ''}>Vat</option>
            </select>
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right qty" value="${data.quantity}" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right price" value="${data.price}" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">0.00</td>
        <td class="border px-3 py-2 text-center">
            <button type="button" class="text-red-500" onclick="removeRow(this, 'product-rows')"><i class="fas fa-trash"></i></button>
        </td>
    `;
        tbody.appendChild(tr);
        calculateQuotation();
    }

    function addDiscountRowWithData(data) {
        let tbody = document.getElementById('discount-rows');
        let idx = tbody.children.length + 1;
        let tr = document.createElement('tr');
        tr.className = 'text-sm text-gray-700';
        tr.innerHTML = `
        <td class="border px-3 py-2 row-index">${idx}</td>
        <td class="border px-3 py-2">
            <input type="text" class="w-full border border-gray-300 rounded px-2 py-1" value="${data.name}" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-center">
            <input type="checkbox" class="w-20 border border-gray-300 rounded px-2 py-1 text-center" ${data.wht3 ? 'checked' : ''} onchange="calculateQuotation()">
            <input type="hidden" value="N">
        </td>
        <td class="border px-3 py-2 text-center">
            <select class="w-full border border-gray-300 rounded px-2 py-1" disabled><option value="N">NonVat</option></select>
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right qty" value="${data.quantity}" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right">
            <input type="number" class="w-24 border border-gray-300 rounded px-2 py-1 text-right price" value="${data.price}" min="0" step="any" onchange="calculateQuotation()">
        </td>
        <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">0.00</td>
        <td class="border px-3 py-2 text-center">
            <button type="button" class="text-red-500" onclick="removeRow(this, 'discount-rows')"><i class="fas fa-trash"></i></button>
        </td>
    `;
        tbody.appendChild(tr);
        calculateQuotation();
    }
</script>


