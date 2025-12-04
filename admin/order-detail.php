<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Update status
if (isset($_POST['status'])) {
    $status = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);
    $success = "Cập nhật trạng thái thành công!";
    
    // Refresh order data
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    $order = $stmt->fetch();
} else {
    // Get Order
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$id]);
    $order = $stmt->fetch();
}

if (!$order) { echo "<div class='alert alert-danger'>Đơn hàng không tồn tại</div>"; include 'includes/footer.php'; exit; }

// Get Items
$stmtItems = $pdo->prepare("SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
$stmtItems->execute([$id]);
$items = $stmtItems->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Chi Tiết Đơn Hàng #<?php echo $id; ?></h4>
    <a href="orders.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Quay Lại</a>
</div>

<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card table-card mb-4">
            <div class="card-header">Danh Sách Sản Phẩm</div>
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Sản Phẩm</th>
                            <th>Giá</th>
                            <th>Số Lượng</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="<?php echo htmlspecialchars($item['image']); ?>" width="50" class="rounded me-3">
                                        <span class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                    </div>
                                </td>
                                <td><?php echo number_format($item['price']); ?> ₫</td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'] * $item['quantity']); ?> ₫</td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-light">
                            <td colspan="3" class="text-end fw-bold py-3">Tổng Cộng:</td>
                            <td class="fw-bold text-danger fs-5 py-3"><?php echo number_format($order['total_amount']); ?> ₫</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card table-card mb-4">
            <div class="card-header">Thông Tin Khách Hàng</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Họ và Tên</label>
                    <div class="fw-bold"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Email</label>
                    <div><?php echo htmlspecialchars($order['customer_email']); ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Số Điện Thoại</label>
                    <div><?php echo htmlspecialchars($order['customer_phone']); ?></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Địa Chỉ</label>
                    <div><?php echo nl2br(htmlspecialchars($order['customer_address'])); ?></div>
                </div>
                <div>
                    <label class="text-muted small">Ngày Đặt</label>
                    <div><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                </div>
            </div>
        </div>
        
        <div class="card table-card">
            <div class="card-header">Cập Nhật Trạng Thái</div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Chờ Xử Lý (Pending)</option>
                            <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Đang Xử Lý (Processing)</option>
                            <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>Hoàn Thành (Completed)</option>
                            <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Đã Hủy (Cancelled)</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Cập Nhật Trạng Thái</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
