<?php

    function Check_Month($r_month){
        return str_replace(
                            array("01","02","03","04","05","06","07","08","09","10","11","12"),
                            array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"),
                            $r_month
                        );
    }

    function Check_Sub_Month($r_month){
        return str_replace(
                            array("01","02","03","04","05","06","07","08","09","10","11","12"),
                            array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค."),
                            $r_month
                        );
    }

    function ConvertToThai($num_thai){
        return str_replace(
                            array("0","1","2","3","4","5","6","7","8","9"),
                            array("๐","๑","๒", "๓","๔","๕","๖","๗","๘","๙"),
                            $num_thai
                        );
    }

    function generateRandomString($length = 8) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
