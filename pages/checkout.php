<?php 
include '../config/database.php'; 
include '../includes/header.php'; 

if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    
    if ($name && $email && $phone && $address) {
        try {
            $pdo->beginTransaction();
            
            // Insert Order
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$userId, $name, $email, $phone, $address, $total]);
            $orderId = $pdo->lastInsertId();
            
            
            // Insert Order Items
            $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($_SESSION['cart'] as $productId => $item) {
                $stmtItem->execute([$orderId, $productId, $item['quantity'], $item['price']]);
            }
            
            $pdo->commit();
            
            // Clear cart
            unset($_SESSION['cart']);
            
            // Redirect to thank you page
            header('Location: thank-you.php?order_id=' . $orderId);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Có lỗi xảy ra. Vui lòng thử lại.";
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin.";
    }
}
?>

<div class="container mt-5">
    <div class="section-title text-center mb-5">
        <h2 class="fw-bold text-uppercase">Thanh Toán</h2>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Thông Tin Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Họ và Tên</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nguyễn Văn A">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số Điện Thoại</label>
                                <input type="tel" name="phone" class="form-control" required placeholder="0901234567">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Địa Chỉ Giao Hàng</label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Số nhà, đường, phường/xã..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Xác Nhận Đặt Hàng</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-5">
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-header bg-transparent py-3">
                    <h5 class="mb-0 fw-bold">Đơn Hàng Của Bạn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush bg-transparent">
                        <?php foreach ($_SESSION['cart'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" width="50" class="rounded me-3">
                                    <div>
                                        <h6 class="my-0"><?php echo htmlspecialchars($item['name']); ?></h6>
                                        <small class="text-muted">SL: <?php echo $item['quantity']; ?></small>
                                    </div>
                                </div>
                                <span class="text-muted"><?php echo number_format($item['price'] * $item['quantity']); ?> VNĐ</span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between bg-transparent px-0 fw-bold fs-5 mt-3 border-top">
                            <span>Tổng Cộng</span>
                            <span class="text-danger"><?php echo number_format($total); ?> VNĐ</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
