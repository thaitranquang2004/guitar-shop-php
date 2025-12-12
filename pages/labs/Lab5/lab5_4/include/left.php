<div class="boxleft">
<div class="headerBoxLeft">
Loại sách
</div>
<?php
$loai = array();
$r = array("maloai"=>"th", "tenloai"=>"Tin Học");
$loai[] = $r;
$r = array("maloai"=>"to", "tenloai"=>"Toán Học");
$loai[] = $r;
$r = array("maloai"=>"td", "tenloai"=>"Từ Điển");
$loai[] = $r;

foreach($loai as $row)
{
		?>
        <div >
        	<a href="index.php?mod=product&ac=catalog&idcat=<?php echo $row["maloai"];?>">
			<?php echo $row["tenloai"];?></a>
        </div>
        <?php
}

?>
</div>

<?php


?>
<div class="boxleft">
<div class="headerBoxLeft">
Nhà xuất bản
</div>
<?php
$nhaxb = array();
$r = array("manxb"=>"gd", "tennxb"=>"Giáo Dục");
$nhaxb[] = $r;
$r = array("manxb"=>"hcm", "tennxb"=>"TP. Hồ Chí Minh");
$nhaxb[] = $r;
$r = array("manxb"=>"tn", "tennxb"=>"Thanh Niên");
$nhaxb[] = $r;
$r = array("manxb"=>"dhqg", "tennxb"=>"Đại Học Quốc Gia");
$nhaxb[] = $r;
$r = array("manxb"=>"vhxh", "tennxb"=>"Văn Hóa xã hội");
$nhaxb[] = $r;

foreach($nhaxb as $row)
{
		?>
        <div >
        	<a href="index.php?mod=product&ac=press&idpress=<?php echo $row["manxb"];?>"><?php echo $row["tennxb"];?></a>
        </div>
        <?php
}
?>
</div>