<?php
$ac= getIndex("ac");

if ($ac=="add")
{
	$quantity = getIndex("quantity", 1);
	$id = getIndex("id");
	$cart->add($id, $quantity);
}
//Biến $cart được tạo từ trang chủ index.php
$cart->show();