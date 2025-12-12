<?php
if (!defined("ROOT"))
{
	echo "You don't have permission to access this page!"; exit;	
}
$path =ROOT."/module/info/trangchu.php";//mac dinh
	$mod = isset($_GET["mod"])?$_GET["mod"]:"";
	$ac = isset($_GET["ac"])?$_GET["ac"]:"";
	if($mod=="info")
	{
		include ROOT."/module/info/index.php";
	}
	if ($mod=="product")
	{
		include ROOT."/module/product/index.php";
		
	}
	if ($mod=="news")
	{
		
	}
	if ($mod=="cart")
	{
		
	}
	if ($mod=="search")
		{
				$path =ROOT."/module/product/search.php";
		}
	include $path;

?>