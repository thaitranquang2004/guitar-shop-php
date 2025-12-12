<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết sản phẩm</title>
</head>
<body>
    <h3>Kết quả nhận được:</h3>
    <?php
    
    if (isset($_GET['id'])) {
        $received_id = $_GET['id'];
        
        // In giá trị id ra màn hình
        echo "Giá trị ID nhận được là: <b>" . $received_id . "</b>";
    } else {
        echo "Không tìm thấy ID nào được gửi đến.";
    }
    ?>
    
    <br/><br/>
    <a href="1.php">Quay lại danh sách</a>
</body>
</html>