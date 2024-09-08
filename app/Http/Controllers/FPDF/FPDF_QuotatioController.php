<?php

namespace App\Http\Controllers\FPDF;

use Fpdf\Fpdf;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\customers\customerModel;
use App\Models\quotations\quotationModel;

class FPDF_QuotatioController extends Fpdf
{
    private $quotes;
    private $customer;

    function Header()
    {
        $this->Image('logo/Logo-docs.png', 10, 10, 35);
        $this->AddFont('THSarabunNew', '', 'THSarabunNew.php');
        $this->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
        $this->SetFont('THSarabunNew', 'B', 11);

        $this->Cell(37);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'บริษัท เน็กซ์ ทริป ฮอลิเดย์ จำกัด (สำนักงานใหญ่)'), 0, 1, 'L');

        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(157);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ใบจองทัวร์/ใบเสนอราคา'), 0, 1, 'L');

        $this->ln(5);
        $this->SetFont('THSarabunNew', '', 11);
        $this->Cell(37);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', '222/2 โกลเด้นทาวน์ บางนา-สวนหลวง แขวงดอกไม้ เขตประเวศ กทม 10250'), 0, 1, 'L');
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(158);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Booking / Quotation'), 0, 1, 'L');

        $this->ln(3);
        $this->SetFont('THSarabunNew', '', 11);
        $this->Cell(37);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'โทรศัพท์:02-136-9144 อัตโนมัติ 16 คู่สาย โทรสาร(Fax): 02-136-9146'), 0, 1, 'L');
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->ln(2);
        $this->Cell(152);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'สำหรับลูกค้า '), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(169);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', '(ไม่ใช่ใบกำกับภาษี)'), 0, 1, 'L');

        $this->ln(1);
        $this->SetFont('THSarabunNew', '', 11);
        $this->Cell(37);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Hotline: 091-091-6364 ,091-091-6463'), 0, 1, 'L');
        $this->ln(3);
        $this->Cell(37);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'TAT License: 11/07440 ,TTAA License:1469'), 0, 1, 'L');
        $this->ln(3);
        $this->Cell(37);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Website: https://www.nexttripholiday.com , Email : nexttripholiday@gmail.com'), 0, 1, 'L');

        $this->ln(-3);
        $this->SetFont('Arial', 'B', 10);
        // เว้นระยะ 158 หน่วย
        $this->Cell(142);
        $this->SetFillColor(249, 198, 143); // กำหนดสีพื้นหลั
        $this->Cell(50, 5, iconv('UTF-8', 'TIS-620//IGNORE', $this->quotes->quote_number), 0, 1, 'R', true);
        $this->SetTextColor(0, 0, 0);
    }

    public function generatePDF(quotationModel $quotationModel)
    {
        //dd($quotationModel);
        $this->quotes = $quotationModel;
        $this->customer = customerModel::where('customer_id', $quotationModel->customer_id)->first();
        // สร้างคลาส PDF แบบกำหนดเอง
        $this->AddPage('P', 'A4');
        $this->AddFont('THSarabunNew', '', 'THSarabunNew.php');
        $this->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
        $this->SetFont('THSarabunNew', 'B', 11);

        // $this->Rect(5, 213, 210,15, 'D');

        $this->SetDrawColor(249, 198, 143);
        $this->SetLineWidth(0.5);
        // สร้างกรอบสี่เหลี่ยมที่ตำแหน่ง (X = 10, Y = 10) กว้าง 100 หน่วย สูง 20 หน่วย
        // ฟังก์ชัน Rect(x, y, width, height, style)
        // โดยใช้ 'D' เพื่อวาดเส้นกรอบ (Draw) เท่านั้น
        $lineHeight = 5; // ความสูงของแต่ละบรรทัด
        $width = 100;

        // $height = $this->GetMultiCellHeight($width, $lineHeight, $this->customer->customer_address);

        //$this->Rect(10, $this->GetY(), $width, $height, 'D'); // 'D' หมายถึงการวาดกรอบโดยไม่เติมสีพื้นหลัง 
      

        // // Customer ID
        $this->ln(5);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-4);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Customer ID: '), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(15);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', $this->customer->customer_number), 0, 1, 'L');
        // Date:
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(120);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Date:'), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(142);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', $this->convertDateToThai(date('Y-m-d', strtotime($quotationModel->quote_date)))), 0, 1, 'L');

        // Customer Name
        $this->ln(5);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-4);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Name: '), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(15);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', $this->customer->customer_name), 0, 1, 'L');
        // Booking No:
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(120);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Booking No:'), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(142);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', $quotationModel->quote_booking), 0, 1, 'L');
        // Customer Address
        // คำนวณความสูงของ Customer Address
        $this->ln(3);
        $this->SetX(15);  // ตั้งค่า X ตำแหน่งที่ต้องการ
        $this->SetFillColor(999, 999, 999); 
        // ข้อความ Address
        $this->Cell(9);
        $addressText = iconv('UTF-8', 'TIS-620//IGNORE', $this->customer->customer_address);
        $width = 100;
        $lineHeight = 5;
        // บันทึกตำแหน่ง Y ก่อน
        $yStart = $this->GetY();
        // แสดงข้อมูลใน MultiCell
        $this->MultiCell($width, $lineHeight, $addressText, 0, 'L', true);
        // บันทึกตำแหน่ง Y หลัง
        $yEnd = $this->GetY();
        // คำนวณความสูงของ MultiCell
        $height = 40;
        if($height <= 40 ){
        $height = 40;
        }else{
            $height = $yEnd - $yStart;
        }
        // วาดกรอบสี่เหลี่ยมที่ตรงตามความสูงของ MultiCell
        $this->Rect(5, 30, $width + 20, $height, 'D'); // กำหนดความกว้างและความสูงที่คำนวณได้
        $this->Rect(130, 30, 75, 40, 'D'); // กรอบที่สอง (ถ้าต้องการ)

        // แสดงข้อมูลใน MultiCell
        // Customer Name

        $this->ln($yStart-7.5);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-4);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Address: '), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);
        $this->SetXY(15, $yStart); // ตั้งค่า Y ตำแหน่งเริ่มต้น
        $this->SetFillColor(999, 999, 999); 
        $this->Cell(9);
        $this->MultiCell($width, $lineHeight, $addressText, 0, 'L', true);
        
        $this->Ln();
        $this->SetFillColor(999, 999, 999); 
        $this->Output();
    }

    function Footer()
    {
        // Go to 1.5 cm from bottom
        $this->SetY(-105);

        // Select Arial italic 8
        $this->AddFont('THSarabunNew', '', 'THSarabunNew.php');
        $this->AddFont('THSarabunNew', 'B', 'THSarabunNew_b.php');
        $this->AddFont('THSarabunNew', 'I', 'THSarabunNew_i.php');

        $this->ln(3);
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(5);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'หมายเหตุ *'), 0, 1, 'L');

        $this->ln(-2);
        $this->Cell(20);
        $this->SetFillColor(999, 999, 999); //Background color of header
        // $this->MultiCell(0, 5, iconv('UTF-8', 'TIS-620//IGNORE', '-หากไม่ชำระเงินตามกำหนดด้านล่าง ทางบริษัทฯ ขอสงวนสิทธิ์ในการตัดที่นั่งโดยไม่แจ้งให้ทราบล่วงหน้า'), 0, 1, 'L');
        $this->MultiCell(180, 5, iconv('UTF-8', 'TIS-620//IGNORE', '-หากไม่ชำระเงินตามกำหนดด้านล่าง ทางบริษัทฯ ขอสงวนสิทธิ์ในการตัดที่นั่งโดยไม่แจ้งให้ทราบล่วงหน้า -หากชำระมัดจำมาแล้วท่านไม่ชำระส่วนที่เหลือ ขออนุญาตยึดเงินมัดจำตามเงื่อนไขบริษัท -สำเนาพาสปอร์ตกรุณาจัดส่งให้บริษัทก่อนเดินทาง 30 วันผ่านทางไลน์หรืออีเมลล์ -ใบนัดหมายการเดินทางจะจัดส่งให้ก่อนการเดินทางระยะเวลา 1-3 วัน'), 0, 1, 'L');
        $this->SetFont('THSarabunNew', '', 12);

        $this->ln(5);
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(5);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'วันที่ชำระมัดจำ'), 0, 1, 'L');
        $this->Cell(60);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ก่อนเวลา'), 0, 1, 'L');
        $this->Cell(120);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'จำนวนเงิน'), 0, 1, 'L');
        $this->Cell(180);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'บาท'), 0, 1, 'L');

        $this->SetDrawColor(249, 198, 143);
        $this->SetLineWidth(0.5);
        // สร้างกรอบสี่เหลี่ยมที่ตำแหน่ง (X = 10, Y = 10) กว้าง 100 หน่วย สูง 20 หน่วย
        // ฟังก์ชัน Rect(x, y, width, height, style)
        // โดยใช้ 'D' เพื่อวาดเส้นกรอบ (Draw) เท่านั้น
        $this->Rect(5, 208, 200, 15, 'D'); // 'D' หมายถึงการวาดกรอบโดยไม่เติมสีพื้นหลัง

        // กำหนดสีข้อความเป็นสีดำ
        $this->SetTextColor(0, 0, 0);

        $this->ln(5);
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(5);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'วันที่ชำระเต็ม'), 0, 1, 'L');
        $this->Cell(60);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ก่อนเวลา'), 0, 1, 'L');
        $this->Cell(120);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'จำนวนเงิน'), 0, 1, 'L');
        $this->Cell(180);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'บาท'), 0, 1, 'L');

        // $this->Rect(5, 213, 210,15, 'D');
        //เส้นสี่เหลี่ยม
        $this->Rect(5, 224.5, 65, 36, 'D');
        $this->Rect(72.5, 224.5, 65, 36, 'D');
        $this->Rect(140, 224.5, 65, 36, 'D');

        $this->ln(14);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-132);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'SALE NAME'), 0, 1, 'C');

        //เส้นตรงแนวนนอ
        $this->SetDrawColor(0, 0, 0);
        // $this->SetDrawColor(249, 198, 143);  // สีเส้นกรอบ (สีส้มอ่อน)
        $this->SetLineWidth(0.1); // ความหนาของเส้น 0.5 หน่วย
        // ฟังก์ชัน Line(x1, y1, x2, y2)
        $this->Line(10, 240, 65, 240);
        $this->Line(78, 240, 132, 240);

        $this->ln(14);
        $this->Cell(-132);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'Sale / Operation'), 0, 1, 'C');
        $this->Cell(-1);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ผู้วางบิล'), 0, 1, 'C');

        $this->ln(5);
        $this->Cell(-132);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'วันที่ 26 มิถุนายน 2567'), 0, 1, 'C');
        $this->Cell(-1);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'วันที่ 26 มิถุนายน 2567'), 0, 1, 'C');
        $this->Cell(135);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ผู้อนุมัติ'), 0, 1, 'C');
        $this->ln(5);
        $this->Cell(135);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'วันที่ 26 มิถุนายน 2567'), 0, 1, 'C');

        $this->ln(8);
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(-3);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'กรุณาชำระเงินค่าทัวร์ หรือตั๋วเครื่องบินโดยการโอนเงิน'), 0, 1, 'L');
        $this->ln(5);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-3);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ชื่อบัญชี บจก. เน็กซ์ ทริป ฮอลิเดย์'), 0, 1, 'L');

        $this->ln(5);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-3);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ธนาคาร'), 0, 1, 'L');
        $this->Cell(50);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ประเภทบัญชี'), 0, 1, 'L');
        $this->Cell(100);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'สาขา'), 0, 1, 'L');
        $this->Cell(150);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'เลขที่บัญชี'), 0, 1, 'L');

        $this->ln(4);
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(2);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'กรุงศรีอยุธยา'), 0, 1, 'L');
        $this->Cell(50);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'ออมทรัพย์'), 0, 1, 'L');
        $this->Cell(100);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'เมกาบางนา'), 0, 1, 'L');
        $this->Cell(150);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', '688-1-28842-5'), 0, 1, 'L');

        $this->ln(5);
        $this->SetFont('THSarabunNew', 'B', 12);
        $this->Cell(-3);
        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'การแจ้งชำระเงิน'), 0, 1, 'L');

        $this->ln(4);
        $this->SetFont('THSarabunNew', '', 12);
        $this->Cell(5);

        $this->Cell(0, 0, iconv('UTF-8', 'TIS-620//IGNORE', 'สามารถแจ้งได้ทุกช่องทาง Line :@nexttripholiday ,อีเมล:nexttripholiday@gmail.com หรือทางไลน์กับพนักงานขายที่ท่านทำการจอง'), 0, 1, 'L');

        // แสดงข้อความใต้เส้น
        $this->SetXY(10, 25); // ตั้งตำแหน่งข้อความ
    }

    // ฟังก์ชันในการแปลงวันที่เป็นภาษาไทย
    function convertDateToThai($date)
    {
        // รายชื่อเดือนภาษาไทย
        $thai_months = [
            '01' => 'มกราคม',
            '02' => 'กุมภาพันธ์',
            '03' => 'มีนาคม',
            '04' => 'เมษายน',
            '05' => 'พฤษภาคม',
            '06' => 'มิถุนายน',
            '07' => 'กรกฎาคม',
            '08' => 'สิงหาคม',
            '09' => 'กันยายน',
            '10' => 'ตุลาคม',
            '11' => 'พฤศจิกายน',
            '12' => 'ธันวาคม',
        ];

        // แปลงวันที่เป็นรูปแบบที่ต้องการ
        $year = date('Y', strtotime($date)) + 543; // แปลงเป็นปีพุทธศักราช
        $month = date('m', strtotime($date));
        $day = date('d', strtotime($date));

        $thai_month = $thai_months[$month];

        return "$day $thai_month $year";
    }

     // ฟังก์ชันคำนวณความสูงของ MultiCell
     function GetMultiCellHeight($width, $lineHeight, $text)
     {
         // การคำนวณจำนวนบรรทัดที่จำเป็น
         $this->SetFont('THSarabunNew', '', 12);
         $this->SetX(15); // ตั้งค่า X ตำแหน่งที่ต้องการ
         $this->MultiCell($width, $lineHeight, $text, 0, 'L', false);
         $height = $this->GetY() - $this->GetYStart(); // ความสูงที่ใช้จริง
         return $height;
     }

    

}
