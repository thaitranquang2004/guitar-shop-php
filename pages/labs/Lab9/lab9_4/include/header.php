<div class=itemmenu><a href="index.php">Home</a></div>
<div class=itemmenu><a href="#">Tin tức</a></div>
<div class=itemmenu><a href="#">Sản Phẩm</a></div>
<div class=itemmenu><a href="index.php?mod=cart">Giỏ hàng (<span id="cart_sumary"><?php echo  $cart->getNumItem();
?></span>)</a></div>
<div class=itemmenusearch>
<form action="index.php">
<input type="hidden" name="mod" value="book" />
<input type="hidden" name="ac" value="search" />
<input type="text" name="key" value="<?php echo Utils::getIndex("key", "");?>" /><input type="submit" value="Search" />
</form>
</div>