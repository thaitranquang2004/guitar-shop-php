<?php
if (!defined("ROOT"))
{
	echo "You don't have permission to access this page!"; exit;	
}

$idpress = getIndex("idpress");
?>
<div class="productList">
	Các sản phẩm của nhà xuất bản có mã: <?php echo $idpress;?>
</div>
