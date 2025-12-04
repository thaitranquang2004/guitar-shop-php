<?php 
include '../config/database.php'; 
include '../includes/header.php'; 

$category = isset($_GET['category']) ? $_GET['category'] : '';
$sql = "SELECT * FROM products";
$params = [];

if ($category) {
    $sql .= " WHERE category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
?>

<div class="container mt-5">
    <div class="section-title text-center mb-5">
        <h2 class="fw-bold text-uppercase"><?php echo $category ? 'Danh Mục: ' . htmlspecialchars($category) : 'Tất Cả Sản Phẩm'; ?></h2>
        <p class="text-muted">Khám phá bộ sưu tập đầy đủ của chúng tôi</p>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Danh Mục</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 px-0"><a href="shop.php" class="text-decoration-none <?php echo !$category ? 'fw-bold text-primary' : 'text-dark'; ?>">Tất Cả</a></li>
                        <li class="list-group-item border-0 px-0"><a href="shop.php?category=Electric" class="text-decoration-none <?php echo $category == 'Electric' ? 'fw-bold text-primary' : 'text-dark'; ?>">Guitar Electric</a></li>
                        <li class="list-group-item border-0 px-0"><a href="shop.php?category=Acoustic" class="text-decoration-none <?php echo $category == 'Acoustic' ? 'fw-bold text-primary' : 'text-dark'; ?>">Guitar Acoustic</a></li>
                        <li class="list-group-item border-0 px-0"><a href="shop.php?category=Bass" class="text-decoration-none <?php echo $category == 'Bass' ? 'fw-bold text-primary' : 'text-dark'; ?>">Guitar Bass</a></li>
                        <li class="list-group-item border-0 px-0"><a href="shop.php?category=Accessories" class="text-decoration-none <?php echo $category == 'Accessories' ? 'fw-bold text-primary' : 'text-dark'; ?>">Phụ Kiện & Amp</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="col-md-9">
            <div class="row">
                <?php
                while ($product = $stmt->fetch()) {
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="position-relative overflow-hidden">
                                <img src="' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
                            </div>
                            <div class="card-body d-flex flex-column text-center">
                                <h5 class="card-title fw-bold mb-2">' . htmlspecialchars($product['name']) . '</h5>
                                <p class="card-text text-muted small flex-grow-1">' . htmlspecialchars(substr($product['description'], 0, 80)) . '...</p>
                                <div class="mb-3">
                                    <span class="price-tag">' . number_format($product['price']) . ' VNĐ</span>
                                </div>
                                <a href="../actions/add-to-cart.php?id=' . $product['id'] . '" class="btn btn-outline-primary w-100 rounded-pill">
                                    <i class="fas fa-cart-plus me-2"></i>Thêm Vào Giỏ
                                </a>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
