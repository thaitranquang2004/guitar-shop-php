<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab8_1- PDO - Mysql </title>
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
$stm = $pdo->query("  select * from category"  );
echo "  Số dòng:"  . $stm->rowCount();
$rows1 =$stm->fetchAll(PDO::FETCH_ASSOC);

foreach($rows1 as $row)
{
	echo "<br>".$row["cat_id"] ."-"  . $row["cat_name"]   ;
}
?><hr />
<?php

$stm = $pdo->query("select * from publisher ");
echo "  Số dòng:"  . $stm->rowCount();
$rows2 = $stm->fetchAll(PDO::FETCH_OBJ);
//print_r($rows2);
foreach($rows2 as $row)
{
	echo "<br>".$row->pub_id ."-". $row->pub_name ;	
}
?>

<hr />
<?php
$sql = "select * from book where book_name like '%a%' ";
$stm = $pdo->query($sql);
echo "  Số dòng:"  . $stm->rowCount();
$rows3 = $stm->fetchAll(PDO::FETCH_NUM);
//print_r($rows3);
foreach($rows3 as $row)
{
	echo "<br>".$row[0] ."-". $row[1] ;	
}
echo "<hr>";
$stm = $pdo->query(" select * from category"  );
echo "  Số dòng:"  . $stm->rowCount();
$row = $stm->fetch(PDO::FETCH_ASSOC);
print_r($row);
$row = $stm->fetch(PDO::FETCH_ASSOC);
print_r($row);
echo "<hr>";
$stm = $pdo->query("select * from publisher");
while($row = $stm->fetch(PDO::FETCH_ASSOC))
{
	echo "<br>".$row["pub_id"] ."-"  . $row["pub_name"]   ;	
}
?>
</body>
</html>