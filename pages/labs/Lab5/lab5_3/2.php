<?php
function postIndex($index, $value="")
{
	if (!isset($_POST[$index]))	return $value;
	return $_POST[$index];
}

$sm 	= postIndex("submit");
$ten 	= postIndex("ten");
$gt 	= postIndex("gt");
$arrImg = array("image/png", "image/jpeg", "image/bmp");

if ($sm=="") {
				header("location:1.php"); exit;//quay ve 1.php
			}

$err = "";
if ($ten=="") $err .="Phải nhập tên <br>";
if ($gt=="") $err .="Phải chọn giới tính <br>";

$errFile = $_FILES["hinh"]["error"];
if ($errFile>0)
	$err .="Lỗi file hình <br>";
else
{
	$type = $_FILES["hinh"]["type"];
	if (!in_array($type, $arrImg))
		$err .="Không phải file hình <br>";
	else
	{	$temp = $_FILES["hinh"]["tmp_name"];
		$name = $_FILES["hinh"]["name"];
		if (!move_uploaded_file($temp, "image/".$name))
			$err .="Không thể lưu file<br>";
		
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab5_3/2</title>
</head>
<body>
<?php
if ($err !="")
  echo $err;
else
{
	if($gt =="1") echo "Chào Anh: $ten ";
	else echo "Chào Chị $ten ";
	?><hr>
    <img src="image/<?php echo $name;?>">
    <?php	
}
?>
<p>
<a href="1.php">Tiếp tục</a>
</p>
</body>
</html>