<?php
function postIndex($index, $value="")
{
	if (!isset($_POST[$index]))	return $value;
	return trim($_POST[$index]);
}

function getExt($file)
{
	$arr = explode(".",$file);
	if (Count($arr)<2) return "";
	return $arr[Count($arr)-1];
}

function getPasswordRandom($n = 8) //Độ dài password
{
	$s="abcdefghijkmlnopqrstuvxyz0123456789";
	return substr(str_shuffle ($s), 0, $n);
}

$sm = postIndex("submit");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab6_2</title>
<style>
fieldset{width:50%; margin:100px auto;}
.info{width:600px; color:#006; background:#6FC; margin:0 auto}
</style>
</head>

<body>
<fieldset>
<legend style="margin:0 auto">Thông tin </legend>
<form action="lab06_2.php" method="post" enctype="multipart/form-data">
<table  align="center">
    <tr><td>Chọn 1 file</td><td><input type="file" name="file" />
    <tr><td colspan="2" align="center"><input type="submit" value="submit" name="submit"></td></tr>
</table>
</form>
</fieldset>
<?php

if ($sm !="")
{
	?>
    <div class="info">
    	<?php 
			if (isset($_FILES["file"]) && ($_FILES["file"]["error"]==0))
				$ext = strtolower(getExt($_FILES["file"]["name"]));
			else $ext="";
			
			$pass = getPasswordRandom(9);
			echo "Phần mở rộng file là: $ext <br> Password ngẫu nhiên: $pass ";
		?>
    </div>
    <?php

}
?>
</body>
</html>
