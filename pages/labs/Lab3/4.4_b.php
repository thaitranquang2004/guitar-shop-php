<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 3_5</title>
<style>
    #banco{border:solid; padding:15px; background:#E8E8E8; display: inline-block;} /* Thêm inline-block để bao trọn nội dung float */
    #banco .cellBlack{width:50px; height:50px; background:black; float:left; color: white; line-height: 50px; text-align: center;}
    #banco .cellWhite{width:50px; height:50px; background:white; float:left; line-height: 50px; text-align: center;}
    .clear{clear:both}
</style>
</head>

<body>
<?php
/*
bảng cửu chương $n, màu nền $color
- Input: $n là một số nguyên dương (1->10)
         $color: Tên màu nền. Mặc định là green
- Output: Trả về chuỗi HTML Bảng cửu chương
*/
function BCC($n, $colorHead="green", $color1="info", $color2="yellow") {
    
    if ($color1 == $color2) {
        $color2 = ""; 
    }
    
    // Khởi tạo biến chuỗi chứa HTML
    $html = "";
    
    $html .= '<table bgcolor="' . $colorHead . '" cellpadding="5" cellspacing="0">';
    $html .= '<tr><td colspan="5" style="text-align:center; font-weight:bold; font-size:18px;">Bảng cửu chương ' . $n . '</td></tr>';
    
    for ($i = 1; $i <= 10; $i++) {
        $rowColor = ($i % 2 == 0) ? $color2 : $color1;
        
        $html .= '<tr bgcolor="' . $rowColor . '">';
        $html .= '<td>' . $n . '</td>';
        $html .= '<td>x</td>';
        $html .= '<td>' . $i . '</td>';
        $html .= '<td>=</td>';
        $html .= '<td>' . ($n * $i) . '</td>';
        $html .= '</tr>';
    }
    
    $html .= '</table>';
    
    // Trả về chuỗi HTML thay vì echo
    return $html;
}

/*
Hàm trả về chuỗi HTML bàn cờ vua
- Input: $size: kích thước bàn cờ
- Output: Chuỗi HTML bàn cờ
*/
function BanCo($size = 8)
{
    $html = "";
    $html .= '<div id="banco">';
    
    for($i = 1; $i <= $size; $i++)
    {
        for($j = 1; $j <= $size; $j++)
        {
            $classCss = (($i + $j) % 2) == 0 ? "cellWhite" : "cellBlack";
            $html .= "<div class='$classCss'>$i - $j</div>";
        }
        $html .= "<div class='clear'></div>";
    }
    
    $html .= '</div>';
    
    return $html;
}

// === CÁCH SỬ DỤNG ===


echo BCC(6, "red"); 

echo "<br/><br/>"; // Xuống dòng cho dễ nhìn

echo BanCo();

?>
</body>
</html>