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
            <span class="{{ optional($quoteLog)->withholding_tax_status === 'ออกแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->withholding_tax_status ?? 'ยังไม่ได้ออก' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->withholding_tax_updated_at ? Carbon::parse($quoteLog->withholding_tax_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->withholding_tax_created_at ?? 'ไม่ทราบ' }}
            </small>
        </li>

         <!-- ใบแจ้งหนี้โฮลเซลล์ -->
         <li class="list-group-item">
            <input type="checkbox" class="form-check-input me-2" id="wholesale_tax_status"
                onchange="updateOrCreateLog('wholesale_tax', this)"
                {{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'checked' : '' }}>
            <i data-feather="{{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'check-circle' : 'box' }}"
                class="{{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'text-success' : 'text-warning' }} feather-sm me-2"></i>
                ใบกำกับภาษีโฮลเซลล์ :
            <span class="{{ optional($quoteLog)->wholesale_tax_status === 'ได้รับแล้ว' ? 'text-success' : 'text-muted' }}">
                {{ optional($quoteLog)->wholesale_tax_status ?? 'ยังไม่ได้รับ' }}
            </span>
            <br>
            <small class="text-secondary">
                อัปเดตล่าสุด:
                {{ optional($quoteLog)->wholesale_tax_updated_at ? Carbon::parse($quoteLog->wholesale_tax_updated_at)->format('d-m-Y : H:m:s') : '' }}
                โดย {{ optional($quoteLog)->withholding_tax_created_by ?? 'ไม่ทราบ' }}
            </small>
        </li>


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
        slip: {
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
        wholesale_tax: {
            true: 'ได้รับแล้ว',
            false: 'ยังไม่ได้รับ'
        }
    };

    const status = checkbox.checked
        ? statusMapping[field]?.true || 'ส่งแล้ว'
        : statusMapping[field]?.false || 'ยังไม่ได้ส่ง';

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



        // function updateOrCreateLog(field, checkbox) {
        //     const status = checkbox.checked ? (field === 'invoice' ? 'ได้แล้ว' : 'ส่งแล้ว') : (field === 'invoice' ?
        //         'ยังไม่ได้' : 'ยังไม่ได้ส่ง');

        //     fetch('{{ route('quote.updateLogStatus', $quotationModel->quote_id) }}', {
        //             method: 'POST',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //             },
        //             body: JSON.stringify({
        //                 field: field,
        //                 status: status,
        //                 created_by: '{{ auth()->user()->name }}'
        //             })
        //         })
        //         .then(response => response.json())
        //         .then(data => {
        //             if (data.message === 'Status updated successfully') {
        //                 const statusElement = checkbox.nextElementSibling;
        //                 statusElement.className = status === 'ได้แล้ว' || status === 'ส่งแล้ว' ? 'text-success' :
        //                     'text-muted';
        //                 statusElement.innerText = status;

        //                 const updateInfo = checkbox.parentElement.querySelector('.text-secondary');
        //                 updateInfo.innerText = `อัปเดตล่าสุด: ${data.updated_at} โดย ${data.created_by}`;

        //                 const toastMessage = document.getElementById('toastMessage');
        //                 toastMessage.textContent = `${field} has been updated to "${status}"`;
        //                 const toastElement = new bootstrap.Toast(document.getElementById('statusToast'));
        //                 toastElement.show();
        //             }
        //         })
        //         .catch(error => console.error('Error updating status:', error));
        // }


        function uploadFiles(event) {
            const files = event.target.files;
            const formData = new FormData();
            for (const file of files) {
                formData.append('files[]', file);
            }

            fetch('{{ route('quote.uploadFiles', $quotationModel->quote_id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Files uploaded successfully') {
                        document.getElementById('invoice_status').checked = true;
                        const statusElement = document.getElementById('invoice_status').nextElementSibling;
                        statusElement.className = 'text-success';
                        statusElement.innerText = 'ได้แล้ว';

                        const updateInfo = document.getElementById('invoice_status').parentElement.querySelector(
                            '.text-secondary');
                        updateInfo.innerText = `อัปเดตล่าสุด: ${data.updated_at} โดย ${data.created_by}`;

                        // แสดงลิงก์สำหรับดูภาพที่อัปโหลดทั้งหมด
                        const fileLinksContainer = document.getElementById('uploaded-file-links');
                        fileLinksContainer.innerHTML = ''; // ล้างลิงก์เก่า
                        data.uploaded_files.forEach((fileUrl, index) => {
                            const fileContainer = document.createElement('div');
                            fileContainer.classList.add('d-flex', 'align-items-center', 'mb-2');

                            const linkElement = document.createElement('a');
                            linkElement.href = fileUrl;
                            linkElement.target = '_blank';
                            linkElement.innerText = `file-upload-${(index + 1).toString().padStart(2, '0')}`;
                            linkElement.classList.add('me-2');

                            // แทน deleteButton ด้วย <a href="#">
                            const deleteLink = document.createElement('a');
                            deleteLink.href = '#';
                            deleteLink.innerText = 'ลบ';
                            deleteLink.classList.add('text-danger', 'ms-2');
                            deleteLink.onclick = (event) => {
                                event.preventDefault(); // ป้องกันไม่ให้ลิงก์รีเฟรชหน้า
                                deleteFile(fileUrl, fileContainer); // เรียกใช้ฟังก์ชันลบไฟล์
                            };

                            fileContainer.appendChild(linkElement);
                            fileContainer.appendChild(deleteLink);
                            fileLinksContainer.appendChild(fileContainer);
                        });
                        alert(data.message); // หรือแสดงการแจ้งเตือนอื่น
                    }
                })
                .catch(error => console.error('Error uploading files:', error));
        }

        function deleteFile(fileUrl, fileContainer) {
            if (!confirm('คุณต้องการลบไฟล์นี้ใช่หรือไม่?')) return;

            fetch("{{ route('quote.deleteFile', $quotationModel->quote_id) }}", {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        file: fileUrl
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'File deleted successfully') {
                        fileContainer.remove(); // ลบองค์ประกอบของไฟล์ออกจาก DOM
                        alert(data.message); // แจ้งเตือนการลบสำเร็จ
                    } else {
                        alert('ไม่สามารถลบไฟล์ได้');
                    }
                })
                .catch(error => console.error('Error deleting file:', error));
        }
    </script>
</div>
