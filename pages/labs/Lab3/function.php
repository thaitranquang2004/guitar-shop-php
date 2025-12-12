<?php
/*
Hàm tạo Bảng cửu chương
- Input: $n (số), $colorHead, $color1, $color2
- Output: Trả về chuỗi HTML
*/
function BCC($n, $colorHead="green", $color1="info", $color2="yellow") {
    
    if ($color1 == $color2) {
        $color2 = ""; 
    }
    
    $html = "";
    // Nối chuỗi HTML
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
    
    return $html;
}

/*
Hàm tạo Bàn cờ vua
- Input: $size (kích thước)
- Output: Trả về chuỗi HTML
*/
function BanCo($size = 8)
{
    $html = "";
    $html .= '<div id="banco">';
    
    for($i = 1; $i <= $size; $i++)
    {
        for($j = 1; $j <= $size; $j++)
        {
            // Tính toán màu ô
            $classCss = (($i + $j) % 2) == 0 ? "cellWhite" : "cellBlack";
            $html .= "<div class='$classCss'>$i - $j</div>";
        }
        $html .= "<div class='clear'></div>"; 
    }
    
    $html .= '</div>';
    
    return $html;
}
?>