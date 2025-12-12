<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab5_1</title>
</head>

<body>
<?php
echo "REQUEST:";
print_r($_REQUEST);
echo "<hr>POST<br>";
print_r($_POST);

?><hr>
<a href="lab5_2.php?x=1&y=2&z=3">Link 1</a><br>
<a href="lab5_2.php?x[]=1&x[]=2&y=3">Link 2</a><br>
<a href="lab5_2.php?mod=product&ac=detail&id=1">Link 3</a><br>
<a href="lab5_2.php?mod=product&ac=list&name=a&page=2">Link 4</a><br>
<hr>
<fieldset>
<legend>Form 1</legend>
<form action="lab5_2.php" method="post">
Nhập x:<input type="text" name="x" value="1"><br>
Nhập y:<input type="text" name="y" value="2"><br>
Nhập z:<input type="text" name="z" value="3"><br>
<input type="submit" >
</form>
</fieldset>

<fieldset>
<legend>Form 2</legend>
<form action="lab5_2.php" method="post">
Nhập x1:<input type="text" name="x[]" value="1"><br>
Nhập x2:<input type="text" name="x[]" value="2"><br>
Nhập y:<input type="text" name="y" value="3"><br>
<input type="submit" >
</form>
</fieldset>

<fieldset>
<legend>Form 3</legend>
<form action="lab5_2.php" method="post">
Nhập tên:<input type="text" name="ten" ><br>
giới tính:<input type="radio" name="gt" value="1">Nam
		  <input type="radio" name="gt" value="0">Nữ<br>
Sở Thích:<input type="checkbox" name="st[]" value="tt">Thể Thao
		<input type="checkbox" name="st[]" value="dl">Du Lịch
		<input type="checkbox" name="st[]" value="game">Game<br>
<input type="submit" >
</form>
</fieldset>
</body>
</html>