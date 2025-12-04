<?php 
include '../config/database.php'; 
include '../includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='container mt-5'><p>Sản phẩm không tồn tại.</p></div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Trang Chủ</a></li>
            <li class="breadcrumb-item"><a href="shop.php">Sản Phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($product['name']); ?></li>
        </ol>
    </nav>

    <div class="row mt-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
        </div>
        <div class="col-md-6">
            <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h1>
            <div class="mb-3">
                <span class="text-warning">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </span>
                <span class="text-muted ms-2">(4.5/5)</span>
            </div>
            <h2 class="text-danger fw-bold mb-3"><?php echo number_format($product['price']); ?> VNĐ</h2>
            <p class="text-muted mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
            
            <!-- Add to Cart Button -->
            <a href="../actions/add-to-cart.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-lg w-100 rounded-pill mb-4">
                <i class="fas fa-cart-plus me-2"></i>Thêm Vào Giỏ Hàng
            </a>
            
            <!-- Product Benefits -->
            <div class="border-top pt-4">
                <div class="d-flex mb-3">
                    <i class="fas fa-shipping-fast text-success me-3 mt-1"></i>
                    <div>
                        <strong>Miễn phí vận chuyển</strong>
                        <p class="mb-0 small text-muted">Cho đơn hàng trên 5.000.000 VNĐ</p>
                    </div>
                </div>
                <div class="d-flex">
                    <i class="fas fa-shield-alt text-primary me-3 mt-1"></i>
                    <div>
                        <strong>Bảo hành 12 tháng</strong>
                        <p class="mb-0 small text-muted">Chính hãng 100%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products (Simple query for now) -->
    <div class="mt-5">
        <h3 class="fw-bold border-bottom pb-2 mb-4">Sản Phẩm Liên Quan</h3>
        <div class="row">
             <?php
            $stmt = $pdo->query("SELECT * FROM products WHERE id != $id LIMIT 4");
            while ($relProduct = $stmt->fetch()) {
                echo '
                <div class="col-md-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="position-relative overflow-hidden">
                            <img src="' . htmlspecialchars($relProduct['image']) . '" class="card-img-top" alt="' . htmlspecialchars($relProduct['name']) . '">
                        </div>
                        <div class="card-body d-flex flex-column text-center">
                            <h6 class="card-title fw-bold mb-2"><a href="product.php?id=' . $relProduct['id'] . '" class="text-decoration-none text-dark">' . htmlspecialchars($relProduct['name']) . '</a></h6>
                            <div class="mb-3">
                                <span class="price-tag small">' . number_format($relProduct['price']) . ' VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
