@php
    use Carbon\Carbon;
@endphp

<div class="modal-body">
    <div class="header">
        <h5>รายการที่ต้องทำ</h5>
    </div>

    <ul class="list-group">
        <!-- ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์ -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="booking_email_status"
                onchange="updateOrCreateLog('booking_email', this)"
                {{ optional($quoteLog)->booking_email_status === 'ส่งแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->booking_email_status === 'ส่งแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->booking_email_status === 'ส่งแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            ส่งใบอีเมลล์จองทัวร์ให้โฮลเซลล์:
            <span class="{{ optional($quoteLog)->booking_email_status === 'ส่งแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->booking_email_status ?? 'ยังไม่ได้ส่ง' }}
            </span>
            <a class="mail-quote" href="{{ route('mail.quote.formMail', $quotationModel->quote_id) }}">
                <i class="fas fa-envelope text-info"></i>
                ส่งเมล
            </a>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->booking_email_updated_at ? Carbon::parse($quoteLog->booking_email_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->booking_email_created_by ?? 'ไม่ทราบ' }}
            </small>
        </li>

        <!-- อินวอยโฮลเซลล์ -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="invoice_status"
                onchange="updateOrCreateLog('invoice', this)"
                {{ optional($quoteLog)->invoice_status === 'ได้แล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->invoice_status === 'ได้แล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->invoice_status === 'ได้แล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            อินวอยโฮลเซลล์:
            <span class="{{ optional($quoteLog)->invoice_status === 'ได้แล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->invoice_status ?? 'ยังไม่ได้' }}
            </span>
            {{-- <input type="file" name="files[]" multiple onchange="uploadFiles(event)"> --}}
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->invoice_updated_at ? Carbon::parse($quoteLog->invoice_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->invoice_created_by ?? 'ไม่ทราบ' }}
            </small>

            <ul>
                {{-- ใบเสนอราคา --}}
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input me-2" id="quote_status"
                        onchange="updateOrCreateLog('quote', this)"
                        {{ optional($quoteLog)->quote_status === 'ได้แล้ว' ? 'checked' : '' }}>
                    <i data-feather="{{ optional($quoteLog)->quote_status === 'ได้แล้ว' ? 'check-circle' : 'box' }}"
                        class="{{ optional($quoteLog)->quote_status === 'ได้แล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
                    ได้รับไฟล์ใบเสนอราคาแล้ว:
                    <span
                        class="{{ optional($quoteLog)->quote_status === 'ได้แล้ว' ? 'text-success' : 'text-muted' }}">
                        {{ optional($quoteLog)->quote_status ?? 'ยังไม่ได้' }}
                    </span>
                    {{-- <input type="file" name="files[]" multiple onchange="uploadFiles(event)"> --}}
                    <br>
                    <small class="text-secondary">
                        อัปเดตล่าสุด:
                        {{ optional($quoteLog)->quote_updated_at ? Carbon::parse($quoteLog->quote_updated_at)->format('d-m-Y : H:m:s') : '' }}
                        โดย {{ optional($quoteLog)->quote_created_by ?? 'ไม่ทราบ' }}
                    </small>
                </li>

                {{-- ไฟล์ใบแจ้งหนี้ --}}
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input me-2" id="inv_status"
                        onchange="updateOrCreateLog('inv', this)"
                        {{ optional($quoteLog)->inv_status === 'ได้แล้ว' ? 'checked' : '' }}>
                    <i data-feather="{{ optional($quoteLog)->quote_status === 'ได้แล้ว' ? 'check-circle' : 'box' }}"
                        class="{{ optional($quoteLog)->inv_status === 'ได้แล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
                    ได้รับไฟล์ใบแจ้งหนี้แล้ว:
                    <span class="{{ optional($quoteLog)->inv_status === 'ได้แล้ว' ? 'text-success' : 'text-muted' }}">
                        {{ optional($quoteLog)->inv_status ?? 'ยังไม่ได้' }}
                    </span>
                    {{-- <input type="file" name="files[]" multiple onchange="uploadFiles(event)"> --}}
                    <br>
                    <small class="text-secondary">
                        อัปเดตล่าสุด:
                        {{ optional($quoteLog)->inv_updated_at ? Carbon::parse($quoteLog->inv_updated_at)->format('d-m-Y : H:m:s') : '' }}
                        โดย {{ optional($quoteLog)->inv_created_by ?? 'ไม่ทราบ' }}
                    </small>
                </li>

            </ul>




        </li>


        <!-- ส่งสลิปให้โฮลเซลล์ -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="slip_status"
                onchange="updateOrCreateLog('slip', this)"
                {{ optional($quoteLog)->slip_status === 'ส่งแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->slip_status === 'ส่งแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->slip_status === 'ส่งแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            ส่งสลิปให้โฮลเซลล์:
            <span class="{{ optional($quoteLog)->slip_status === 'ส่งแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->slip_status ?? 'ยังไม่ได้ส่ง' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->slip_updated_at ? Carbon::parse($quoteLog->slip_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->slip_created_by ?? 'ไม่ทราบ' }}
            </small>

            <ul>
                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input me-2" id="depositslip_status"
                    onchange="updateOrCreateLog('depositslip', this)"
                    {{ optional($quoteLog)->depositslip_status === 'ส่งแล้ว' ? 'checked' : '' }}>
                <i data-feather="{{ optional($quoteLog)->depositslip_status === 'ส่งแล้ว' ? 'check-circle' : 'box' }}"
                    class="{{ optional($quoteLog)->depositslip_status === 'ส่งแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
                ส่งสลิปมัดจำ:
                <span class="{{ optional($quoteLog)->depositslip_status === 'ส่งแล้ว' ? 'text-success' : 'text-muted' }}">
                    {{ optional($quoteLog)->depositslip_status ?? 'ยังไม่ได้ส่ง' }}
                </span>
                <br>
                <small class="text-secondary">
                    อัปเดตล่าสุด:
                    {{ optional($quoteLog)->depositslip_updated_at ? Carbon::parse($quoteLog->depositslip_updated_at)->format('d-m-Y : H:m:s') : '' }}
                    โดย {{ optional($quoteLog)->depositslip_created_by ?? 'ไม่ทราบ' }}
                </small>
                </li>

                <li class="list-group-item">
                    <input type="checkbox" class="form-check-input me-2" id="fullslip_status"
                    onchange="updateOrCreateLog('fullslip', this)"
                    {{ optional($quoteLog)->fullslip_status === 'ส่งแล้ว' ? 'checked' : '' }}>
                <i data-feather="{{ optional($quoteLog)->fullslip_status === 'ส่งแล้ว' ? 'check-circle' : 'box' }}"
                    class="{{ optional($quoteLog)->fullslip_status === 'ส่งแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
                ส่งสลิปยอดเต็ม:
                <span class="{{ optional($quoteLog)->fullslip_status === 'ส่งแล้ว' ? 'text-success' : 'text-muted' }}">
                    {{ optional($quoteLog)->fullslip_status ?? 'ยังไม่ได้ส่ง' }}
                </span>
                <br>
                <small class="text-secondary">
                    อัปเดตล่าสุด:
                    {{ optional($quoteLog)->fullslip_updated_at ? Carbon::parse($quoteLog->fullslip_updated_at)->format('d-m-Y : H:m:s') : '' }}
                    โดย {{ optional($quoteLog)->fullslip_created_by ?? 'ไม่ทราบ' }}
                </small>
                </li>

              
            </ul>
        </li>

        <!-- ส่งพาสปอตให้โฮลเซลล์ -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="passport_status"
                onchange="updateOrCreateLog('passport', this)"
                {{ optional($quoteLog)->passport_status === 'ส่งแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->passport_status === 'ส่งแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->passport_status === 'ส่งแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            ส่งพาสปอตให้โฮลเซลล์:
            <span class="{{ optional($quoteLog)->passport_status === 'ส่งแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->passport_status ?? 'ยังไม่ได้ส่ง' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->passport_updated_at ? Carbon::parse($quoteLog->passport_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->passport_created_by ?? 'ไม่ทราบ' }}
            </small>
        </li>

        <!-- ส่งใบนัดหมายให้ลูกค้า -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="appointment_status"
                onchange="updateOrCreateLog('appointment', this)"
                {{ optional($quoteLog)->appointment_status === 'ส่งแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->appointment_status === 'ส่งแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->appointment_status === 'ส่งแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            ส่งใบนัดหมายให้ลูกค้า:
            <span class="{{ optional($quoteLog)->appointment_status === 'ส่งแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->appointment_status ?? 'ยังไม่ได้ส่ง' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->appointment_updated_at ? Carbon::parse($quoteLog->appointment_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->appointment_created_by ?? 'ไม่ทราบ' }}
            </small>
        </li>

        <!-- ออกใบหักณที่จ่าย -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="withholding_tax_status"
                onchange="updateOrCreateLog('withholding_tax', this)"
                {{ optional($quoteLog)->withholding_tax_status === 'ออกแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->withholding_tax_status === 'ออกแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->withholding_tax_status === 'ออกแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            ออกใบหัก ณ ที่จ่าย:
            <span
                class="{{ optional($quoteLog)->withholding_tax_status === 'ออกแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->withholding_tax_status ?? 'ยังไม่ได้ออก' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->withholding_tax_updated_at ? Carbon::parse($quoteLog->withholding_tax_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->withholding_tax_created_at ?? 'ไม่ทราบ' }}
            </small>
            <ul >
                <li class="list-group-item">
                <input type="checkbox" class="form-check-input me-2" id="wholesale_skip_status"
                onchange="updateOrCreateLog('wholesale_skip', this)"
                {{ optional($quoteLog)->wholesale_skip_status === 'ไม่ต้องการออก' ? 'checked' : '' }}>ไม่ต้องการออก :
            </li>
            </ul>
        </li>

        <!-- ใบแจ้งหนี้โฮลเซลล์ -->
        <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="wholesale_tax_status"
                onchange="updateOrCreateLog('wholesale_tax', this)"
                {{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
            ใบกำกับภาษีโฮลเซลล์ :
            <span
                class="{{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->wholesale_tax_status ?? 'ยังไม่ได้รับ' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->wholesale_tax_updated_at ? Carbon::parse($quoteLog->wholesale_tax_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->withholding_tax_created_by ?? 'ไม่ทราบ' }}
            </small>

            

         

        </li>
        <li>
       


    </ul>


    {{-- mail form quote --}}
    <div class="modal fade bd-example-modal-sm modal-lg modal-mail-quote" id="modal-mail-quote" tabindex="-1"
        role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                ...
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // modal add payment wholesale quote
            $(".mail-quote").on("click", function(e) {
                e.preventDefault();
                // Show modal and load content
                $(".modal-mail-quote").modal("show").find(".modal-content").load($(this).attr("href"));
                // Hide another modal if open
                $(".modal-quote-check").modal("hide");
            });
        });

        // ป้องกันการติ๊กเอง
        $('#invoice_status, #slip_status').on('click', function(event) {
            event.preventDefault();
        });


        function updateOrCreateLog(field, checkbox) {
            const statusMapping = {
                booking_email: {
                    true: 'ส่งแล้ว',
                    false: 'ยังไม่ได้ส่ง'
                },
                invoice: {
                    true: 'ได้แล้ว',
                    false: 'ยังไม่ได้'
                },
                quote: {
                    true: 'ได้แล้ว',
                    false: 'ยังไม่ได้'
                },
                inv: {
                    true: 'ได้แล้ว',
                    false: 'ยังไม่ได้'
                },
                slip: {
                    true: 'ส่งแล้ว',
                    false: 'ยังไม่ได้ส่ง'
                },
                depositslip: {
                    true: 'ส่งแล้ว',
                    false: 'ยังไม่ได้ส่ง'
                },
                fullslip: {
                    true: 'ส่งแล้ว',
                    false: 'ยังไม่ได้ส่ง'
                },
                passport: {
                    true: 'ส่งแล้ว',
                    false: 'ยังไม่ได้ส่ง'
                },
                appointment: {
                    true: 'ส่งแล้ว',
                    false: 'ยังไม่ได้ส่ง'
                },
                withholding_tax: {
                    true: 'ออกแล้ว',
                    false: 'ยังไม่ได้ออก'
                },
                withholding_skip: {
                    true: 'ไม่ต้องการออก',
                    false: 'ออก'
                },
                wholesale_tax: {
                    true: 'ได้รับแล้ว',
                    false: 'ยังไม่ได้รับ'
                }
            };

            const status = checkbox.checked ?
    statusMapping[field]?.true || 'ไม่ต้องการออก' : 
    statusMapping[field]?.false || 'ต้องการออก';

            fetch('{{ route('quote.updateLogStatus', $quotationModel->quote_id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        field: field,
                        status: status,
                        created_by: '{{ auth()->user()->name }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Status updated successfully') {
                        const statusElement = checkbox.nextElementSibling;
                        statusElement.className = status === 'ได้แล้ว' || status === 'ส่งแล้ว' || status === 'ออกแล้ว' || status === 'ได้รับแล้ว' ? 'text-success' : 'text-muted';
                        statusElement.innerText = status;

                        const updateInfo = checkbox.parentElement.querySelector('.text-secondary');
                        updateInfo.innerText = `อัปเดตล่าสุด: ${data.updated_at} โดย ${data.created_by}`;
                    }
                })
                .catch(error => console.error('Error updating status:', error));
        }



        function updateInvoiceStatus() {
            if ($('#quote_status').prop('checked') && $('#inv_status').prop('checked')) {
                $('#invoice_status').prop('checked', true).prop('readonly', true);
                $('#invoice_status').trigger('change');
            } else {
                $('#invoice_status').prop('checked', false).prop('readonly', false);
            }
        }

        // เรียกใช้ updateInvoiceStatus() เมื่อโหลดหน้าเว็บ
        updateInvoiceStatus();

        $('#quote_status, #inv_status').on('change', function() {
            updateInvoiceStatus();
            // เรียกใช้ updateOrCreateLog สำหรับ invoice_quote_status และ in_inv_status
            updateOrCreateLog('quote', $('#quote_status')[0]);
            updateOrCreateLog('inv', $('#inv_status')[0]);
        });

        // เรียกใช้ updateOrCreateLog สำหรับ inve_status
        $('#invoice_status').on('change', function() {
            updateOrCreateLog('invoice', this);
        });


        function updateSlipStatus() {
            if ($('#depositslip_status').prop('checked') && $('#fullslip_status').prop('checked')) {
                $('#slip_status').prop('checked', true).prop('readonly', true);
                $('#slip_status').trigger('change');
            } else {
                $('#slip_status').prop('checked', false).prop('readonly', false);
            }
        }

        // เรียกใช้ updateInvoiceStatus() เมื่อโหลดหน้าเว็บ
        updateSlipStatus();

        $('#depositslip_status, #fullslip_status').on('change', function() {
            updateSlipStatus();
            // เรียกใช้ updateOrCreateLog สำหรับ invoice_quote_status และ in_inv_status
            updateOrCreateLog('depositslip', $('#depositslip_status')[0]);
            updateOrCreateLog('fullslip', $('#fullslip_status')[0]);
        });

        // เรียกใช้ updateOrCreateLog สำหรับ inve_status
        $('#slip_status').on('change', function() {
            updateOrCreateLog('slip', this);
        });

    </script>
</div>
