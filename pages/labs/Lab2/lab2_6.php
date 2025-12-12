<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>lab 2_6</title>
</head>

<body>
<?php
$thu=0; //biến $thu chứa giá trị từ 0 đến 6
switch($thu)
{
	case 0:
		echo "Chủ nhật";
		break;
	case 1:
		echo "Thứ hai";
		break;
	case 2:
		echo "Thứ ba";
		break;
	case 3:
		echo "Thứ tư";
		break;
	case 4: 
		echo "Thứ năm";
		break;
	case 5:
		echo "Thứ sau";
		break;
	case 6:
		echo "Thứ bảy";
		break;
	default:
		echo "Không phải thứ";
		
}
?>
</body>
</html>