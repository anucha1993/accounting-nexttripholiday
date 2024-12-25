<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>หน้าซอง</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'sarabun_new', sans-serif;
            font-size: 20px;
            margin-top: 0; 
            padding-top: 0;
             flex-grow: 1;
    
        },
        table {
            border-collapse: collapse; /* รวมขอบของเซลล์ */
            margin: 20px; /* ระยะห่างรอบตาราง */
        }
        td {
            border: 2px solid #ffaa50; /* สีกรอบ */
           /* padding: 10px; /* ระยะห่างระหว่างข้อความกับกรอบ */
            background-color: #fff; /* สีพื้นหลัง */
            border-radius: 5px; /* มุมกรอบที่มน */
            text-align: left; /* จัดข้อความให้อยู่กลาง */
            font-size: 18px
        }
        
        
       
        
    </style>
</head>
<body>
    <div style="width: 15%; padding: 5px; padding-top: -40px">
        <img src="{{asset('logo/Logo-docs.png')}}" alt="">
    </div>

    <div style="width: 50%; float: left; padding: 0px; padding-top: -10px">

        <h5>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</h5>
        <div style="padding-top: -35spx;">
            <span style="font-size: 14px; display: block; ">
                222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250
            </span>
        </div>
        <div style="padding-top: -7px;">
            <span style="font-size: 14px; display: block;">
                โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146
            </span>
        </div>
        <div style="padding-top: -7px;"> 
            <span style="font-size: 14px; display: block;">
                Hotline: 091-091-6364 ,091-091-6463
            </span>
        </div>
        <div style="padding-top: -7px;">
            <span style="font-size: 14px; display: block;">
                TAT License: 11/07440 ,TTAA License:1469
            </span>
        </div>
        <div style="padding-top: -7px;">
            <span style="font-size: 14px; display: block;">
                Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com
            </span>
        </div>
        
        
    </div>
  
    <div style="width: 50%; float: right; padding: 0px; padding-top: 150px">
        <span><b>กรุณานำส่ง</b></span>

        <h5 style="padding-top: -25px;">เรียน: {{$customer->customer_name}}</h5>
        <div style="padding-top: -35px;">
            <span style="font-size: 14px; display: block; ">
              ที่อยู่: {{$customer->customer_address}}
            </span>
        </div>
        <div style="padding-top: -7px;">
            <span style="font-size: 14px; display: block;">
                โทรศัพท์: {{$customer->customer_tel}}
            </span>
        </div>
        
       
        
        
    </div>

</body>
</html>
