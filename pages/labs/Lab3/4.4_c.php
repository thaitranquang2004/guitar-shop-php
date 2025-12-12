<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab 3_5 - Gọi hàm từ file ngoài</title>
<style>
    
    #banco{border:solid; padding:15px; background:#E8E8E8; display: inline-block;}
    #banco .cellBlack{width:50px; height:50px; background:black; float:left; color: white; line-height: 50px; text-align: center;}
    #banco .cellWhite{width:50px; height:50px; background:white; float:left; line-height: 50px; text-align: center;}
    .clear{clear:both}
</style>
</head>

<body>
    
    <?php
  
    include 'function.php'; 
    
    
    
    echo "<h3>Kết quả gọi hàm BCC:</h3>";
    echo BCC(6, "red"); 
    
    echo "<br/><hr/><br/>"; 

    echo "<h3>Kết quả gọi hàm BanCo:</h3>";
    echo BanCo(8);
    ?>

</body>
</html>