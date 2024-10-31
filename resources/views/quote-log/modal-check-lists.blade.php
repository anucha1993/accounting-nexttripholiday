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
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด: {{ optional($quoteLog)->booking_email_updated_at ? Carbon::parse($quoteLog->booking_email_updated_at)->format('d M Y') : '' }}
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
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด: {{ optional($quoteLog)->invoice_updated_at ? Carbon::parse($quoteLog->invoice_updated_at)->format('d M Y') : '' }}
                โดย {{ optional($quoteLog)->invoice_created_by ?? 'ไม่ทราบ' }}
            </small>
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
                อัปเดตล่าสุด: {{ optional($quoteLog)->slip_updated_at ? Carbon::parse($quoteLog->slip_updated_at)->format('d M Y') : '' }}
                โดย {{ optional($quoteLog)->slip_created_by ?? 'ไม่ทราบ' }}
            </small>
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
                อัปเดตล่าสุด: {{ optional($quoteLog)->passport_updated_at ? Carbon::parse($quoteLog->passport_updated_at)->format('d M Y') : '' }}
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
                อัปเดตล่าสุด: {{ optional($quoteLog)->appointment_updated_at ? Carbon::parse($quoteLog->appointment_updated_at)->format('d M Y') : '' }}
                โดย {{ optional($quoteLog)->appointment_created_by ?? 'ไม่ทราบ' }}
            </small>
        </li>
    </ul>

    <script>
        function updateOrCreateLog(field, checkbox) {
            const status = checkbox.checked ? (field === 'invoice' ? 'ได้แล้ว' : 'ส่งแล้ว') : (field === 'invoice' ? 'ยังไม่ได้' : 'ยังไม่ได้ส่ง');

            fetch('{{ route("quote.updateLogStatus", $quotationModel->quote_id) }}', {
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
                    statusElement.className = status === 'ได้แล้ว' || status === 'ส่งแล้ว' ? 'text-success' : 'text-muted';
                    statusElement.innerText = status;

                    const updateInfo = checkbox.parentElement.querySelector('.text-secondary');
                    updateInfo.innerText = `อัปเดตล่าสุด: ${data.updated_at} โดย ${data.created_by}`;

                    const toastMessage = document.getElementById('toastMessage');
                    toastMessage.textContent = `${field} has been updated to "${status}"`;
                    const toastElement = new bootstrap.Toast(document.getElementById('statusToast'));
                    toastElement.show();
                }
            })
            .catch(error => console.error('Error updating status:', error));
        }
    </script>
</div>
