<?php 
include '../config/database.php'; 
include '../includes/header.php'; 
$orderId = $_GET['order_id'] ?? 0;
?>

<div class="container mt-5 text-center" style="min-height: 50vh; display: flex; flex-direction: column; justify-content: center;">
    <div class="mb-4">
        <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
    </div>
    <h1 class="fw-bold mb-3">Đặt Hàng Thành Công!</h1>
    <p class="lead text-muted mb-4">Cảm ơn bạn đã mua sắm tại Guitar Shop. Mã đơn hàng của bạn là <strong>#<?php echo htmlspecialchars($orderId); ?></strong>.</p>
    <p class="mb-5">Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.</p>
    
    <div>
        <a href="./index.php" class="btn btn-primary rounded-pill px-4 me-2">Về Trang Chủ</a>
        <a href="shop.php" class="btn btn-outline-secondary rounded-pill px-4">Tiếp Tục Mua Sắm</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
