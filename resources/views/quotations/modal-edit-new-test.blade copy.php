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
        <input type="hidden" name="quote_sale" value="{{ Auth::user()->id }}">
        <input type="hidden" name="quote_booking_create" value="{{ $quotationModel->quote_booking_create ?? date('Y-m-d') }}">
        <input type="hidden" name="quote_date" value="{{ $quotationModel->quote_date ?? date('Y-m-d') }}">

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
                            <input type="text" id="tourSearch" name="quote_tour_name" autocomplete="off"
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
                        <input type="text" id="tour-code" name="quote_tour_code" class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ $quotationModel->quote_tour_code ?? '' }}" placeholder="รหัสทัวร์ กำหนดเอง">
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
                        <div class="relative">
                            <a href="javascript:void(0)" id="list-period" class="text-blue-500 hover:underline text-xs">Api วันที่เดินทาง</a>
                            <input type="date" id="date-start-display" name="quote_date_start" 
                                class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                                value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_start)) }}">
                            <div id="date-list" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow mt-1 hidden max-h-48 overflow-y-auto"></div>
                            <input type="hidden" id="date-start" name="quote_date_start" value="{{ $quotationModel->quote_date_start ?? '' }}">
                            <input type="hidden" id="period1" name="period1">
                            <input type="hidden" id="period2" name="period2">
                            <input type="hidden" id="period3" name="period3">
                            <input type="hidden" id="period4" name="period4">
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-600 mb-1">วันที่เดินทางกลับ</label>
                        <input type="date" id="date-end-display" name="quote_date_end" 
                            class="w-full border border-gray-300 rounded px-3 py-1 text-sm"
                            value="{{ date('Y-m-d', strtotime($quotationModel->quote_date_end)) }}">
                        <input type="hidden" id="date-end" name="quote_date_end" value="{{ $quotationModel->quote_date_end ?? '' }}">
                    </div>


                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white rounded-lg p-4 shadow border border-green-100">
                <span class="text-xs bg-green-100  px-2 py-1 rounded mb-3 inline-block">ข้อมูลลูกค้า</span>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-4 gap-y-3">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">ชื่อลูกค้า</label>
                        <div class="flex gap-2 relative">
                            <input type="text" name="customer_name" id="customerSearch" 
                                class="w-full border border-gray-300 rounded px-3 py-1 text-sm" 
                                placeholder="ชื่อลูกค้า...ENTER เพื่อค้นหา" 
                                value="{{ $customer->customer_name ?? '' }}" autocomplete="off" required>
                            <button type="button" id="btn-new-customer" class="px-2 py-1 bg-blue-500 text-white rounded text-xs">
                                ลูกค้าใหม่
                            </button>
                        </div>
                        <div id="customerResults" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow mt-1 hidden"></div>
                        <input type="hidden" id="customer-id" name="customer_id" value="{{ $customer->customer_id ?? '' }}">
                        <input type="hidden" id="customer-new" name="customer_type_new" value="{{ isset($customer) ? 'customerOld' : 'customerNew' }}">
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

                            <input type="radio" id="commission-yes" name="quote_commission" value="Y"
                class="mt-1.5 focus:ring-blue-500" 
                {{ isset($quotationModel) && $quotationModel->quote_commission == 'Y' ? 'checked' : '' }}>
            <label for="commission-yes" class="text-gray-800">
                จ่ายค่าคอมมิชชั่น
            </label>
            <input type="radio" id="commission-no" name="quote_commission" value="N"
                class="mt-1.5 focus:ring-blue-500 ml-4"
                {{ isset($quotationModel) && $quotationModel->quote_commission == 'N' ? 'checked' : '' }}>
            <label for="commission-no" class="text-gray-800">
                ไม่จ่ายค่าคอมมิชชั่น
            </label>

            <div id="note-commission-block" class="mt-3" style="{{ isset($quotationModel) && $quotationModel->quote_commission == 'N' ? '' : 'display: none;' }}">
                <label class="block text-sm font-medium text-gray-600 mb-1">บันทึกเหตุผลกรณีไม่จ่ายค่าคอมมิชชั่น</label>
                <textarea name="quote_note_commission" id="note-commission" rows="2" 
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none"
                    placeholder="บันทึกเหตุผล">{{ $quotationModel->quote_note_commission ?? '' }}</textarea>
            </div>

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
                    <tbody id="product-rows">
                        @if(isset($quoteProducts) && count($quoteProducts) > 0)
                            @php $rowNum = 1; @endphp
                            @foreach($quoteProducts as $row)
                            <tr class="text-sm text-gray-700">
                                <td class="border px-3 py-2 row-index">{{ $rowNum++ }}</td>
                                <td class="border px-3 py-2">
                                    <select name="product_id[]" class="product-select w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                                        <option value="">--เลือกสินค้า--</option>
                                        @foreach($products as $product)
                                            <option data-pax="{{ $product->product_pax }}" value="{{ $product->id }}" {{ $row->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}{{ $product->product_pax === 'Y' ? '(Pax)' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <input type="checkbox" name="withholding_tax[]" class="vat-3 w-20 border border-gray-300 rounded px-2 py-1 text-center" value="Y" {{ $row->withholding_tax == 'Y' ? 'checked' : '' }} onchange="calculateQuotation()">
                                    <input type="hidden" name="withholding_tax[]" value="N" {{ $row->withholding_tax == 'Y' ? 'disabled' : '' }}>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <select name="vat_status[]" class="vat-status w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                                        <option value="nonvat" {{ $row->vat_status == 'nonvat' ? 'selected' : '' }}>nonVat</option>
                                        <option value="vat" {{ $row->vat_status == 'vat' ? 'selected' : '' }}>Vat</option>
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="quantity[]" class="quantity w-24 border border-gray-300 rounded px-2 py-1 text-right" value="{{ $row->product_qty ?? 1 }}" min="0" step="any" onchange="calculateQuotation()">
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="price_per_unit[]" class="price-per-unit w-24 border border-gray-300 rounded px-2 py-1 text-right" value="{{ $row->product_price ?? 0 }}" min="0" step="any" onchange="calculateQuotation()">
                                </td>
                                <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">
                                    <input type="number" name="total_amount[]" class="total-amount w-full border-0 text-end bg-transparent" value="{{ $row->product_sum ?? 0 }}" readonly>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <button type="button" class="text-red-500 remove-row-btn" onclick="removeRow(this, 'product-rows')"><i class="fas fa-trash"></i></button>
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

                    <tbody id="discount-rows">
                        @if(isset($quoteProductsDiscount) && count($quoteProductsDiscount) > 0)
                            @php $rowNum = 1; @endphp
                            @foreach($quoteProductsDiscount as $row)
                            <tr class="text-sm text-gray-700">
                                <td class="border px-3 py-2 row-index">{{ $rowNum++ }}</td>
                                <td class="border px-3 py-2">
                                    <select name="product_id[]" class="product-select w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                                        <option value="">--เลือกส่วนลด--</option>
                                        @foreach($productDiscount as $product)
                                            <option value="{{ $product->id }}" {{ $row->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->product_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <input type="hidden" name="withholding_tax[]" value="N">
                                    <span class="text-gray-400">-</span>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <select name="vat_status[]" class="vat-status w-full border border-gray-300 rounded px-2 py-1" onchange="calculateQuotation()">
                                        <option value="nonvat" {{ $row->vat_status == 'nonvat' ? 'selected' : '' }}>nonVat</option>
                                        <option value="vat" {{ $row->vat_status == 'vat' ? 'selected' : '' }}>Vat</option>
                                    </select>
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="quantity[]" class="quantity w-24 border border-gray-300 rounded px-2 py-1 text-right" 
                                        value="{{ $row->product_qty ?? 1 }}" min="0" step="any" onchange="calculateQuotation()">
                                </td>
                                <td class="border px-3 py-2 text-right">
                                    <input type="number" name="price_per_unit[]" class="price-per-unit w-24 border border-gray-300 rounded px-2 py-1 text-right" 
                                        value="{{ $row->product_price ?? 0 }}" min="0" step="any" onchange="calculateQuotation()">
                                </td>
                                <td class="border px-3 py-2 text-right text-gray-800 font-medium sum">
                                    <input type="number" name="total_amount[]" class="total-amount w-full border-0 text-end bg-transparent" 
                                        value="{{ $row->product_sum ?? 0 }}" readonly>
                                </td>
                                <td class="border px-3 py-2 text-center">
                                    <button type="button" class="text-red-500 remove-row-btn" onclick="removeRow(this, 'discount-rows')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        @endif

                    <tbody>
                        <tr>
                            <td colspan="8">
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
                                <input type="checkbox" id="pre-vat" name="quote_withholding_tax_status" value="Y"
                                    class="mt-1.5 focus:ring-blue-500" 
                                    {{ $quotationModel->quote_withholding_tax_status == 'Y' ? 'checked' : '' }}
                                    onchange="calculateQuotation()">
                                <label for="pre-vat" class="text-gray-800">
                                    คิดภาษีหัก ณ ที่จ่าย 3% (Withholding Tax)
                                </label>
                            </div>
                            <div class="flex items-start space-x-2">
                                <label for="vat_exclude" class="text-gray-800">
                                    จำนวนเงินภาษีหัก ณ ที่จ่าย 3% : <span id="withholdingTax">0.00</span> บาท
                                </label>
                            </div>
                            <hr class="my-3">
                            <div class="mt-3">
                                <label class="block text-sm font-medium text-gray-600 mb-1">บันทึกเพิ่มเติม</label>
                                <textarea name="quote_note" rows="2" 
                                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm resize-none"
                                    placeholder="บันทึกเพิ่มเติม">{{ $quotationModel->quote_note ?? '' }}</textarea>
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



                        <input type="hidden" name="quote_pax_total" id="quote-pax-total" value="0">
                        <input type="hidden" id="booking-create-date" value="{{ date('Y-m-d') }}">
                        <div class="text-end mt-3">
                            <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded shadow" data-bs-dismiss="modal">
                                ปิด
                            </button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow ml-2">
                                <i class="fas fa-save"></i> บันทึกการเปลี่ยนแปลง
                            </button>
                        </div>
                    </form>
                </div>

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<!-- Perfect Scrollbar -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/css/perfect-scrollbar.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.5/perfect-scrollbar.min.js"></script>

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
                            <a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer" 
                               data-id="${item.customer_id}" 
                               data-name="${item.customer_name}" 
                               data-email="${item.customer_email}" 
                               data-taxid="${item.customer_texid}" 
                               data-tel="${item.customer_tel}" 
                               data-fax="${item.customer_fax}" 
                               data-address="${item.customer_address}">
                               ${item.customer_name} (${item.customer_email})
                            </a>
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
        $('#customer-id').val($a.data('id'));
        $('#customer_email').val($a.data('email'));
        $('#texid').val($a.data('taxid'));
        $('#customer_tel').val($a.data('tel'));
        $('#fax').val($a.data('fax'));
        $('#customer_address').val($a.data('address'));
        $('#customer-new').val('customerOld');
        $('#customerResults').empty().hide();
    });
    
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#customerResults, #customerSearch').length) {
            $('#customerResults').empty().hide();
        }
    });
    
    $('#btn-new-customer').on('click', function() {
        $('#customerSearch, #customer_email, #texid, #customer_tel, #fax, #customer_address').val('');
        $('#customer-id').val('');
        $('#customer-new').val('customerNew');
    });
});

