<?php
if (!defined("ROOT"))
{
	echo "You don't have permission to access this page!"; exit;	
}

$tensach = getIndex("proname");

?>
<div class="productList">
	Danh sách các cuốn sách có tên sách chứa chuỗi: <?php echo $tensach;?>
</div>
