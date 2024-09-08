<!DOCTYPE html>
<html>
<head>
    <title>pdf</title>
    <meta http-equiv="Content-Language" content="th" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: 'sarabun_new', sans-serif;
            font-size: 20px;
            margin-top: 0; 
            padding-top: 0;
        },
        table {
            border-collapse: collapse; /* รวมขอบของเซลล์ */
            margin: 20px; /* ระยะห่างรอบตาราง */
        }
        td {
            border: 2px solid #f9c68f; /* สีกรอบ */
           /* padding: 10px; /* ระยะห่างระหว่างข้อความกับกรอบ */
            background-color: #fff; /* สีพื้นหลัง */
            border-radius: 5px; /* มุมกรอบที่มน */
            text-align: left; /* จัดข้อความให้อยู่กลาง */
            font-size: 18px
        }
       
        
    </style>
</head>
<body style="margin-top: 0px; padding-top: 0;">
    <header>
        <div style="width: 15%; float: left; padding: 10px;">
            <img src="{{asset('logo/Logo-docs.png')}}" alt="">
        </div>
    
        <div style="width: 50%; float: left; padding: 0px;">
            <h5>บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)</h5>
            <div style="padding-top: -30px;">
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
    
        <div style="width: 30%; float: left; padding: 0px;">
            <div class="text-center pt-6 " style="padding-left: 73px">
                <h5>ใบจองทัวร์ / ใบเสนอราคา</h5>
            </div>
           
            <div class="text-center pt-6 " style="padding-left: 80px; padding-top: -55px;">
                <h5><b>Booking / Quotation</b></h5>
            </div>
            <div class="" style="padding-left: 67px; padding-top: -55px;">
                <h5><b>สำหรับลูกค้า </b> <span style="font-size: 14px">(ไม่ใช่ใบกำกับภาษี)</span></h5>  
            </div>
            <div style="margin-top: -45px; text-align: right;">
                <h4 style="background-color: #f9c68f; display: inline-block; padding-left: 73px"><b>{{$quotationModel->quote_number}}</b></h4>
            </div>
            
            
        </div>
        <div style="margin-top: -35px">
            <table style="margin-right: -35px; margin-left: -35px;">
                <tr>
                    {{-- <td style="width: 100px; padding-left: 5px; border-right: none;">
                        <p><b>Customer ID:</span></p>
                        <p><b>Name:</b> </p>
                        <p><b>Address:</b> </p>
                        <p><b>Mobile:</b> </p>
                        <p><b>Fax:</b></p>
                        <p><b>Email:</b></p>
                    </td>
                    <td style="width: 500px; padding-left: 5px; border-left: none;">
                      <p><span>{{$customer->customer_number}}</span></p>
                      <p><span>{{$customer->customer_name}}</span></p>
                      <p><span>{{$customer->customer_address}}</span></p>
                      <p><span>{{$customer->customer_tel}}</span></p>
                      <p><span>{{$customer->customer_fax ? $customer->customer_fax : '-'}}</span></p>
                      <p><span>{{$customer->customer_email ? $customer->customer_email: '-' }}</span></p>
                     
                    </td>
                    
                    <td style="border: none;"></td>
                    <td style="width: 100px; padding-left: 5px; border-right: none;">
                        <h4><b>Date:</b></h4>
                        <h4><b>Booking No:</b></h4>
                        <h4><b>Sale:</b></h4>
                        <h4><b>Email:</b></h4>
                        <h4><b>Tour Code:</b></h4>
                        <h4><b>Airline:</b></h4>

                    </td>

                    <td style="width: 300px; padding-left: 5px; border-left: none; background-color: #f9c68f;">
                        <p><span>{{thaidate('j F Y',$quotationModel->quote_date)}}</span></p>
                        <p><span>{{$quotationModel->quote_booking}}</span></p>
                        <p><span>{{$sale->name}}</span></p>
                        <p><span>{{$sale->email}}</span></p>
                        <p><span>{{$quotationModel->quote_tour_code}}</span></p>
                        <p ><span>{{$airline->travel_name }}</span></p>
                       
                      </td> --}}

                      <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none;">
                        <p><b>Customer ID:</span></p>
                     </td>
                    
                     <td style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none;">
                        <p><span>{{$customer->customer_number}}</span></p>
                     </td>
                     <td style="border: none;"></td>
                     <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none;">
                        <h4><b>Date:</b></h4>
                     </td>
                     
                     <td style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none;">
                        <p><span>{{thaidate('j F Y',$quotationModel->quote_date)}}</span></p>
                     </td>
                </tr>
                <tr>
                    <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <p><b>Name :</span></p>
                     </td>
                    
                     <td style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none;">
                        <p><span>{{$customer->customer_name}}</span></p>
                     </td>
                     <td style="border: none;"></td>
                     <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <h4><b>Boonking No:</b></h4>
                     </td>
                     
                     <td style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; border-top: none;">
                        <p><span>{{$quotationModel->quote_booking}}</span></p>
                     </td>
                </tr>
                <tr>
                    <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <p><b>Address:</span></p>
                     </td>
                    
                     <td style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none;">
                        <p><span>{{$customer->customer_address}}</span></p>
                     </td>
                     <td style="border: none;"></td>
                     <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <h4><b>Sale:</b></h4>
                     </td>
                     
                     <td style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; border-top: none;">
                        <p><span>{{$sale->name}}</span></p>
                     </td>
                </tr>
                <tr>
                    <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <p><b>Mobile:</span></p>
                     </td>
                    
                     <td style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none;">
                        <p><span>{{$customer->customer_tel}}</span></p>
                     </td>
                     <td style="border: none;"></td>
                     <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <h4><b>Email:</b></h4>
                     </td>
                     
                     <td style="width: 150px; padding-left: 5px; border-left: none; border-bottom: none; border-top: none;">
                        <p><span>{{$sale->email}}</span></p>
                     </td>
                </tr>
                <tr>
                    <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <p><b>Fax:</span></p>
                     </td>
                    
                     <td style="width: 400px; padding-left: 5px; border-left: none;  border-bottom: none; border-top: none;">
                        <p><span>{{$customer->customer_fax}}</span></p>
                     </td>
                     <td style="border: none;"></td>
                     <td style="width: 100px; padding-left: 5px; border-right: none;  border-bottom: none; border-top: none;">
                        <h4><b>Tour Code:</b></h4>
                     </td>
                     
                     <td style="width: 150px; padding-left: 5px5px; border-left: none; border-bottom: none; border-top: none;">
                        <p><span>{{$quotationModel->quote_tour_code}}</span></p>
                     </td>
                </tr>
                <tr style="padding: 3px">
                    <td style="width: 100px; padding-left: 5px; border-right: none;  border-top: none;">
                        <p><b>Email:</span></p>
                     </td>
                    
                     <td style="width: 400px; padding-left: 5px; border-left: none; border-top: none;">
                        <p><span>{{$customer->customer_email}}</span></p>
                     </td>
                     <td style="border: none;"></td>
                     <td style="width: 100px; padding-left: 5px; border-right: none;   border-top: none;">
                        <h4><b>Airline:</b></h4>
                     </td>
                     
                     <td style="width: 150px; padding: 0; text-align: center; border-left: none; border-top: none; background-color: #f9c68f;">
                        <p style="margin: 0; padding: 10px;">
                            <span>{{$airline->travel_name}}</span>
                        </p>
                    </td>
                   
                    
                    
                    
                </tr>
              
                
            </table>
        </div>

        

    </header>
    
</body>
</html>