// --- Autocomplete/Search logic for ชื่อแพคเกจทัวร์ ---
$(function() {
    // เพิ่ม debug เพื่อดูว่า elements มีอยู่หรือไม่
    console.log('Tour search initialization started');
    console.log('tourSearch element:', $('#tourSearch').length);
    console.log('tourResults element:', $('#tourResults').length);
    
    $('#tourSearch').on('input', function() {
        var searchTerm = $(this).val();
        console.log('Search term:', searchTerm);
        if (searchTerm.length < 2) {
            $('#tourResults').empty().hide();
            return;
        }
        $.ajax({
            url: '{{ route('api.tours') }}',
            method: 'GET',
            data: { search: searchTerm },
            success: function(data) {
                console.log('API Response:', data);
                // เก็บข้อมูล API response สำหรับ debugging
                window.lastTourApiResponse = data;
                
                $('#tourResults').empty();
                if (data.length > 0) {
                    var limited = data.slice(0, 5);
                    $.each(limited, function(index, item) {
                        console.log('Tour item:', item);
                        console.log('  - ID:', item.id);
                        console.log('  - Code:', item.code);
                        console.log('  - Country ID (raw):', item.country_id, 'Type:', typeof item.country_id, 'Is Array:', Array.isArray(item.country_id));
                        console.log('  - Wholesale ID (raw):', item.wholesale_id, 'Type:', typeof item.wholesale_id, 'Is Array:', Array.isArray(item.wholesale_id));
                        console.log('  - Airline ID (raw):', item.airline_id, 'Type:', typeof item.airline_id, 'Is Array:', Array.isArray(item.airline_id));
                        
                        // Clean the data
                        var cleanCountryId = Array.isArray(item.country_id) ? item.country_id[0] : item.country_id;
                        var cleanWholesaleId = Array.isArray(item.wholesale_id) ? item.wholesale_id[0] : item.wholesale_id;
                        var cleanAirlineId = Array.isArray(item.airline_id) ? item.airline_id[0] : item.airline_id;
                        
                        console.log('  - Country ID (clean):', cleanCountryId);
                        console.log('  - Wholesale ID (clean):', cleanWholesaleId);
                        console.log('  - Airline ID (clean):', cleanAirlineId);
                        $('#tourResults').append(
                            `<a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer"
                                data-id="${item.id || ''}"
                                data-code="${item.code || ''}"
                                data-name="${item.code} - ${item.code1} - ${item.name}"
                                data-country="${Array.isArray(item.country_id) ? item.country_id[0] : (item.country_id || '')}"
                                data-wholesaler="${Array.isArray(item.wholesale_id) ? item.wholesale_id[0] : (item.wholesale_id || '')}"
                                data-airline="${Array.isArray(item.airline_id) ? item.airline_id[0] : (item.airline_id || '')}">
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
                console.log('Tour results displayed, count:', $('#tourResults a').length);
            },
            error: function(xhr, status, error) {
                console.error('Tour API Error:', error);
                console.error('Response:', xhr.responseText);
            }
        });
    });
    
    $(document).on('click', '#tourResults a', function(e) {
        e.preventDefault();
        var $a = $(this);
        console.log('=== TOUR SELECTION STARTED ===');
        console.log('Tour selected data:', $a.data());
        console.log('Element HTML:', $a[0].outerHTML);
        console.log('All data attributes:');
        $.each($a.data(), function(key, value) {
            console.log('  ' + key + ':', value, typeof value);
        });
        
        // Set basic tour info
        $('#tourSearch').val($a.data('name'));
        $('#tour-id').val($a.data('id'));
        $('#tour-code').val($a.data('code'));
        
        // Get values from clicked element and clean them
        var countryVal = window.cleanDataValue($a.data('country'));
        var wholesaleVal = window.cleanDataValue($a.data('wholesaler'));
        var airlineVal = window.cleanDataValue($a.data('airline'));
        
        console.log('Values to set (after cleaning) - Country:', countryVal, 'Wholesale:', wholesaleVal, 'Airline:', airlineVal);
        console.log('Country value type:', typeof countryVal, 'Is empty?', !countryVal);
        console.log('Wholesale value type:', typeof wholesaleVal, 'Is empty?', !wholesaleVal);
        console.log('Airline value type:', typeof airlineVal, 'Is empty?', !airlineVal);
        
        // First, let's check what select elements we have
        var $countrySelect = $('select[name="quote_country"]');
        var $wholesaleSelect = $('select[name="quote_wholesale"]');
        var $airlineSelect = $('select[name="quote_airline"]');
        
        console.log('Select elements found:');
        console.log('  Country select:', $countrySelect.length);
        console.log('  Wholesale select:', $wholesaleSelect.length);
        console.log('  Airline select:', $airlineSelect.length);
        
        // Debug: แสดง options ทั้งหมดใน select
        console.log('=== AVAILABLE OPTIONS ===');
        console.log('Country options:');
        $countrySelect.find('option').each(function() {
            console.log('  Value:', $(this).val(), 'Text:', $(this).text().trim());
        });
        
        console.log('Wholesale options:');
        $wholesaleSelect.find('option').each(function() {
            console.log('  Value:', $(this).val(), 'Text:', $(this).text().trim());
        });
        
        console.log('Airline options:');
        $airlineSelect.find('option').each(function() {
            console.log('  Value:', $(this).val(), 'Text:', $(this).text().trim());
        });
        
        // Now try to set values
        console.log('=== SETTING VALUES ===');
        
        // Set Country
        if (countryVal && countryVal !== '') {
            console.log('Setting country value:', countryVal);
            var $countryOption = $countrySelect.find('option[value="' + countryVal + '"]');
            console.log('Country option found:', $countryOption.length, 'Text:', $countryOption.text());
            
            // Try to find by different methods
            console.log('Trying different search methods for country:');
            console.log('  Exact match:', $countrySelect.find('option[value="' + countryVal + '"]').length);
            console.log('  Loose match:', $countrySelect.find('option').filter(function() {
                return $(this).val() == countryVal;
            }).length);
            
            if ($countryOption.length > 0) {
                $countrySelect.val(countryVal);
                console.log('✅ Country select after set:', $countrySelect.val());
                // Highlight the change
                $countrySelect.css('background-color', '#e6f3ff');
                setTimeout(() => $countrySelect.css('background-color', ''), 2000);
            } else {
                console.log('❌ Country option not found for value:', countryVal);
                console.log('Available country values:', $countrySelect.find('option').map(function() {
                    return $(this).val();
                }).get());
            }
        } else {
            console.log('⚠️ Country value is empty or null:', countryVal);
        }
        
        // Set Wholesale
        if (wholesaleVal) {
            console.log('Setting wholesale value:', wholesaleVal);
            var $wholesaleOption = $wholesaleSelect.find('option[value="' + wholesaleVal + '"]');
            console.log('Wholesale option found:', $wholesaleOption.length, 'Text:', $wholesaleOption.text());
            if ($wholesaleOption.length > 0) {
                $wholesaleSelect.val(wholesaleVal);
                console.log('Wholesale select after set:', $wholesaleSelect.val());
                // Highlight the change
                $wholesaleSelect.css('background-color', '#e6f3ff');
                setTimeout(() => $wholesaleSelect.css('background-color', ''), 2000);
            } else {
                console.log('❌ Wholesale option not found for value:', wholesaleVal);
            }
        }
        
        // Set Airline
        if (airlineVal) {
            console.log('Setting airline value:', airlineVal);
            var $airlineOption = $airlineSelect.find('option[value="' + airlineVal + '"]');
            console.log('Airline option found:', $airlineOption.length, 'Text:', $airlineOption.text());
            if ($airlineOption.length > 0) {
                $airlineSelect.val(airlineVal);
                console.log('Airline select after set:', $airlineSelect.val());
                // Highlight the change
                $airlineSelect.css('background-color', '#e6f3ff');
                setTimeout(() => $airlineSelect.css('background-color', ''), 2000);
            } else {
                console.log('❌ Airline option not found for value:', airlineVal);
            }
        }
        
        $('#tour-api-code').val($a.data('code'));
        $('#tourResults').empty().hide();
        console.log('=== TOUR SELECTION COMPLETED ===');
    });
    
    $(document).on('click', function(event) {
        if (!$(event.target).closest('#tourResults, #tourSearch').length) {
            $('#tourResults').empty().hide();
        }
    });
    
    $('#resetTourSearch').on('click', function() {
        $('#tourSearch, #tour-id, #tour-code, #tour-api-code').val('');
        $('select[name="quote_country"], select[name="quote_wholesale"], select[name="quote_airline"]').val('');
        $('#tourResults').empty().hide();
    });
});

// --- วันที่ API ---
$(document).on('click', '#list-period', function(e) {
    e.preventDefault();
    var tourId = $('#tour-id').val();
    if (!tourId) {
        alert('กรุณาเลือกแพคเกจทัวร์ก่อน');
        return;
    }
    
    $.ajax({
        url: '/api/tour-periods/' + tourId,
        method: 'GET',
        success: function(data) {
            $('#date-list').empty();
            if (data.length > 0) {
                $.each(data, function(index, item) {
                    $('#date-list').append(
                        `<a href="#" class="block px-3 py-2 hover:bg-blue-100 cursor-pointer"
                            data-start="${item.start_date}"
                            data-end="${item.end_date}">
                            ${item.start_date} - ${item.end_date}
                        </a>`
                    );
                });
                $('#date-list').show();
            } else {
                alert('ไม่พบข้อมูลวันที่เดินทาง');
            }
        },
        error: function() {
            alert('เกิดข้อผิดพลาดในการดึงข้อมูลวันที่');
        }
    });
});

$(document).on('click', '#date-list a', function(e) {
    e.preventDefault();
    var $a = $(this);
    $('#date-start-display').val($a.data('start'));
    $('#date-end-display').val($a.data('end'));
    $('#date-start').val($a.data('start'));
    $('#date-end').val($a.data('end'));
    $('#date-list').empty().hide();
});

// --- Toggle commission note ---
function toggleNoteCommission() {
    var val = $('input[name="quote_commission"]:checked').val();
    if (val === 'N') {
        $('#note-commission-block').show();
    } else {
        $('#note-commission-block').hide();
    }
}

$(document).on('change', 'input[name="quote_commission"]', toggleNoteCommission);
toggleNoteCommission(); // เรียกครั้งแรกตอนโหลด

// --- คำนวณยอดและ sync เงื่อนไขชำระเงิน ---
function calculateQuotation() {
    let vatType = document.querySelector('input[name="vat_option"]:checked')?.value || 'include';
    let vatRate = 0.07;
    let sumTotalNonVat = 0, sumTotalVat = 0, sumDiscount = 0, sumPreVat = 0, sumVat = 0, sumIncludeVat = 0, grandTotal = 0, withholdingAmount = 0, paxTotal = 0;
    
    // Loop รายการบริการ
    document.querySelectorAll('#product-rows tr:not(:last-child)').forEach(row => {
        let selectProduct = row.querySelector('select[name="product_id[]"]');
        if (!selectProduct || !selectProduct.value) return;
        
        let qty = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);
        let price = parseFloat(row.querySelector('input[name="price_per_unit[]"]')?.value || 0);
        let vatStatus = row.querySelector('select[name="vat_status[]"]')?.value || 'nonvat';
        let isWithholding = row.querySelector('input.vat-3')?.checked;
        
        let rowTotal = qty * price;
        if (isWithholding) rowTotal += rowTotal * 0.03;
        
        // อัปเดตยอดรวมในช่อง sum
        let sumCell = row.querySelector('input[name="total_amount[]"]');
        if (sumCell) sumCell.value = rowTotal.toFixed(2);
        
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
    });
    
    // Loop รายการส่วนลด
    document.querySelectorAll('#discount-rows tr:not(:last-child)').forEach(row => {
        let selectProduct = row.querySelector('select[name="product_id[]"]');
        if (!selectProduct || !selectProduct.value) return;
        
        let qty = parseFloat(row.querySelector('input[name="quantity[]"]')?.value || 0);
        let price = parseFloat(row.querySelector('input[name="price_per_unit[]"]')?.value || 0);
        let rowTotal = qty * price;
        
        // อัปเดตยอดรวมในช่อง sum
        let sumCell = row.querySelector('input[name="total_amount[]"]');
        if (sumCell) sumCell.value = rowTotal.toFixed(2);
        
        sumDiscount += rowTotal;
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
    
    // set pax และ hidden fields
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
}

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

// --- Form submission handling ---
$('#formEditNewTest').on('submit', function() {
    // สำหรับทุก .vat-3 (checkbox)
    $('.vat-3').each(function() {
        // ถ้าไม่ได้ติ๊ก ให้ enable hidden input (N) และ disable checkbox
        if (!$(this).is(':checked')) {
            $(this).prop('disabled', true)
                .siblings('input[type="hidden"][name="withholding_tax[]"]').prop('disabled', false);
        } else {
            $(this).siblings('input[type="hidden"][name="withholding_tax[]"]').prop('disabled', true);
        }
    });
});

// --- คำนวณยอดทันทีเมื่อโหลดหน้า ---
$(document).ready(function() {
    console.log('=== DOCUMENT READY - DEBUGGING INFO ===');
    
    // Debug form elements
    console.log('Tour search input:', $('#tourSearch').length);
    console.log('Tour results div:', $('#tourResults').length);
    console.log('Country select:', $('select[name="quote_country"]').length);
    console.log('Wholesale select:', $('select[name="quote_wholesale"]').length);
    console.log('Airline select:', $('select[name="quote_airline"]').length);
    
    // Show current values
    console.log('Current tour search value:', $('#tourSearch').val());
    console.log('Current country value:', $('select[name="quote_country"]').val());
    console.log('Current wholesale value:', $('select[name="quote_wholesale"]').val());
    console.log('Current airline value:', $('select[name="quote_airline"]').val());
    
    // Debug options count
    console.log('Country options count:', $('select[name="quote_country"] option').length);
    console.log('Wholesale options count:', $('select[name="quote_wholesale"] option').length);
    console.log('Airline options count:', $('select[name="quote_airline"] option').length);
    
    // กำหนดค่าเริ่มต้น
    if (!document.querySelector('input[name="vat_option"]:checked')) {
        document.querySelector('input[name="vat_option"][value="include"]').checked = true;
    }
    
    // คำนวณยอดเริ่มต้น
    calculateQuotation();
    
    // ตั้งค่าเริ่มต้นสำหรับการชำระเงิน
    syncDepositAndFullPayment();
    
    // ป้องกัน submit form เมื่อกด Enter ในช่องค้นหา
    $('#tourSearch, #customerSearch').on('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            let resultsId = $(this).attr('id') === 'tourSearch' ? '#tourResults' : '#customerResults';
            let $first = $(resultsId + ' a').first();
            if ($first.length) $first.trigger('click');
        }
    });
    
    console.log('=== DOCUMENT READY COMPLETED ===');
});

// === DEBUG FUNCTIONS ===
// ฟังก์ชันสำหรับ debug ที่สามารถเรียกจาก console
window.debugTourSelection = function() {
    console.log('=== MANUAL DEBUG TOUR SELECTION ===');
    
    // Check elements
    console.log('Elements check:');
    console.log('  #tourSearch:', $('#tourSearch').length, $('#tourSearch').val());
    console.log('  #tourResults:', $('#tourResults').length);
    console.log('  select[name="quote_country"]:', $('select[name="quote_country"]').length);
    console.log('  select[name="quote_wholesale"]:', $('select[name="quote_wholesale"]').length);
    console.log('  select[name="quote_airline"]:', $('select[name="quote_airline"]').length);
    
    // Check current values
    console.log('Current values:');
    console.log('  Country:', $('select[name="quote_country"]').val());
    console.log('  Wholesale:', $('select[name="quote_wholesale"]').val());
    console.log('  Airline:', $('select[name="quote_airline"]').val());
    
    // Check options
    console.log('Options available:');
    $('select[name="quote_country"] option').each(function(i) {
        if (i < 5) console.log('  Country option:', $(this).val(), $(this).text().trim());
    });
    $('select[name="quote_wholesale"] option').each(function(i) {
        if (i < 5) console.log('  Wholesale option:', $(this).val(), $(this).text().trim());
    });
    $('select[name="quote_airline"] option').each(function(i) {
        if (i < 5) console.log('  Airline option:', $(this).val(), $(this).text().trim());
    });
};

// ฟังก์ชันทดสอบการ set ค่า
window.testSetValues = function(countryId, wholesaleId, airlineId) {
    console.log('=== TESTING SET VALUES ===');
    console.log('Setting Country:', countryId, 'Wholesale:', wholesaleId, 'Airline:', airlineId);
    
    if (countryId) {
        $('select[name="quote_country"]').val(countryId);
        console.log('Country set to:', $('select[name="quote_country"]').val());
    }
    if (wholesaleId) {
        $('select[name="quote_wholesale"]').val(wholesaleId);
        console.log('Wholesale set to:', $('select[name="quote_wholesale"]').val());
    }
    if (airlineId) {
        $('select[name="quote_airline"]').val(airlineId);
        console.log('Airline set to:', $('select[name="quote_airline"]').val());
    }
};

// ฟังก์ชันจำลองการเลือกทัวร์
window.simulateTourSelection = function(countryId, wholesaleId, airlineId) {
    console.log('=== SIMULATING TOUR SELECTION ===');
    console.log('Simulating with Country:', countryId, 'Wholesale:', wholesaleId, 'Airline:', airlineId);
    
    // สร้าง fake tour result element
    var $fakeElement = $('<a href="#" data-country="' + countryId + '" data-wholesaler="' + wholesaleId + '" data-airline="' + airlineId + '">Test Tour</a>');
    $fakeElement.data('country', countryId);
    $fakeElement.data('wholesaler', wholesaleId);
    $fakeElement.data('airline', airlineId);
    
    // Trigger click event
    $fakeElement.trigger('click');
};

// ฟังก์ชันแสดงข้อมูลทัวร์จาก API
window.showLastTourApiResponse = function() {
    console.log('=== CHECKING LAST TOUR API DATA ===');
    // This would be set from the last API response
    if (window.lastTourApiResponse) {
        console.log('Last API Response:', window.lastTourApiResponse);
        if (window.lastTourApiResponse.length > 0) {
            console.table(window.lastTourApiResponse);
            
            // Show specific data issues
            window.lastTourApiResponse.forEach(function(item, index) {
                console.log(`Item ${index}:`, {
                    id: item.id,
                    code: item.code,
                    country_id: item.country_id,
                    country_type: typeof item.country_id,
                    country_is_array: Array.isArray(item.country_id),
                    wholesale_id: item.wholesale_id,
                    wholesale_type: typeof item.wholesale_id,
                    wholesale_is_array: Array.isArray(item.wholesale_id),
                    airline_id: item.airline_id,
                    airline_type: typeof item.airline_id,
                    airline_is_array: Array.isArray(item.airline_id)
                });
            });
        }
    } else {
        console.log('No API response data found. Try searching for a tour first.');
    }
};

console.log('Debug functions available:');
console.log('- debugTourSelection()');
console.log('- testSetValues(countryId, wholesaleId, airlineId)');
console.log('- simulateTourSelection(countryId, wholesaleId, airlineId)');
console.log('- showLastTourApiResponse()');

// Helper function to clean data values
window.cleanDataValue = function(value) {
    if (!value) return '';
    
    // Convert to string
    var stringValue = String(value);
    
    // If it looks like an array string, extract the first value
    if (stringValue.includes('[')) {
        // Remove brackets and quotes
        stringValue = stringValue.replace(/[\[\]"']/g, '');
        // Split by comma and take first value
        stringValue = stringValue.split(',')[0];
    }
    
    // Trim whitespace
    stringValue = stringValue.trim();
    
    console.log('cleanDataValue: input =', value, 'output =', stringValue);
    return stringValue;
};

console.log('Helper functions:');
console.log('- cleanDataValue(value) - cleans array-like strings');;

function syncDepositAndFullPayment() {
    let paxTotal = parseFloat(document.getElementById('quote-pax-total')?.value || 0);
    let depositPerPax = parseFloat(document.getElementById('quote-payment-price')?.value || 0);
    let payExtra = parseFloat(document.getElementById('pay-extra')?.value || 0);
    let depositTotal = (paxTotal * depositPerPax) + payExtra;
    
    if (document.getElementById('quote-payment-deposit')?.checked) {
        document.getElementById('payment-amount').value = depositTotal.toFixed(2);
    }
    
    // อัปเดตยอดชำระเต็มจำนวน
    let grandTotal = parseFloat(document.querySelector('[data-summary="grandTotal"]')?.textContent || 0);
    document.getElementById('full-payment-amount').value = grandTotal.toFixed(2);
}

// --- Perfect Scrollbar Initialization (Safe) ---
$(document).ready(function() {
    // ตรวจสอบว่า Perfect Scrollbar พร้อมใช้งานหรือไม่
    if (typeof PerfectScrollbar !== 'undefined') {
        try {
            // Initialize Perfect Scrollbar for elements that need it
            const scrollElements = document.querySelectorAll('.scroll-sidebar, .perfect-scrollbar');
            scrollElements.forEach(function(element) {
                if (element && !element.perfectScrollbar) {
                    new PerfectScrollbar(element, {
                        wheelSpeed: 2,
                        wheelPropagation: false,
                        minScrollbarLength: 20
                    });
                }
            });
        } catch (e) {
            console.log('Perfect Scrollbar initialization skipped:', e.message);
        }
    }
    
    // Handle responsive Perfect Scrollbar
    function handleResponsiveScrollbar() {
        if (typeof PerfectScrollbar !== 'undefined') {
            try {
                const isMobile = window.innerWidth < 768;
                const scrollElements = document.querySelectorAll('.scroll-sidebar');
                
                scrollElements.forEach(function(element) {
                    if (isMobile) {
                        // Destroy on mobile
                        if (element.perfectScrollbar) {
                            element.perfectScrollbar.destroy();
                            element.perfectScrollbar = null;
                        }
                    } else {
                        // Initialize on desktop
                        if (!element.perfectScrollbar) {
                            element.perfectScrollbar = new PerfectScrollbar(element);
                        }
                    }
                });
            } catch (e) {
                console.log('Responsive scrollbar handling skipped:', e.message);
            }
        }
    }
    
    // Call on load and resize
    handleResponsiveScrollbar();
    $(window).on('resize', handleResponsiveScrollbar);
});

</script>


