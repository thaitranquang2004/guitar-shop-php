<?php
if (!defined("ROOT"))
{
	echo "Err!"; exit;	
}
$key = getIndex("key", "");
$sql ="select * from book where book_name like :key ";
$arr = array(":key"=> "%".$key."%");
$page = Utils::getIndex("page", 1);
$list = $book->search($page);

echo "Page $page/ ".$book->getPageCount() ;
foreach($list as $r)
{
	?>
    <div class=book>
    	<?php echo $r["book_name"];?>
    </div>
    <?php	
}

?>
<div>
<?php
for($i=1; $i<=$book->getPageCount(); $i++)
{
	echo "<a href='index.php?mod=book&ac=search&key=$key&page=$i'>$i</a>&nbsp;";	
}
?>
</div>