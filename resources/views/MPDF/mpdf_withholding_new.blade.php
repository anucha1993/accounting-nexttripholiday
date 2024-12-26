<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .container {
            position: relative;
            width: 100%;
        }
        .image {
            width: 100%;
            height: auto;
        }
        /* เล่มที่ */
        .text-overlay-no {
            position: absolute;
            top: 65px; /* ปรับตำแหน่งแนวตั้ง */
            left: 630px; /* ปรับตำแหน่งแนวนอน */
            font-size: 16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
         /* เล่มที่ */
         .text-overlay-no-2 {
            position: absolute;
            top: 85px; /* ปรับตำแหน่งแนวตั้ง */
            left: 630px; /* ปรับตำแหน่งแนวนอน */
            font-size: 16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-company {
            position: absolute;
            top: 143px;
            left: 70px;
            font-size: 16px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }
        .text-company-address {
            position: absolute;
            top: 174px;
            left: 77px;
            font-size: 16px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }

        .text-company-tax {
            position: absolute;
            top: 118px;
            left: 498px;
            font-size: 18px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }

        .text-customer-tax {
            position: absolute;
            top: 210px;
            left: 500px;
            font-size: 16px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }
        .group1 {
            letter-spacing: 16px; /* ปรับระยะห่างระหว่างตัวเลขในกลุ่มแรก */
        }
        .group2 {
            letter-spacing: 12px; /* ปรับระยะห่างในกลุ่มที่สอง */
        }
        .group3 {
            letter-spacing: 11px; /* ปรับระยะห่างในกลุ่มที่สาม */
        }
        .group4 {
            letter-spacing: 16px; /* ปรับระยะห่างในกลุ่มที่สี่ */
        }
        .text-customer {
            position: absolute;
            top: 242px; /* ปรับตำแหน่งแนวตั้ง */
            left: 75px; /* ปรับตำแหน่งแนวนอน */
            font-size: 16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-customer-addr {
            position: absolute;
            top: 276px; /* ปรับตำแหน่งแนวตั้ง */
            left: 78px; /* ปรับตำแหน่งแนวนอน */
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-no {
            position: absolute;
            top: 276px; /* ปรับตำแหน่งแนวตั้ง */
            left: 133px; /* ปรับตำแหน่งแนวนอน */
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-DejaVuSans-53 {
            position: absolute;
            top: 333px; /* ปรับตำแหน่งแนวตั้ง */
            left: 520px; /* ปรับตำแหน่งแนวนอน */
            font-size: 20px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-DejaVuSans-3 {
            position: absolute;
            top: 308; /* ปรับตำแหน่งแนวตั้ง */
            left: 622px; /* ปรับตำแหน่งแนวนอน */
            font-size: 20px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-DejaVuSans {
            position: absolute;
            top: 942px; /* ปรับตำแหน่งแนวตั้ง */
            left: 109px; /* ปรับตำแหน่งแนวนอน */
            font-size: 20px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-no-ref {
            position: absolute;
            top: 843px; /* ปรับตำแหน่งแนวตั้ง */
            left: 130px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-date {
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            position: absolute; /* กำหนดให้ text ลอยทับ */
            top: 838px; /* ปรับตำแหน่งจากบนลงล่าง */
            left: 440px; /* ปรับตำแหน่งจากซ้ายไปขวา */
            color: #000; /* สีข้อความ */
            font-weight: bold;
            font-size: 16px;
        }
        .date-create {
            position: absolute;
            top: 1007px; /* ปรับตำแหน่งแนวตั้ง */
            left: 455px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
           
        }
       
        .month-create {
            position: absolute;
            top: 1007px; /* ปรับตำแหน่งแนวตั้ง */
            left: 505px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
           
        }

        .year-create {
            position: absolute;
            top: 1007px; /* ปรับตำแหน่งแนวตั้ง */
            left: 575px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
           
        }
        .text-total {
            position: absolute;
            top: 840px; /* ปรับตำแหน่งแนวตั้ง */
            right: 153px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-price {
            position: absolute;
            top: 840px; /* ปรับตำแหน่งแนวตั้ง */
            right: 60px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-total-1 {
            position: absolute;
            top: 865px; /* ปรับตำแหน่งแนวตั้ง */
            right: 153px;/* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-price-1 {
            position: absolute;
            top: 865px; /* ปรับตำแหน่งแนวตั้ง */
            right: 60px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-total-text {
            position: absolute;
            top: 895px; /* ปรับตำแหน่งแนวตั้ง */
            right: 359px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .image-signature {
            position: absolute;
            top: 915px; /* ปรับตำแหน่งแนวตั้ง */
            left: 450px; /* ปรับตำแหน่งแนวนอน */
  
        }

        .text-book {
            position: absolute;
            top: 78px; /* ปรับตำแหน่งแนวตั้ง */
            right: 65px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-doc-no {
            position: absolute;
            top: 97px; /* ปรับตำแหน่งแนวตั้ง */
            right: 75px; /* ปรับตำแหน่งแนวนอน */
            font-size:16px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

       
        
    </style>
</head>
<body>
    <div class="container">
        <img src="{{URL::asset('withholding/1/2.svg')}}" alt="Image" class="image">
    </div>

    <div class="text-book">
        <span><b>{{$WithholdingTaxDocument->book_no}}</b>&nbsp;</span>
    </div>
    <div class="text-doc-no">
        <span><b>{{$WithholdingTaxDocument->document_no}}</b>&nbsp;</span>
    </div>

    <div class="text-company-tax">
        <span><b>0 &nbsp;  &nbsp; &nbsp;1 &nbsp; 1 &nbsp; 5 &nbsp; &nbsp;5&nbsp; &nbsp;  &nbsp; 5&nbsp; &nbsp; 6 &nbsp; 0 &nbsp; 1  &nbsp; &nbsp;3 &nbsp;  &nbsp; 6 &nbsp; 5 &nbsp; &nbsp;  &nbsp; 8</b></span>
    </div>



    <div class="text-company">
        <span><b>บจก. เน็กซ์ ทริป ฮอลิเดย์</b> &nbsp;</span>
    </div>
    <div class="text-company-address">
        <span><b>222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250</b> &nbsp;</span>
    </div>
    
 

    <div class="text-customer">
        <span><b>{{$customer->customer_name}}</b> &nbsp;</span>
    </div>

    <div class="text-customer-tax">
        <!-- สมมติว่า $wholesale->textid = '0125558000987' -->
        <span class="group1"><b>{{ substr($customer->customer_texid, 0, 1) }}</b></span>
        <span class="group2"><b>{{ substr($customer->customer_texid, 1, 4) }}</b></span>
        <span class="group3"><b>{{ substr($customer->customer_texid, 5, 5) }}</b></span>
        <span class="group4"><b>{{ substr($customer->customer_texid, 10, 2) }}</b></span>
        <span class="group1"><b>{{ substr($customer->customer_texid, 12, 1) }}</b></span>
    </div>

    <div class="text-customer-addr">
        <span><b>{{$customer->customer_address}}</b> &nbsp;</span>
    </div>

    {{-- <div class="text-no">
        <span><b>1</b>&nbsp;</span>
    </div> --}}
    <!-- เครื่องหมายถูก (✔) ภงด 53 -->
    <div class="text-DejaVuSans-53">
        <span style="font-family: DejaVuSans; " class="">&#10003;</span> 
    </div>
    <!-- เครื่องหมายถูก (✔) ภงด 53 -->
    <div class="text-DejaVuSans-3">
        <span style="font-family: DejaVuSans; " class="">&#10003;</span> 
    </div>


     <div class="text-DejaVuSans">
        <span style="font-family: DejaVuSans; " class="">&#10003;</span> 
    </div>


    <div class="text-no-ref">
        <span><b>{{$item->income_type}}</b>&nbsp;</span>
    </div>
    <div class="text-date">
        <span><b>{{ thaidate('j M Y', $WithholdingTaxDocument->document_date) }}</b>&nbsp;</span>
    </div>
    
    
    <div class="date-create">
        <span><b>{{ thaidate('j', $WithholdingTaxDocument->document_date) }}</b>&nbsp;</span>
    </div>
    <div class="month-create">
        <span><b>{{ thaidate('M', $WithholdingTaxDocument->document_date) }}</b>&nbsp;</span>
    </div>
    <div class="year-create">
        <span><b>{{ thaidate('Y', $WithholdingTaxDocument->document_date) }}</b>&nbsp;</span>
    </div> 
    
    <div class="text-total">
        <span><b>{{number_format($WithholdingTaxDocument->total_amount,2) }}</b>&nbsp;</span>
    </div>
    
    <div class="text-price">
        <span><b>{{number_format($WithholdingTaxDocument->total_withholding_tax,2) }}</b>&nbsp;</span>
    </div>
    
    <div class="text-total-1">
        <span><b>{{number_format($WithholdingTaxDocument->total_amount,2) }}</b>&nbsp;</span>
    </div>
    
    <div class="text-price-1">
        <span><b>{{number_format($WithholdingTaxDocument->total_withholding_tax,2) }}</b>&nbsp;</span>
    </div>
    <div class="text-total-text">
        <span><b>@bathText($WithholdingTaxDocument->total_withholding_tax)</b>&nbsp;</span>
    </div>
    
    <div class="image-signature">
        <img src="{{URL::asset($imageSignature->image_signture_path)}}" alt="Image" class="image" style="width: 80%">
    </div>
    

     <script src="{{ URL::asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
     <script>
        $(document).ready(function() {
            // เรียกหน้าต่างพรีวิวการพิมพ์ทันทีที่โหลดหน้า
            window.print();
        });
    </script>
</body>

</html>
