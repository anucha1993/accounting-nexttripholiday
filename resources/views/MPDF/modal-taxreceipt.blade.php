<div class="card">
    <div class="card-body">
        <form id="sendEmailForm" method="post">
            @csrf
            <div class="row">
                <h4>ส่งเมล์ ใบกำกับภาษี # {{ $taxReceipt->taxinvoice_number }}</h4>
                <hr>
                <div class="col-md-12 mb-3">
                    <label for="">Subject</label>
                    <input type="text" name="subject" class="form-control" value="ใบกำกับภาษี Next Trip Holiday">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="">Mail</label>
                    <input type="email" name="email" value="{{ $customer->customer_email }}" class="form-control"
                        placeholder="Email" required>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="border-bottom title-part-padding">
                    <h4 class="card-title mb-0">รายละเอียด</h4>
                </div>
                <textarea cols="80" id="testedit" name="text_detail" rows="15" data-sample="1" data-sample-short >
                     <p>เรียน คุณ {{ $customer->customer_name }}</p>
                    <p>ใบกำกับภาษีเลขที่ {{ $taxReceipt->taxinvoice_number }}</p>
                    <p>กรุณาตรวจสอบไฟล์แนบที่ส่งมาพร้อมกับอีเมลล์นี้</p>
                    <br>
                    
                    <p>**Email นี้ เป็น Email ตอบรับอัตโนมัติ ไม่สามารถตอบกลับได้</p>
                    <p>สอบถามรายละเอียดและจองทัวร์ได้ที่   Line: @nexttripholiday</p>

                    <p>ขอแสดงความนับถือ</p>
                    
                    <p>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</p>
                    <p>โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146</p>
                    <p>Hotline: 091-091-6364 ,091-091-6463</p>
                    <p>TAT License: 11/07440 ,TTAA License:1469</p>
                    <p>Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com</p>
                </textarea>
            </div>

            <div class="col-md-12">
                <button type="submit" class="btn btn-info float-end"><i class="fas fa-paper-plane"></i> Send</button>
            </div>
        </form>
    </div>
</div>

<!-- CKEditor initialization -->
<script data-sample="1">
    CKEDITOR.replace("testedit", {
      height: 300,
    });
</script>

<script>
    document.getElementById("sendEmailForm").addEventListener("submit", function(event) {
        event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

        // อัปเดตข้อมูลจาก CKEditor เข้าไปใน textarea ก่อนส่ง
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }

        let formData = new FormData(this); // รับข้อมูลจากฟอร์ม

        Swal.fire({
            title: "กำลังส่งอีเมล...",
            html: "กรุณารอสักครู่...",
            allowOutsideClick: false, // ป้องกันไม่ให้ปิดโดยคลิกข้างนอก
            showConfirmButton: false, // ซ่อนปุ่ม OK ขณะรอ
            didOpen: () => {
                Swal.showLoading(); // แสดงสัญลักษณ์การโหลด (วงกลมหมุน)
            }
        });

        // เรียก Ajax เพื่อส่งอีเมล
        fetch("{{ route('mpdf.taxreceipt.sendPdf', $invoiceModel->invoice_id) }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: formData
            })
            .then(response => response.json()) // ตรวจสอบว่า response เป็น JSON
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: "success",
                        title: "ส่งอีเมลสำเร็จ!",
                        text: "อีเมลได้ถูกส่งแล้ว"
                    }).then(() => {
                        // ปิด modal หลังจากกด OK
                        const modalEl = document.querySelector('#modal-mail-quote');
                        if (modalEl) {
                            const modalInstance = bootstrap.Modal.getInstance(modalEl) ||
                                new bootstrap.Modal(modalEl);
                            modalInstance.hide();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "เกิดข้อผิดพลาด",
                        text: "ไม่สามารถส่งอีเมลได้"
                    });
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire({
                    icon: "error",
                    title: "เกิดข้อผิดพลาด",
                    text: "ไม่สามารถส่งอีเมลได้"
                });
            });
    });
</script>
