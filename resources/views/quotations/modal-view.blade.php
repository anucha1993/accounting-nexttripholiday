
<div class="container-fluid page-content">
    <div class="todo-listing">
        <div class="container border bg-white">
            <h2 class="text-center my-4"><i class="fa fa-file-invoice-dollar" style="color:#1976d2;margin-right:8px;"></i>ดูข้อมูลใบเสนอราคา</h2>
            {{-- ข้อมูลทั่วไป --}}
            <div class="section-card">
                <div class="section-title"><i class="fa fa-user-tie"></i> ข้อมูลทั่วไป</div>
                <div class="row table-custom">
                    <div class="col-md-2 ms-auto">
                        <label>เซลล์ผู้ขายแพคเกจ:</label>
                        <div>{{ $sales->firstWhere('id', $quotationModel->quote_sale)->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-3 ms-3">
                        <label>วันที่สั่งซื้อ/จองแพคเกจ:</label>
                        <div>{{ $quotationModel->quote_booking_create ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <label>เลขที่ใบเสนอราคา</label>
                        <div>{{ $quotationModel->quote_number ?? '-' }}</div>
                    </div>
                    <div class="col-md-2">
                        <label>วันที่เสนอราคา</label>
                        <div>{{ $quotationModel->quote_date ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <hr class="divider">
            {{-- รายละเอียดแพคเกจทัวร์ --}}
            <div class="section-card">
                <div class="section-title" style="background:linear-gradient(90deg,#d84315 60%,#ff7043 100%)"><i class="fa fa-suitcase-rolling"></i> รายละเอียดแพคเกจทัวร์</div>
                <div class="row table-custom">
                    <div class="col-md-6">
                        <label>ชื่อแพคเกจทัวร์:</label>
                        <div>{{ $quotationModel->quote_tour_name ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>รหัสทัวร์ API</label>
                        <div>{{ $quotationModel->quote_tour ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>รหัสทัวร์ กำหนดเอง</label>
                        <div>{{ $quotationModel->quote_tour_code ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>ระยะเวลาทัวร์ (วัน/คืน):</label>
                        <div>{{ $quotationModel->quote_numday ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>ประเทศที่เดินทาง:</label>
                        <div>{{ $country->firstWhere('id', $quotationModel->quote_country)->country_name_th ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>โฮลเซลล์:</label>
                        <div>{{ $wholesale->firstWhere('id', $quotationModel->quote_wholesale)->wholesale_name_th ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>สายการบิน:</label>
                        <div>{{ $airline->firstWhere('id', $quotationModel->quote_airline)->travel_name ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>วันออกเดินทาง:</label>
                        <div>{{ $quotationModel->quote_date_start ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>วันเดินทางกลับ:</label>
                        <div>{{ $quotationModel->quote_date_end ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <hr class="divider">
            {{-- ข้อมูลลูกค้า --}}
            <div class="section-card">
                <div class="section-title" style="background:linear-gradient(90deg,#43a047 60%,#81c784 100%)"><i class="fa fa-users"></i> ข้อมูลลูกค้า</div>
                <div class="row table-custom">
                    <div class="col-md-3">
                        <label>ชื่อลูกค้า:</label>
                        <div>{{ $customer->customer_name ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>อีเมล์:</label>
                        <div>{{ $customer->customer_email ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>เลขผู้เสียภาษี:</label>
                        <div>{{ $customer->customer_texid ?? '-' }}</div>
                    </div>
                    <div class="col-md-3">
                        <label>เบอร์โทรศัพท์:</label>
                        <div>{{ $customer->customer_tel ?? '-' }}</div>
                    </div>
                    <div class="col-md-12">
                        <label>ที่อยู่:</label>
                        <div>{{ $customer->customer_address ?? '-' }}</div>
                    </div>
                </div>
            </div>
            <hr class="divider">
            {{-- ข้อมูลค่าบริการ --}}
            <div class="section-card">
                <h5 class="section-inline"><i class="fa fa-coins"></i> ข้อมูลค่าบริการ <span class="float-end">Pax: {{ $quotationModel->quote_pax_total ?? '-' }}</span></h5>
                <div id="quotation-table" class="table-custom text-center">
                    <div class="row header-row" style="padding: 5px">
                        <div class="col-md-1">ลำดับ</div>
                        <div class="col-md-3">รายการสินค้า</div>
                        <div class="col-md-1">รวม 3%</div>
                        <div class="col-md-1">NonVat</div>
                        <div class="col-md-1">จำนวน</div>
                        <div class="col-md-2">ราคา/หน่วย</div>
                        <div class="col-md-2">ยอดรวม</div>
                    </div>
                    <hr>
                    @foreach ($quoteProducts as $i => $row)
                    <div class="row item-row table-income" style="background:#55ffb848;border-radius:8px;padding:8px 0;">
                        <div class="col-md-1">{{ $i+1 }}</div>
                        <div class="col-md-3">{{ $products->firstWhere('id', $row->product_id)->product_name ?? '-' }}</div>
                        <div class="col-md-1">{{ $row->withholding_tax == 'Y' ? '✔' : '' }}</div>
                        <div class="col-md-1">{{ $row->vat_status == 'nonvat' ? '✔' : '' }}</div>
                        <div class="col-md-1">{{ $row->product_qty ?? '-' }}</div>
                        <div class="col-md-2">{{ number_format($row->product_price, 2) }}</div>
                        <div class="col-md-2">{{ number_format($row->product_sum, 2) }}</div>
                    </div>
                    @endforeach
                </div>
                <hr>
                <div class="row item-row table-discount">
                    <div class="col-md-12" style="text-align: left">
                        <label class="text-danger">ส่วนลด</label>
                        <div id="discount-list">
                            @foreach ($quoteProductsDiscount as $i => $row)
                            <div class="row item-row table-discount mb-1 align-items-center discount-row" style="background:#fffbe7;border-radius:8px;padding:8px 0;">
                                <div class="col-md-1 text-center">{{ $i+1 }}</div>
                                <div class="col-md-3">{{ $productDiscount->firstWhere('id', $row->product_id)->product_name ?? '-' }}</div>
                                <div class="col-md-1">{{ $row->vat_status == 'nonvat' ? '✔' : '' }}</div>
                                <div class="col-md-1">{{ $row->product_qty ?? '-' }}</div>
                                <div class="col-md-2">{{ number_format($row->product_price, 2) }}</div>
                                <div class="col-md-2">{{ number_format($row->product_sum, 2) }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <hr class="divider">
            {{-- สรุปยอดและ VAT --}}
            <div class="section-card">
                <div class="section-title" style="background:linear-gradient(90deg,#1976d2 60%,#42a5f5 100%)"><i class="fa fa-calculator"></i> สรุปยอดและ VAT</div>
                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-12">
                                <label>การคำนวณ VAT:</label>
                                <div>{{ $quotationModel->quote_vat_type == 'include' ? 'คำนวณรวมกับราคาสินค้าและบริการ (VAT Include)' : 'คำนวณแยกกับราคาสินค้าและบริการ (VAT Exclude)' }}</div>
                            </div>
                            <div class="col-md-12">
                                <label>คิดภาษีหัก ณ ที่จ่าย 3%:</label>
                                <div>{{ $quotationModel->quote_withholding_tax_status == 'Y' ? '✔' : '' }}</div>
                            </div>
                            <div class="col-md-12">
                                <label>จำนวนเงินภาษีหัก ณ ที่จ่าย 3%:</label>
                                <div>{{ number_format($quotationModel->quote_withholding_tax, 2) }} บาท</div>
                            </div>
                            <div class="col-md-12">
                                <label>บันทึกเพิ่มเติม</label>
                                <div>{{ $quotationModel->quote_note ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">ยอดรวมยกเว้นภาษี / Vat-Exempted Amount</div>
                            <div class="col-md-2 text-end">{{ number_format($quotationModel->quote_vat_exempted_amount, 2) }}</div>
                        </div>
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">ราคาสุทธิสินค้าที่เสียภาษี / Pre-Tax Amount:</div>
                            <div class="col-md-2 text-end">{{ number_format($quotationModel->quote_pre_tax_amount, 2) }}</div>
                        </div>
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">ส่วนลด / Discount :</div>
                            <div class="col-md-2 text-end">{{ number_format($quotationModel->quote_discount, 2) }}</div>
                        </div>
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">ราคาก่อนภาษีมูลค่าเพิ่ม / Pre-VAT Amount:</div>
                            <div class="col-md-2 text-end">{{ number_format($quotationModel->quote_pre_vat_amount, 2) }}</div>
                        </div>
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">ภาษีมูลค่าเพิ่ม VAT : 7%:</div>
                            <div class="col-md-2 text-end">{{ number_format($quotationModel->quote_vat, 2) }}</div>
                        </div>
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">ราคารวมภาษีมูลค่าเพิ่ม / Include VAT:</div>
                            <div class="col-md-2 text-end">{{ number_format($quotationModel->quote_include_vat, 2) }}</div>
                        </div>
                        <div class="row summary text-info">
                            <div class="col-md-10 text-end">จำนวนเงินรวมทั้งสิ้น / Grand Total:</div>
                            <div class="col-md-2 text-end"><b>{{ number_format($quotationModel->quote_grand_total, 2) }}</b></div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="divider">
            {{-- เงื่อนไขการชำระเงิน --}}
            <div class="section-card">
                <div class="section-title" style="background:linear-gradient(90deg,#fbc02d 60%,#fff176 100%);color:#333;"><i class="fa fa-hand-holding-usd"></i> เงื่อนไขการชำระเงิน</div>
                <div class="row">
                    <div class="col-md-12">
                        <label>เงื่อนไขการชำระเงิน</label>
                        <div class="mb-2">
                            <span>@if($quotationModel->quote_payment_type == 'deposit')<i class="fa fa-check text-success"></i>@endif เงินมัดจำ</span>
                            <div class="row mb-2 ms-3">
                                <div class="col-md-3">ภายในวันที่: <span>{{ $quotationModel->quote_payment_date ? date('d/m/Y H:i', strtotime($quotationModel->quote_payment_date)) : '-' }}</span></div>
                                <div class="col-md-3">เรทเงินมัดจำ: <span>{{ number_format($quotationModel->quote_payment_price, 2) }}</span></div>
                                <div class="col-md-3">ชำระเพิ่มเติม: <span>{{ number_format($quotationModel->quote_payment_extra, 2) }}</span></div>
                                <div class="col-md-3">จำนวนเงินที่ต้องชำระ: <span>{{ number_format($quotationModel->quote_payment_total, 2) }}</span></div>
                            </div>
                            
                        </div>
                        <div class="mb-2">
                            <span>@if($quotationModel->quote_payment_type == 'full')<i class="fa fa-check text-success"></i>@endif ชำระเต็มจำนวน</span>
                            <div class="row mb-2 ms-3">
                                <div class="col-md-3">ภายในวันที่: <span>{{ $quotationModel->quote_payment_date_full ? date('d/m/Y H:i', strtotime($quotationModel->quote_payment_date_full)) : '-' }}</span></div>
                                <div class="col-md-3">จำนวนเงิน: <span>{{ number_format($quotationModel->quote_payment_total_full, 2) }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2 mb-2">
                    <div class="col-md-12">
                        <label>สถานะการจ่ายค่าคอมมิชชั่น:</label>
                        <div class="row mb-2 ms-3">
                            <div class="col-md-3"><span>@if($quotationModel->quote_commission == 'Y')<i class="fa fa-check text-success"></i>@endif จ่ายค่าคอม</span></div>
                            <div class="col-md-3"><span>@if($quotationModel->quote_commission == 'N')<i class="fa fa-check text-success"></i>@endif ไม่จ่ายค่าคอม</span></div>
                        </div>
                       
                        @if($quotationModel->quote_commission == 'N' && !empty($quotationModel->quote_note_commission))
                            <div class="mt-1"><label>เหตุผล:</label> <span>{{ $quotationModel->quote_note_commission }}</span></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
