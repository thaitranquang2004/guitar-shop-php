<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

// Handle Add Banner
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $image = $_POST['image'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $link = $_POST['link'];
    $order = (int)$_POST['display_order'];
    
    $stmt = $pdo->prepare("INSERT INTO banners (image, title, subtitle, link, display_order) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$image, $title, $subtitle, $link, $order]);
    $success = "Thêm banner thành công!";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM banners WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='banners.php';</script>";
}

$banners = $pdo->query("SELECT * FROM banners ORDER BY display_order ASC")->fetchAll();
?>

<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<!-- Add Banner Button -->
<button class="btn btn-primary mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#addBannerForm">
    <i class="fas fa-plus me-2"></i>Thêm Banner Mới
</button>

<!-- Add Banner Form (Collapsed) -->
<div class="collapse mb-4" id="addBannerForm">
    <div class="card table-card">
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL Hình Ảnh</label>
                        <input type="text" name="image" class="form-control" required placeholder="https://...">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tiêu Đề</label>
                        <input type="text" name="title" class="form-control" placeholder="Tiêu đề lớn">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phụ Đề</label>
                        <input type="text" name="subtitle" class="form-control" placeholder="Mô tả ngắn">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Link</label>
                        <input type="text" name="link" class="form-control" placeholder="shop.php">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Thứ Tự</label>
                        <input type="number" name="display_order" class="form-control" value="0">
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Lưu Banner</button>
            </form>
        </div>
    </div>
</div>

<!-- Banners Table -->
<div class="card table-card">
    <div class="card-header">Danh Sách Banner</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Thứ Tự</th>
                        <th>Hình Ảnh</th>
                        <th>Tiêu Đề</th>
                        <th>Link</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($banners as $b): ?>
                        <tr>
                            <td class="ps-4"><?php echo $b['display_order']; ?></td>
                            <td><img src="<?php echo htmlspecialchars($b['image']); ?>" height="50" class="rounded"></td>
                            <td>
                                <div class="fw-bold"><?php echo htmlspecialchars($b['title']); ?></div>
                                <small class="text-muted"><?php echo htmlspecialchars($b['subtitle']); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($b['link']); ?></td>
                            <td>
                                <a href="edit-banner.php?id=<?php echo $b['id']; ?>" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                <a href="banners.php?delete=<?php echo $b['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
