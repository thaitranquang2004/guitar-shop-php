<pre><?php
$a = array(1, -3, 5); //mảng có 3 phần tử
$b = array("a"=>2, "b"=>4, "c"=>-6);//mảng có 3 phần tử.Các index của mảng là chuỗi
?>
Nội dung giá trị mảng a :
<?php
foreach($a as $value)
{
	echo $value ." ";	
}
?>
<br> Nôi dung mảng a (key-value) 
<?php
foreach($a as $key=>$value)
{
	echo "($key - $value )";	
}
?>
<br /> Nội dung mảng b: (key - value):
<?php
foreach($b as $k=>$v)
{
	echo "($k - $v) ";	
}
?>
<br />Hiển thị nội dung mảng b ra dạng bảng:
<table border=1>
	<tr><td>STT</td><td>Key</td><td>Value</td></tr>
    <?php
	$i=0;
	foreach($b as $k=>$v)
	{	$i++;
		echo "<tr><td>$i</td>";
		echo "<td>$k</td>";
		echo "<td>$v</td></tr>";
	}
	?>
</table>
câu a
<?php
$count_positive = 0; // Khởi tạo biến đếm
foreach($a as $value)
{
    if ($value > 0) { // Kiểm tra nếu giá trị là số dương
        $count_positive++; // Tăng biến đếm
    }
}
echo "Mảng a = (" . implode(", ", $a) . ")";
echo "<br>Số phần tử dương trong mảng \$a là: **" . $count_positive . "**";
?>
<br>
câu b
**Tạo mảng $c chứa các phần tử dương từ mảng $b:**

<?php

$c = array();


foreach($b as $key => $value) {
    if ($value > 0) {
        
        $c[$key] = $value;
    }
}


echo "Mảng \$c sau khi tạo: ";
echo "<pre>";
print_r($c);
echo "</pre>";


echo "Hiển thị chi tiết: ";
foreach($c as $k => $v) {
    echo "($k => $v) ";
}
?>