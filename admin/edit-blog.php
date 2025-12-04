<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get blog post
$stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    echo "<div class='alert alert-danger'>Bài viết không tồn tại.</div>";
    include 'includes/footer.php';
    exit;
}

// Handle Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image'];
    
    $stmt = $pdo->prepare("UPDATE blog_posts SET title = ?, content = ?, image = ? WHERE id = ?");
    $result = $stmt->execute([$title, $content, $image, $id]);
    
    if ($result) {
        $success = "Cập nhật bài viết thành công!";
        // Refresh post data
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$id]);
        $post = $stmt->fetch();
    } else {
        $error = "Có lỗi xảy ra. Vui lòng thử lại.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Chỉnh Sửa Bài Viết</span>
                <a href="blog-posts.php" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Quay Lại
                </a>
            </div>
            <div class="card-body p-4">
                <?php if (isset($success)): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Tiêu Đề Bài Viết</label>
                        <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Hình Ảnh</label>
                        <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($post['image']); ?>" required>
                        <small class="form-text text-muted">Link hình ảnh đại diện</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hình Ảnh Hiện Tại</label>
                        <div>
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" class="img-fluid rounded shadow-sm" style="max-height: 200px;" alt="Preview">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nội Dung</label>
                        <textarea name="content" class="form-control" rows="12" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                        </button>
                        <a href="blog-posts.php?delete=<?php echo $post['id']; ?>" 
                           class="btn btn-danger" 
                           onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                            <i class="fas fa-trash me-2"></i>Xóa Bài Viết
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
