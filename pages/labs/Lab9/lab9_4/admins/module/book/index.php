<?php
$group = Utils::getIndex("group", "book");

//cho xu ly table category
if ($group=="cat")
{	
	$category = new Category();
	$ac = Utils::getIndex("ac", "catShow");
	if ($ac !="delete")
	include ROOT."/admins/module/book/catedit.php"; //Insert form edit or add new
	
	if ($ac=="catShow")
		include ROOT."/admins/module/book/catshow.php";
	
	if ($ac=="delete")
		{
			//xu ly xoa	
			$n = $category->delete(Utils::getIndex("id"));
			?>
            <script language="javascript">
			alert("Đã xóa <?php echo $n;?> category!");
			window.location="index.php?mod=book&group=cat";
			</script>
            <?php
		}
	if ($ac=="saveEdit")
		{
			//xu ly edit -> and redirect to index.php -> mod-> action	
			$n = $category->saveEdit();
			?>
            <script language="javascript">
			alert("Đã sửa <?php echo $n;?> category!");
			window.location="index.php?mod=book&group=cat";
			</script>
            <?php
		}
	if ($ac=="saveAdd")
		{
			//xu ly edit -> and redirect to index.php -> mod-> action	
			$n = $category->saveAddNew();
			?>
            <script language="javascript">
			alert("Da them <?php echo $n;?> category!");
			window.location="index.php?mod=book&group=cat";
			</script>
            <?php
		}
}
if ($group=="book")
{
$ac = Utils::getIndex("ac", "bookShow");
if ($ac=="bookShow")
include ROOT."/admins/module/book/bookshow.php";
//...
//...

}

if ($group=="pub")
{
   echo "Các thao tác với nhà xuất bản tại đây nha!";	
}