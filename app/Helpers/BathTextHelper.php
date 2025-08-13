<?php

namespace App\Helpers;

class BathTextHelper
{
               public static function convert($number)
               {
                              // กำหนดค่าขั้นต้น
                              $bahtText = '';

                              // กำหนดการแปลงตัวเลขเป็นข้อความ
                              $numberStr = number_format($number, 2, '.', '');
                              list($baht, $satang) = explode('.', $numberStr);

                              $bahtText = self::readNumber($baht) . 'บาท';
                              if ($satang > 0) {
                                             $bahtText .= self::readNumber($satang) . 'สตางค์';
                              } else {
                                             $bahtText .= 'ถ้วน';
                              }

                              return $bahtText;
               }

    

    private static function readNumber($number)
    {
        $units = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        $levels = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน'];

        $isNegative = false;
        if (substr($number, 0, 1) === '-') {
            $isNegative = true;
            $number = substr($number, 1);
        }

        $number = ltrim(strval(intval($number)), '0');
        if ($number === '') {
            return 'ศูนย์';
        }

        $bahtText = '';
        $numberLength = strlen($number);
        $segments = [];

        // แบ่งตัวเลขเป็นกลุ่มละ 6 หลัก (หลักล้าน)
        while ($numberLength > 0) {
            $start = max(0, $numberLength - 6);
            $segments[] = substr($number, $start, $numberLength - $start);
            $numberLength = $start;
        }

        $result = '';
        for ($i = 0; $i < count($segments); $i++) {
            $segmentText = '';
            $segment = str_pad($segments[$i], 6, '0', STR_PAD_LEFT);
            for ($j = 0; $j < 6; $j++) {
                $digit = intval($segment[5 - $j]);
                if ($digit == 0) continue;
                // หลักหน่วย (j==0) เฉพาะกลุ่มที่ไม่ใช่ล้านแรก (i>0) และเป็น 1 ให้ใช้ 'หนึ่ง' แทน 'เอ็ด'
                if ($j == 0 && $digit == 1) {
                    if ($i > 0 && $segmentText === '') {
                        $unit = 'หนึ่ง';
                    } elseif ($segmentText !== '' || $i > 0) {
                        $unit = 'เอ็ด';
                    } else {
                        $unit = 'หนึ่ง';
                    }
                } elseif ($j == 1 && $digit == 2) {
                    $unit = 'ยี่';
                } elseif ($j == 1 && $digit == 1) {
                    $unit = '';
                } else {
                    $unit = $units[$digit];
                }
                $level = $levels[$j];
                if ($j == 1 && $digit > 0) {
                    $level = 'สิบ';
                }
                $segmentText = $unit . $level . $segmentText;
            }
            if ($segmentText !== '') {
                if ($i > 0) {
                    $segmentText .= 'ล้าน';
                }
                $result = $segmentText . $result;
            }
        }

        if ($isNegative) {
            $result = 'ลบ' . $result;
        }

        return $result;
    }
}
