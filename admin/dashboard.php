<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

// Stats
$productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$orderCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$userCount = $pdo->query("SELECT COUNT(*) FROM userss WHERE role='customer'")->fetchColumn();
$revenue = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE status='completed'")->fetchColumn();

// Recent Orders
$recentOrders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-gradient-primary text-white">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Sản Phẩm</h5>
                    <h2 class="mb-0"><?php echo number_format($productCount); ?></h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25">
                    <i class="fas fa-box"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-gradient-success text-white">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Đơn Hàng</h5>
                    <h2 class="mb-0"><?php echo number_format($orderCount); ?></h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-gradient-warning text-white">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Doanh Thu</h5>
                    <h2 class="mb-0"><?php echo number_format($revenue ?? 0); ?> ₫</h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-gradient-danger text-white">
            <div class="card-body">
                <div>
                    <h5 class="card-title">Khách Hàng</h5>
                    <h2 class="mb-0"><?php echo number_format($userCount); ?></h2>
                </div>
                <div class="icon-box bg-white bg-opacity-25">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Đơn Hàng Gần Đây</span>
        <a href="orders.php" class="btn btn-sm btn-primary rounded-pill">Xem Tất Cả</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Khách Hàng</th>
                        <th>Tổng Tiền</th>
                        <th>Trạng Thái</th>
                        <th>Ngày Đặt</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td class="ps-4">#<?php echo $order['id']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($order['customer_phone']); ?></small>
                            </td>
                            <td><?php echo number_format($order['total_amount']); ?> ₫</td>
                            <td>
                                <?php 
                                $statusClass = 'secondary';
                                if ($order['status'] == 'completed') $statusClass = 'success';
                                if ($order['status'] == 'pending') $statusClass = 'warning';
                                if ($order['status'] == 'cancelled') $statusClass = 'danger';
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?>"><?php echo ucfirst($order['status']); ?></span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>