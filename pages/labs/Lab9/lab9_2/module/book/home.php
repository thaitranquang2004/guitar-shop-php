<?php
$list = $book->getRand(10);
foreach($list as $r)
{
	?>
    <div class=book>
    	<?php echo $r["book_name"];?>
    </div>
    <?php	
}
?>