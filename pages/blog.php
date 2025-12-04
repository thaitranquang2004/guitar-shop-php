<?php 
include '../config/database.php'; 
include '../includes/header.php'; 

$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
?>

<main class="container mt-5">
    <?php if ($post_id > 0):
        // Single blog post view
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$post_id]);
        $post = $stmt->fetch();
        if ($post):
    ?>
        <article class="row justify-content-center">
            <div class="col-md-8">
                <img src="<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid mb-3 rounded shadow" alt="<?php echo htmlspecialchars($post['title']); ?>">
                <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($post['title']); ?></h1>
                <p class="text-muted mb-4"><i class="far fa-calendar-alt me-2"></i><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></p>
                <div class="blog-content lh-lg"><?php echo nl2br(htmlspecialchars($post['content'])); ?></div>
                <div class="mt-5">
                    <a href="blog.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Quay Lại</a>
                </div>
            </div>
        </article>
    <?php else: ?>
        <div class="alert alert-warning">Bài viết không tồn tại.</div>
    <?php endif; ?>
    
    <?php else: // List view ?>
        <div class="section-title text-center mb-5">
            <h2 class="fw-bold text-uppercase">Blog & Tin Tức</h2>
            <p class="text-muted">Cập nhật kiến thức và xu hướng âm nhạc</p>
        </div>
        
        <div class="row">
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC");
                while ($post = $stmt->fetch()) {
                    echo '
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="' . htmlspecialchars($post['image']) . '" class="card-img-top" style="height: 200px; object-fit: cover;" alt="' . htmlspecialchars($post['title']) . '">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">' . htmlspecialchars($post['title']) . '</h5>
                                <p class="card-text text-muted small">' . htmlspecialchars(substr($post['content'], 0, 100)) . '...</p>
                                <a href="blog.php?id=' . $post['id'] . '" class="btn btn-outline-primary btn-sm rounded-pill">Đọc Tiếp</a>
                            </div>
                        </div>
                    </div>';
                }
            } catch (Exception $e) {
                echo '<div class="col-12"><p class="text-center text-muted">Chưa có bài viết nào.</p></div>';
            }
            ?>
        </div>
    <?php endif; ?>
</main>

<?php include '../includes/footer.php'; ?>