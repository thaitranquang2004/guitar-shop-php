<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quản lý loại sách</title>
<style>
#container{width:600px; margin:0 auto;}
</style>
</head>

<body>
<div id="container">

<form action="lab8_3.php" method="post">
<table>
<tr><td>Mã loại:</td><td><input type="text" name="cat_id" /></td></tr>
<tr><td>Tên loại:</td><td><input type="text" name="cat_name" /></td></tr>
<tr><td colspan="2"> <input type="submit" name="sm" value="Insert" /></td></tr>
</table>
</form>
<?php
// try{
// $pdh = new PDO("mysql:host=localhost; dbname=bookstore"  , "root"  , ""  );
// $pdh->query("  set names 'utf8'"  );
// }
// catch(Exception $e){
// 		echo $e->getMessage(); exit;
// }
include '../../../config/database.php';

if (isset($_POST["sm"]))
{
	$sql="insert into category(cat_id, cat_name) values(:cat_id, :cat_name) ";
	$arr = array(":cat_id"=>$_POST["cat_id"], ":cat_name"=>$_POST["cat_name"]);
	$stm= $pdo->prepare($sql);
	$stm->execute($arr);
	$n = $stm->rowCount();
	if ($n>0) echo "Đã thêm $n loại ";
	else echo "Lỗi thêm ";
}

$stm = $pdo->prepare("select * from category");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
?>
<table><tr><td>mã loại</td><td>tên loại</td>
		<td>Thao tác</td></tr>
<?php
foreach($rows as $row)
{
	?>
    <tr><td><?php echo $row["cat_id"];?></td>
    	<td><?php echo $row["cat_name"];?></td>
        <td><a href='lab8_31.php?cat_id=<?php echo $row["cat_id"];?>'>Xóa</a></td>
        </tr>
    <?php
}
?>
</table>
</div>
</body>
</html>