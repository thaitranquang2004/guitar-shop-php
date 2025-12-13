<?php
//Load cac file can thiet cho ung dung
include "config/config.php";
include ROOT."/include/function.php";
spl_autoload_register("loadClass");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Database!</title>
<style>
.book{width:250px; height:300px; margin:3px; background:#FCC; float:left}
div.book img{height:200px; margin:0 10px}
</style>
</head>

<body>
<?php
	$obj = new Db();//tu dong load file classes/Db.class.php
	$rows = $obj->select("select * from category ");
	foreach($rows as $row)
	{
		echo "<br>".$row["cat_id"] ."-".$row["cat_name"];	
	}
	echo "<hr>";
	
	$book = new Book();
	$rows = $book->getRand(5);
	foreach($rows as $row)
	{
		?>
        <div class='book'><?php echo $row["book_id"] ."-".$row["book_name"];?><hr />
        <img src="image/book/<?php echo $row["img"];?>" />
        
        </div>
        <?php
			
	}

?>
</body>
</html>