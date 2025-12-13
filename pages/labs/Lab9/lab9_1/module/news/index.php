<?php

$ac = getIndex("ac", "list");
if ($ac=="list")
	{
		echo "Hiển thị danh sách tin tức tại đây!";	
	}
if ($ac=="detail")
{
	$id = getIndex("id");
	echo "Hiển thị chi tiết tin có id ='$id' ";	
}
?>