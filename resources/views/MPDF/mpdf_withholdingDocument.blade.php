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
            top: 125px;
            left: 70px;
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }
        .text-company-address {
            position: absolute;
            top: 152px;
            left: 77px;
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }

        .text-company-tax {
            position: absolute;
            top: 108px;
            left: 453px;
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }

        .text-customer-tax {
            position: absolute;
            top: 188px;
            left: 453px;
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif;
            color: rgb(0, 0, 0);
            display: inline-block;
        }
        .group1 {
            letter-spacing: 8px; /* ปรับระยะห่างระหว่างตัวเลขในกลุ่มแรก */
        }
        .group2 {
            letter-spacing: 8px; /* ปรับระยะห่างในกลุ่มที่สอง */
        }
        .group3 {
            letter-spacing: 7px; /* ปรับระยะห่างในกลุ่มที่สาม */
        }
        .group4 {
            letter-spacing: 8px; /* ปรับระยะห่างในกลุ่มที่สี่ */
        }
        .text-customer {
            position: absolute;
            top: 212px; /* ปรับตำแหน่งแนวตั้ง */
            left: 75px; /* ปรับตำแหน่งแนวนอน */
            font-size: 14px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-customer-addr {
            position: absolute;
            top: 243px; /* ปรับตำแหน่งแนวตั้ง */
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
            top: 295px; /* ปรับตำแหน่งแนวตั้ง */
            left: 475px; /* ปรับตำแหน่งแนวนอน */
            font-size: 20px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-DejaVuSans-3 {
            position: absolute;
            top: 273px; /* ปรับตำแหน่งแนวตั้ง */
            left: 565px; /* ปรับตำแหน่งแนวนอน */
            font-size: 20px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-no-ref {
            position: absolute;
            top: 753px; /* ปรับตำแหน่งแนวตั้ง */
            left: 130px; /* ปรับตำแหน่งแนวนอน */
            font-size:14px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }

        .text-date {
            position: absolute;
            top: 778px; /* ปรับตำแหน่งแนวตั้ง */
            left: 453px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
       
        .text-total {
            position: absolute;
            top: 778px; /* ปรับตำแหน่งแนวตั้ง */
            right: 164px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-price {
            position: absolute;
            top: 778px; /* ปรับตำแหน่งแนวตั้ง */
            right: 76px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-total-1 {
            position: absolute;
            top: 800px; /* ปรับตำแหน่งแนวตั้ง */
            right: 164px;/* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-price-1 {
            position: absolute;
            top: 800px; /* ปรับตำแหน่งแนวตั้ง */
            right: 76px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .text-total-text {
            position: absolute;
            top: 830px; /* ปรับตำแหน่งแนวตั้ง */
            right: 359px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
        }
        .date-create {
            position: absolute;
            top: 934px; /* ปรับตำแหน่งแนวตั้ง */
            right: 320px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
           
        }
        .year-create {
            position: absolute;
            top: 934px; /* ปรับตำแหน่งแนวตั้ง */
            right: 205px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
            font-family: 'sarabun_new', sans-serif; /* ใช้ฟอนต์อื่น */
            color: rgb(0, 0, 0);
            display: inline; /* บังคับให้ข้อความแสดงในบรรทัดเดียว */
            letter-spacing: 0; /* ระยะห่างระหว่างตัวอักษร */
            line-height: 1; /* ปรับระยะห่างระหว่างบรรทัด */
           
        }
        .month-create {
            position: absolute;
            top: 934px; /* ปรับตำแหน่งแนวตั้ง */
            right: 270px; /* ปรับตำแหน่งแนวนอน */
            font-size:18px;
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
        <img src="{{URL::asset('MPDF/withholding_std.svg')}}" alt="Image" class="image">
    </div>

    <div class="text-overlay-no">
        <span>10 &nbsp;</span>
    </div>
    <div class="text-overlay-no-2">
        <span>147 &nbsp;</span>
    </div>
    <div class="text-company-tax">
        <span>0 &nbsp; 1 &nbsp;1 &nbsp;5 &nbsp;5&nbsp; &nbsp;5&nbsp; 6&nbsp; 0&nbsp; 1&nbsp;3 &nbsp; 6&nbsp; 5 &nbsp; &nbsp;8</span>
    </div>



    <div class="text-company">
        <span>บจก. เน็กซ์ ทริป ฮอลิเดย์ &nbsp;</span>
    </div>
    <div class="text-company-address">
        <span>222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250 &nbsp;</span>
    </div>
    
 

    <div class="text-customer">
        <span>{{$customer->customer_name}} &nbsp;</span>
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
        <span>{{$customer->customer_address}} &nbsp;</span>
    </div>

    <div class="text-no">
        <span><b>1</b>&nbsp;</span>
    </div>
    <!-- เครื่องหมายถูก (✔) ภงด 53 -->
    <div class="text-DejaVuSans-53">
        <span style="font-family: DejaVuSans; " class="">&#10003;</span> 
    </div>
    <!-- เครื่องหมายถูก (✔) ภงด 53 -->
    <div class="text-DejaVuSans-3">
        <span style="font-family: DejaVuSans; " class="">&#10003;</span> 
    </div>

    <div class="text-no-ref">
        <span><b>{{$item->income_type}}</b>&nbsp;</span>
    </div>

    {{-- <div class="text-date">
        <span><b>{{ thaidate('j M Y', $inputTaxModel->input_tax_date) }}</b>&nbsp;</span>
    </div>

    <div class="text-total">
        <span><b>{{number_format($inputTaxModel->input_tax_service_total,2) }}</b>&nbsp;</span>
    </div>

    <div class="text-price">
        <span><b>{{number_format($inputTaxModel->input_tax_withholding,2) }}</b>&nbsp;</span>
    </div>

    <div class="text-total-1">
        <span><b>{{number_format($inputTaxModel->input_tax_service_total,2) }}</b>&nbsp;</span>
    </div>

    <div class="text-price-1">
        <span><b>{{number_format($inputTaxModel->input_tax_withholding,2) }}</b>&nbsp;</span>
    </div>
    <div class="text-total-text">
        <span><b>@bathText($inputTaxModel->input_tax_withholding)</b>&nbsp;</span>
    </div>

    <div class="date-create">
        <span><b>{{ thaidate('j', $inputTaxModel->input_tax_date) }}</b>&nbsp;</span>
    </div>
    <div class="month-create">
        <span><b>{{ thaidate('M', $inputTaxModel->input_tax_date) }}</b>&nbsp;</span>
    </div>
    <div class="year-create">
        <span><b>{{ thaidate('Y', $inputTaxModel->input_tax_date) }}</b>&nbsp;</span>
    </div> --}}
    

     <script src="{{ URL::asset('template/assets/libs/jquery/dist/jquery.min.js') }}"></script>
     <script>
        $(document).ready(function() {
            // เรียกหน้าต่างพรีวิวการพิมพ์ทันทีที่โหลดหน้า
            window.print();
        });
    </script>
</body>

</html>
