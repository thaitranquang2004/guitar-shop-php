<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

$orders = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC")->fetchAll();
?>

<div class="card table-card">
    <div class="card-header">Tất Cả Đơn Hàng</div>
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
                    <?php foreach ($orders as $order): ?>
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
                            <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                            <td>
                                <a href="order-detail.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i> Xem Chi Tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
