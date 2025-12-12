<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab2_2</title>
</head>

<body>
<?php
$a=1;
$kq=0;
function f()
{
	$GLOBALS['b']=2;
	global $kq;
	$kq=$GLOBALS['a']+$GLOBALS['b'];
}
f();
echo "a = $a<br/>";
echo "b = $b<br/>";
echo "kq = $kq<br/>";
echo "Xóa bỏ globals thì kết quả trả về 0 vì trong function muốn khai báo biến thì phải có globals "
?>
</body>
</html>