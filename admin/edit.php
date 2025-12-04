<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Handle Update
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_POST['image']; 
    $category = $_POST['category'];
    
    $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ?, category = ? WHERE id = ?");
    $stmt->execute([$name, $price, $desc, $image, $category, $id]);
    $success = "Cập nhật sản phẩm thành công!";
}

// Get Product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    echo "<div class='alert alert-danger'>Sản phẩm không tồn tại.</div>";
    include 'includes/footer.php';
    exit;
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Chỉnh Sửa Sản Phẩm</h4>
    <a href="products.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i>Quay Lại</a>
</div>

<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card table-card">
            <div class="card-body p-4">
                <form method="post">
                    <input type="hidden" name="action" value="update">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên Sản Phẩm</label>
                            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Danh Mục</label>
                            <select name="category" class="form-select">
                                <option value="Electric" <?php echo $product['category'] == 'Electric' ? 'selected' : ''; ?>>Electric Guitar</option>
                                <option value="Acoustic" <?php echo $product['category'] == 'Acoustic' ? 'selected' : ''; ?>>Acoustic Guitar</option>
                                <option value="Bass" <?php echo $product['category'] == 'Bass' ? 'selected' : ''; ?>>Bass Guitar</option>
                                <option value="Accessories" <?php echo $product['category'] == 'Accessories' ? 'selected' : ''; ?>>Phụ Kiện</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">URL Hình Ảnh</label>
                            <input type="text" name="image" class="form-control" value="<?php echo htmlspecialchars($product['image']); ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Mô Tả</label>
                            <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($product['description']); ?></textarea>
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
