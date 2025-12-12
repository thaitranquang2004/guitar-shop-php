<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 2_5</title>
</head>

<body>
<?php	
	require("lab2_5a.php");
	require("lab2_5b.php");
	
	

	if(isset($x))
		echo "Giá trị của x là: $x";
	else
		echo "Biến x không tồn tại";
	echo"<br>";
	
	
	echo"include_one giống với include nhưng trong include_once chỉ nhúng 1 file duy nhất khi có file khác cùng tên thì php sẽ bỏ qua chỉ  chạy 1 cái <br>";
	echo "kết quả ra 20 giống vs vd4_6";
?>	
</body>
</html>