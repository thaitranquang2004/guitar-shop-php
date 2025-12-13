<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab8_2- PDO - Mysql - select - insert - parameter </title>
</head>

<body>
<?php
// try{
// $pdh = new PDO("mysql:host=localhost; dbname=bookstore"  , "root"  , ""  );
// $pdh->query("  set names 'utf8'"  );
// }
// catch(Exception $e){
// 		echo $e->getMessage(); exit;
// }
include '../../../config/database.php';

$search= "a";
$sql ="select * from publisher where pub_name like :ten ";
$stm = $pdo->prepare($sql);
$stm->bindValue(":ten","%$search%");
$stm->execute();
$rows = $stm->fetchAll(PDO::FETCH_ASSOC);
echo "<pre>";
print_r($rows);
echo "</pre>";
echo "<hr>";
$ma="LS1";
$ten = "Lịch sử";
$sql="insert into category(cat_id, cat_name) values(:maloai, :tenloai)";
$arr = array(":maloai"=>$ma, ":tenloai"=>$ten);
$stm= $pdo->prepare($sql);
$stm->execute($arr);
$n = $stm->rowCount();
echo "Đã thêm $n loại sách";
?>

