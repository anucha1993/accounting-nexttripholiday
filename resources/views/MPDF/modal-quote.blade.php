<div class="card">
    <div class="card-body">
        <form id="sendEmailForm" method="post">
            @csrf
            <div class="row">
                <h4>ส่งเมล์ ใบเสนอราคา # {{ $quotationModel->quote_number }}</h4>
                <hr>
                <div class="col-md-12 mb-3">
                    <label for="">Subject</label>
                    <input type="text" name="subject" class="form-control" value="ใบเสนอราคา Next Trip Holiday">
                </div>
                <div class="col-md-12 mb-3">
                    <label for="">Mail</label>
                    <input type="email" name="email" value="{{ $customer->customer_email }}" class="form-control"
                        placeholder="Email" required>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-info float-end"><i class="fas fa-paper-plane"></i> Send</button>
            </div>
        </form>
    </div>
</div>

<script>
   document.getElementById("sendEmailForm").addEventListener("submit", function(event) {
    event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

    let formData = new FormData(this); // รับข้อมูลจากฟอร์ม

    Swal.fire({
        title: "กำลังส่งอีเมล...",
        html: "กรุณารอสักครู่...",
        allowOutsideClick: false, // ป้องกันไม่ให้ปิดโดยคลิกข้างนอก
        showConfirmButton: false, // ซ่อนปุ่ม OK ขณะรอ
        onBeforeOpen: () => {
            Swal.showLoading(); // แสดงสัญลักษณ์การโหลด (วงกลมหมุน)
        }
    });

    // เรียก Ajax เพื่อส่งอีเมล
    fetch("{{ route('mpdf.quote.sendPdf', $quotationModel->quote_id) }}", {
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
