<?php include 'config/database.php'; ?>
<?php include 'includes/header.php'; ?>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
    <div class="carousel-indicators">
        <?php
        // Fetch Banners
        try {
            $banners = $pdo->query("SELECT * FROM banners ORDER BY display_order ASC")->fetchAll();
        } catch (Exception $e) {
            $banners = []; // Fallback if table doesn't exist
        }
        
        foreach ($banners as $index => $banner) {
            echo '<button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="' . $index . '" class="' . ($index == 0 ? 'active' : '') . '"></button>';
        }
        ?>
    </div>
    <div class="carousel-inner">
        <?php if (!empty($banners)): ?>
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?php echo $index == 0 ? 'active' : ''; ?>" style="background: url('<?php echo htmlspecialchars($banner['image']); ?>') no-repeat center center; background-size: cover; height: 600px;">
                    <div class="carousel-caption d-none d-md-block mb-5">
                        <h1 class="display-3 fw-bold text-warning animated fadeInDown"><?php echo htmlspecialchars($banner['title']); ?></h1>
                        <?php if ($banner['subtitle']): ?>
                            <p class="lead text-white animated fadeInUp"><?php echo htmlspecialchars($banner['subtitle']); ?></p>
                        <?php endif; ?>
                        <?php if ($banner['link']): ?>
                            <a href="<?php echo htmlspecialchars($banner['link']); ?>" class="btn btn-primary btn-lg rounded-pill px-5 animated fadeInUp">Xem Ngay</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <!-- Default banner if no banners are found -->
            <div class="carousel-item active" style="background: url('https://via.placeholder.com/1920x600/333333/FFFFFF?text=Welcome+to+Guitar+Shop') no-repeat center center; background-size: cover; height: 600px;">
                <div class="carousel-caption d-none d-md-block mb-5">
                    <h1 class="display-3 fw-bold text-warning">Welcome to Guitar Shop</h1>
                    <p class="lead text-white">Khám phá bộ sưu tập guitar chất lượng cao.</p>
                    <a href="pages/shop.php" class="btn btn-primary btn-lg rounded-pill px-5">Mua Sắm Ngay</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>

<main class="container mt-5">
    <!-- Sản Phẩm Nổi Bật -->
    <div class="section-title">
        <h2 class="fw-bold text-uppercase">Sản Phẩm Nổi Bật</h2>
    </div>
    
    <div class="row mt-4">
        <?php
        $stmt = $pdo->query("SELECT * FROM products LIMIT 4");
        while ($product = $stmt->fetch()) {
            echo '
            <div class="col-md-3 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="position-relative overflow-hidden">
                        <img src="' . htmlspecialchars($product['image']) . '" class="card-img-top" alt="' . htmlspecialchars($product['name']) . '">
                        <div class="position-absolute top-0 end-0 p-2">
                             <span class="badge bg-danger rounded-pill">Hot</span>
                        </div>
                    </div>
                    <div class="card-body d-flex flex-column text-center">
                        <h5 class="card-title fw-bold mb-2">' . htmlspecialchars($product['name']) . '</h5>
                        <p class="card-text text-muted small flex-grow-1">' . htmlspecialchars(substr($product['description'], 0, 80)) . '...</p>
                        <div class="mb-3">
                            <span class="price-tag">' . number_format($product['price']) . ' VNĐ</span>
                        </div>
                        <a href="actions/add-to-cart.php?id=' . $product['id'] . '" class="btn btn-outline-primary w-100 rounded-pill">
                            <i class="fas fa-cart-plus me-2"></i>Thêm Vào Giỏ
                        </a>
                    </div>
                </div>
            </div>';
        }
        ?>
    </div>
    
    <div class="text-center mt-4 mb-5">
        <a href="pages/shop.php" class="btn btn-secondary rounded-pill px-4">Xem Tất Cả Sản Phẩm</a>
    </div>

    <!-- Blog Nổi Bật -->
    <div class="section-title mt-5">
        <h2 class="fw-bold text-uppercase">Tin Tức Mới Nhất</h2>
    </div>
    
    <div class="row mt-4">
        <?php
        // Check if table exists first to avoid error if user hasn't imported SQL yet
        try {
            $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT 3");
            while ($post = $stmt->fetch()) {
                echo '
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <img src="' . htmlspecialchars($post['image']) . '" class="card-img-top" alt="' . htmlspecialchars($post['title']) . '" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">' . htmlspecialchars($post['title']) . '</h5>
                            <p class="card-text text-muted small">' . htmlspecialchars(substr($post['content'], 0, 120)) . '...</p>
                            <a href="pages/blog.php?id=' . $post['id'] . '" class="text-primary text-decoration-none fw-bold">Đọc Thêm <i class="fas fa-arrow-right small"></i></a>
                        </div>
                    </div>
                </div>';
            }
        } catch (Exception $e) {
            echo '<div class="col-12"><p class="text-center text-muted">Chưa có bài viết nào.</p></div>';
        }
        ?>
    </div>

    <!-- Về Chúng Tôi -->
    <section class="mt-5 py-5 bg-light rounded-3 text-center">
        <div class="container">
            <h2 class="fw-bold mb-4">Tại Sao Chọn Guitar Shop?</h2>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h4>Chính Hãng 100%</h4>
                    <p class="text-muted">Cam kết hàng chính hãng từ các thương hiệu lớn.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h4>Giao Hàng Nhanh</h4>
                    <p class="text-muted">Vận chuyển toàn quốc, nhận hàng trong 2-3 ngày.</p>
                </div>
                <div class="col-md-4 mb-3">
                    <i class="fas fa-headset fa-3x text-warning mb-3"></i>
                    <h4>Hỗ Trợ 24/7</h4>
                    <p class="text-muted">Đội ngũ tư vấn chuyên nghiệp, nhiệt tình.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>