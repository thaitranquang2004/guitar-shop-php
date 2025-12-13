<?php
$mod = getIndex("mod","book");
			
if ($mod=="book")
	include "module/book/index.php";
if ($mod=="news")
	include "module/news/index.php";
if ($mod=="cart")
	include "module/cart/index.php";
	
?>