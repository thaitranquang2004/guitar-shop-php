<?php
if (!defined("ROOT"))
{
	echo "Err!"; exit;	
}
$cat_id = getIndex("cat_id", "all");
$pub_id = getIndex("pub_id", "all");
$sql ="select * from book where 1 ";
$arr = array();
if ($cat_id !="all")
{	$sql .=" and cat_id =:cat_id ";
	$arr[":cat_id"] = $cat_id;
}

if ($pub_id !="all")
{	$sql .=" and pub_id =:pub_id ";
	$arr[":pub_id"] = $pub_id;
}

$list = $book->exeQuery($sql, $arr);
echo "Có ".$book->getRowCount() ." kết quả";
foreach($list as $r)
{
	?>
    <div class=book>
    	<?php echo $r["book_name"];?>
    </div>
    <?php	
}

?>