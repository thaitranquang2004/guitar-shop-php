<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 2_4</title>
</head>

<body>
<?php
$a=1;
$b=2;
$x="1";
$s1="Xin";
$s2= "chào";
$s=$s1." ".$s2;//ghép chuỗi
echo "a + b = ".($a+$b)."<br/>";
echo "a + x = ".($a+$x)."<br/>";
echo "x + a = ".($x+$a)."<br/>";
echo "Ghép chuỗi: $s"."<br/>";
echo "Phân biệt == và === :";
if($a===$x)
	echo "a và x giống nhau";
else 
	echo "a và x khác nhau";
echo "<br>";
echo"kết quả ra a và x khác nhau vì trong php == trong sẽ là so sáng bằng sẽ tự chuyển kiểu dữ liệu <br>
còn trong === thì php sẽ ko chuyển kiểu dữ liệu phải chung kiểu dữ liệu mới có thể xuất ra (a và x giống nhau)"
?>
</body>
</html>