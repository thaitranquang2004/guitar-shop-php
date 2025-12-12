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
	
	
	echo"Xuất ra kết quả x=20 vì trong lab2_5a có dữ liệu là 10 và trong lab2_5b cũng là 10 nên kq là 20";
?>
</body>
</html>