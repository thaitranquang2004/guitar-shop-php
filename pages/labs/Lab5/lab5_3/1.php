<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab5_3/1</title>
<style>
fieldset{width:50%; margin:100px auto;}
</style>
</head>

<body>
<fieldset>
<legend>Nhập thông tin</legend>
<form action="2.php" method="post" enctype="multipart/form-data">
<table  align="center">
    <tr><td>Nhập tên:</td><td><input type="text" name="ten"></td></tr>
    <tr><td>Giới tính:</td><td><input type="radio" name="gt" value="1" checked="checked">Nam
              <input type="radio" name="gt" value="0">Nữ</td></tr>
    <tr><td>Hình:</td><td><input type="file" name="hinh" /></td></tr>
    <tr><td colspan="2" align="center"><input type="submit" value="submit" name="submit"></td></tr>
</table>
</form>
</fieldset>
</body>
</html>
