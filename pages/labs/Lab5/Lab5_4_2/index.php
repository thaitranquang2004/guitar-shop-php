<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sản phẩm</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        .container { width: 500px; margin: 0 auto; border: 1px solid #ccc; padding: 20px; background: #f9f9f9; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 120px; font-weight: bold; }
        .result { margin-top: 20px; padding: 15px; background: #e0f7fa; border: 1px dashed #006064; }
    </style>
</head>
<body>

<div class="container">
    <h2>TÌM KIẾM SẢN PHẨM</h2>
    
    <form action="" method="get">
        
        <div class="form-group">
            <label>Tên sản phẩm:</label>
            <input type="text" name="txtTenSP" required placeholder="Nhập tên sản phẩm...">
        </div>

        <div class="form-group">
            <label>Cách tìm:</label>
            <input type="radio" name="radCachTim" value="gandung" checked> Gần đúng
            <input type="radio" name="radCachTim" value="chinhxac"> Chính xác
        </div>

        <div class="form-group">
            <label>Loại sản phẩm:</label>
            <select name="slLoaiSP">
                <option value="tatca">Tất cả</option>
                <option value="loai1">Loại 1</option>
                <option value="loai2">Loại 2</option>
                <option value="loai3">Loại 3</option>
            </select>
        </div>

        <div class="form-group">
            <label></label>
            <input type="submit" name="btnTim" value="Tìm kiếm">
        </div>
    </form>

    <?php
   
    if (isset($_GET['btnTim'])) {
        
        
        $tenSP = $_GET['txtTenSP'];
        $cachTim = $_GET['radCachTim'];
        $loaiSP = $_GET['slLoaiSP'];

        echo "<div class='result'>";
        echo "<h3>KẾT QUẢ TÌM KIẾM:</h3>";
        
 
        echo "<b>Tên sản phẩm:</b> " . $tenSP . "<br/>";

        $textCachTim = ($cachTim == "chinhxac") ? "Chính xác" : "Gần đúng";
        echo "<b>Cách tìm:</b> " . $textCachTim . "<br/>";

      
        if ($loaiSP != "tatca") {
         
            $tenLoai = "";
            switch ($loaiSP) {
                case 'loai1': $tenLoai = "Loại 1"; break;
                case 'loai2': $tenLoai = "Loại 2"; break;
                case 'loai3': $tenLoai = "Loại 3"; break;
                default: $tenLoai = $loaiSP;
            }
            echo "<b>Loại sản phẩm:</b> " . $tenLoai . "<br/>";
        }

        echo "</div>";
    }
    ?>
</div>

</body>
</html>