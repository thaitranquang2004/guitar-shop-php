<?php
function showArray($arr)
{
	foreach($arr as $k=>$v)
	{
		echo "<br> $k - $v ";	
	}	
}
$a = array(6, 2, 7, 8, 5); 
$b = array("a"=>4, "b"=>2, "c"=>3, "d"=>8);
$n = array_rand($a);
echo "Phần tử ngẫu nhiên: key=$n , giá trị=".$a[$n];
$c = array_rand($a, 3);
echo "<br> Danh sách 3 phần tử ngẫu nhiên được lấy ra:";
foreach($c as $k)
{
	echo "(key=$k -	value={$a[$k]})";
}

$m=3;
$c = array_rand($b, $m);
echo "<br> Danh sách $m phần tử ngẫu nhiên được lấy ra từ b:";
foreach($c as $k)
{
	echo "(key=$k -	value={$b[$k]})";
}
?><hr />
<?php
$a1= $a; sort($a1);
echo "Mảng a sau khi sắp xếp:";
showArray($a1);
$b1 = $b;
rsort($b1);
echo "<br>Mảng b sau khi sắp xếp loại bỏ key:";
showArray($b1);

$b2 = $b;
krsort($b2);
echo "<br>Mảng b sau khi sắp xếp giữ lại key:";
showArray($b2);

echo "<hr> Tính tổng ";
$sum_a = array_sum($a);
$sum_b = array_sum($b);
echo "<br> Tổng các giá trị trong mảng a = $sum_a , mảng b= $sum_b ";


?>


