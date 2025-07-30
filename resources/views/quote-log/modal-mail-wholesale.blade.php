<div class="card">
    <div class="card-body">
        <form id="sendEmailFormWholesale" method="post">
            @csrf
            <div class="row">
                <h4>ส่งเมล์ข้อมูลจองทัวร์โฮลเซล# {{ $quotationModel->quote_number }}</h4>
                <hr>
                <div class="col-md-12 mb-3">
                    <label for="">Subject</label>
                    <input type="text" name="subject" class="form-control" value="ข้อมูลจองทัวร์ Next Trip Holiday {{'ชื่อแพคเกจ:'.$quotationModel->quote_tour_name }}">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="">Mail</label>
                    <input type="email" name="email" value="{{ $quotationModel->quoteWholesale->email }}" class="form-control"
                        placeholder="Email" required>
                </div>
            </div>

            <div class="col-12 mb-3">
                <div class="border-bottom title-part-padding">
                    <h4 class="card-title mb-0">รายละเอียด</h4>
                </div>
                <textarea cols="80" id="testeditWholesale" name="text_detail" rows="15" data-sample="1" data-sample-short >
                    <p><strong>**Email นี้ เป็น Email ตอบรับอัตโนมัติ โฮลเซลไม่สามารถส่งหลักฐานการโอนเงินในนี้ได้</strong></p>
                    <p>ขอบคุณที่ไว้วางใจในการให้บริการของ&nbsp;Next Trip Holiday&nbsp;</p>
                    <br>
                   <table class="info-table w-100">
                     <tr>
                                        <td class="label">ชื่อลูกค้า:</td>
                                        <td class="value">{{ $customer->customer_name }}</td>
                                    </tr>
                                     <tr>
                                        <td class="label">เบอร์โทร:</td>
                                        <td class="value">{{ $customer->customer_tel ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">ชื่อแพคเกจ:</td>
                                        <td class="value">{{ $quotationModel->quote_tour_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">สายการบิน:</td>
                                        <td class="value">{{ $quotationModel->airline->travel_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">วันเดินทาง:</td>
                                        <td class="value">
                                            @if ($quotationModel->quote_date_start && $quotationModel->quote_date_end)
                                                {{ thaidate('j M Y', $quotationModel->quote_date_start) }} -
                                            {{ thaidate('j M Y', $quotationModel->quote_date_end) }}
                                            <small class="text-muted">({{ $quotationModel->quote_numday }})</small>
                                            @else
                                                -
                                            @endif
                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label">ผู้เดินทาง:</td>
                                        <td class="value">{{ $quotationModel->quote_pax_total ?: '-' }} ท่าน</td>
                                    </tr>
                                    <tr>
                                        <td class="label">โฮลเซลล์:</td>
                                        <td class="value">{{ $quotationModel->quoteWholesale->wholesale_name_th }}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">รหัสทัวร์:</td>
                                        <td class="value">
                                            {{ $quotationModel->quote_tour ?: $quotationModel->quote_tour_code }}</td>
                                    </tr>
                                </table>

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
    CKEDITOR.replace("testeditWholesale", {
      height: 300,
    });
</script>

<script>
    document.getElementById("sendEmailFormWholesale").addEventListener("submit", function(event) {
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
        fetch("{{ route('quote.sendWholesaleMail', $quotationModel->quote_id) }}", {
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
                        window.location.reload();
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
