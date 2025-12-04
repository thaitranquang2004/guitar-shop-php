<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

// Handle Add Blog Post
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_POST['image']; 
    
    $stmt = $pdo->prepare("INSERT INTO blog_posts (title, content, image) VALUES (?, ?, ?)");
    $stmt->execute([$title, $content, $image]);
    $success = "Thêm bài viết thành công!";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM blog_posts WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='blog-posts.php';</script>";
}

try {
    $posts = $pdo->query("SELECT * FROM blog_posts ORDER BY created_at DESC")->fetchAll();
} catch (Exception $e) {
    $posts = [];
    $error = "Bảng blog_posts chưa tồn tại. Vui lòng tạo bảng trong database.";
}
?>

<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
<?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

<!-- Add Blog Post Button -->
<button class="btn btn-primary mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#addBlogForm">
    <i class="fas fa-plus me-2"></i>Thêm Bài Viết Mới
</button>

<!-- Add Blog Post Form (Collapsed) -->
<div class="collapse mb-4" id="addBlogForm">
    <div class="card table-card">
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Tiêu Đề Bài Viết</label>
                        <input type="text" name="title" class="form-control" required placeholder="Nhập tiêu đề...">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">URL Hình Ảnh</label>
                        <input type="text" name="image" class="form-control" placeholder="https://..." required>
                        <small class="form-text text-muted">Link hình ảnh đại diện cho bài viết</small>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Nội Dung</label>
                        <textarea name="content" class="form-control" rows="8" required placeholder="Nhập nội dung bài viết..."></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Lưu Bài Viết</button>
            </form>
        </div>
    </div>
</div>

<!-- Blog Posts Table -->
<div class="card table-card">
    <div class="card-header">Danh Sách Bài Viết (<?php echo count($posts); ?>)</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Hình</th>
                        <th>Tiêu Đề</th>
                        <th>Nội Dung</th>
                        <th>Ngày Đăng</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($posts)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                <p>Chưa có bài viết nào.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($posts as $p): ?>
                            <tr>
                                <td class="ps-4">#<?php echo $p['id']; ?></td>
                                <td><img src="<?php echo htmlspecialchars($p['image']); ?>" width="60" class="rounded" alt="<?php echo htmlspecialchars($p['title']); ?>"></td>
                                <td class="fw-bold"><?php echo htmlspecialchars($p['title']); ?></td>
                                <td><small class="text-muted"><?php echo htmlspecialchars(substr($p['content'], 0, 80)); ?>...</small></td>
                                <td><small><?php echo date('d/m/Y', strtotime($p['created_at'])); ?></small></td>
                                <td>
                                    <a href="edit-blog.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                    <a href="blog-posts.php?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
