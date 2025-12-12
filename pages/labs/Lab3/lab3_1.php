<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 3_1</title>
</head>

<body>
<?php
	//Tính biểu thức S=1 + 2 + 3 + ... + 100
	// Sử dụng FOR
	$s1=0;
	for($i=1;$i<=100;$i++)
		$s1+=$i;
	//Sử dụng while
	$i=1;
	$s2=0;
	while($i<=100)
	{
		$s2+=$i;
		$i++;
	}
	//Sử dụng DO...WHILE
	$i=1;
	$s3=0;
	do
	{
		$s3+=$i;
		$i++;
	}while($i<=100);
	echo "Kết quả S = 1 + 2 + 3 + ... + 100 <br/>";
	echo "Tính bằng FOR, S1 = $s1 <br/>";
	echo "Tính bằng WHILE, S2 = $s2 <br/>";
	echo "Tính bằng DO...WHILE, S3 = $s3 <br/>";
 ?>
</body>
</html> 