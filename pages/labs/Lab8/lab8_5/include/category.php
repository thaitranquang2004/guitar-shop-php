<?php
$cats = $db->select("select * from category ");
foreach($cats as $r)
{
	?>
    <div><a href="index.php?mod=book&ac=list&cat_id=<?php echo $r["cat_id"];?>">
    		<?php echo $r["cat_name"];?></a>
    </div>
    <?php	
}

?>