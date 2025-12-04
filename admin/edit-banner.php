<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Update
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $image = $_POST['image'];
    $title = $_POST['title'];
    $subtitle = $_POST['subtitle'];
    $link = $_POST['link'];
    $order = (int)$_POST['display_order'];
    
    $stmt = $pdo->prepare("UPDATE banners SET image = ?, title = ?, subtitle = ?, link = ?, display_order = ? WHERE id = ?");
    $stmt->execute([$image, $title, $subtitle, $link, $order, $id]);
    $success = "Cập nhật banner thành công!";
}

// Get Banner
$stmt = $pdo->prepare("SELECT * FROM banners WHERE id = ?");
$stmt->execute([$id]);
$banner = $stmt->fetch();

if (!$banner) {
    echo "<div class='alert alert-danger'>Banner không tồn tại.</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Chỉnh Sửa Banner</h4>
    <a href="banners.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Quay Lại</a>
</div>

<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card table-card">
            <div class="card-body p-4">
                <form method="post">
                    <input type="hidden" name="action" value="update">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">URL Hình Ảnh</label>
                            <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($banner['image']); ?>" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Tiêu Đề</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($banner['title']); ?>">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Phụ Đề</label>
                            <input type="text" name="subtitle" class="form-control" value="<?php echo htmlspecialchars($banner['subtitle']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Link</label>
                            <input type="text" name="link" class="form-control" value="<?php echo htmlspecialchars($banner['link']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Thứ Tự</label>
                            <input type="number" name="display_order" class="form-control" value="<?php echo $banner['display_order']; ?>">
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
