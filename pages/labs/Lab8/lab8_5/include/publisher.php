<?php
$pubs = $db->select("select * from publisher ");
foreach($pubs as $r)
{
	?>
    <div><a href="index.php?mod=book&ac=list&pub_id=<?php echo $r["pub_id"];?>">
    		<?php echo $r["pub_name"];?></a>
    </div>
    <?php	
}

?>