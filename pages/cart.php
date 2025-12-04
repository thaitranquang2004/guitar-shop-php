<?php 
include '../config/database.php'; 
include '../includes/header.php'; 

$total = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }
}
?>

<div class="container mt-5">
    <h2 class="fw-bold mb-4">Giỏ Hàng Của Bạn</h2>
    
    <?php if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
            <h4>Giỏ hàng của bạn đang trống</h4>
            <p class="text-muted mb-4">Hãy thêm sản phẩm yêu thích vào giỏ hàng</p>
            <a href="shop.php" class="btn btn-primary rounded-pill px-4">Mua Sắm Ngay</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3">Sản Phẩm</th>
                                        <th class="py-3">Giá</th>
                                        <th class="py-3">Số Lượng</th>
                                        <th class="py-3">Tổng</th>
                                        <th class="pe-4 py-3"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo htmlspecialchars($item['image']); ?>" width="60" class="rounded me-3" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                                    <span class="fw-bold"><?php echo htmlspecialchars($item['name']); ?></span>
                                                </div>
                                            </td>
                                            <td><?php echo number_format($item['price']); ?> ₫</td>
                                            <td>
                                                <form method="post" action="../actions/update-cart.php" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99" class="form-control form-control-sm" style="width: 70px; display: inline-block;">
                                                    <button type="submit" class="btn btn-sm btn-outline-primary ms-1">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="fw-bold"><?php echo number_format($item['price'] * $item['quantity']); ?> ₫</td>
                                            <td class="pe-4">
                                                <a href="../actions/remove-cart.php?id=<?php echo $id; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa sản phẩm này?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <a href="shop.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Tiếp Tục Mua Sắm</a>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">Tổng Quan Đơn Hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Tạm tính</span>
                            <span><?php echo number_format($total); ?> ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Phí vận chuyển</span>
                            <span class="text-success">Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold fs-5">Tổng Cộng</span>
                            <span class="fw-bold fs-5 text-danger"><?php echo number_format($total); ?> ₫</span>
                        </div>
                        <a href="checkout.php" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm">Tiến Hành Thanh Toán</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>