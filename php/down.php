<?php
include "phpqrcode.php";
$value = unescape($_GET['url']); //二维码内容   
$errorCorrectionLevel = 'L';//容错级别   
$matrixPointSize = 6;//生成图片大小  
$getimg= 'qrcode.png';
$logo ='01.png';//准备好的logo图片 
//生成二维码图片   
$fileliu=QRcode::png($value,false,false, $errorCorrectionLevel, $matrixPointSize, 2);
Header("Content-type: application/octet-stream");
Header("Accept-Ranges: bytes");
Header("Content-Disposition: attachment; filename=erweima.png");
$file_data=fread($fileliu);
echo $file_data;
/**
* 功能和js unescape函数，解码经过escape编码过的数据
* @param $str
*/ 
function unescape($str) 
{ 
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i ++) 
    { 
        if ($str[$i] == '%' && $str[$i + 1] == 'u') 
        { 
            $val = hexdec(substr($str, $i + 2, 4));
            if ($val < 0x7f) 
                $ret .= chr($val);
            else 
                if ($val < 0x800) 
                    $ret .= chr(0xc0 | ($val >> 6)) .
                     chr(0x80 | ($val & 0x3f));
                else 
                    $ret .= chr(0xe0 | ($val >> 12)) .
                     chr(0x80 | (($val >> 6) & 0x3f)) .
                     chr(0x80 | ($val & 0x3f));
            $i += 5;
        } else 
            if ($str[$i] == '%') 
            { 
                $ret .= urldecode(substr($str, $i, 3));
                $i += 2;
            } else 
                $ret .= $str[$i];
    } 
    return $ret;
} 
/**
* 功能是js escape php 实现
* @param $string           the sting want to be escaped
* @param $in_encoding      
* @param $out_encoding     
*/ 
function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') { 
    $return = '';
    if (function_exists('mb_get_info')) { 
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) { 
            $str = mb_substr ( $string, $x, 1, $in_encoding );
            if (strlen ( $str ) > 1) { // 多字节字符
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) );
            } else { 
                $return .= '%' . strtoupper ( bin2hex ( $str ) );
            } 
        } 
    } 
    return $return;
}
?>