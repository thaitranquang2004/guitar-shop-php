<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

// Handle Add Product
if (isset($_POST['action']) && $_POST['action'] == 'add') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $desc = $_POST['description'];
    $image = $_POST['image']; 
    $category = $_POST['category'];
    
    $stmt = $pdo->prepare("INSERT INTO products (name, price, description, image, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $price, $desc, $image, $category]);
    $success = "Thêm sản phẩm thành công!";
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    echo "<script>window.location.href='products.php';</script>";
}

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>

<?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

<!-- Add Product Button -->
<button class="btn btn-primary mb-4" type="button" data-bs-toggle="collapse" data-bs-target="#addProductForm">
    <i class="fas fa-plus me-2"></i>Thêm Sản Phẩm Mới
</button>

<!-- Add Product Form (Collapsed) -->
<div class="collapse mb-4" id="addProductForm">
    <div class="card table-card">
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Sản Phẩm</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Giá (VNĐ)</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Danh Mục</label>
                        <select name="category" class="form-select">
                            <option value="Electric">Electric Guitar</option>
                            <option value="Acoustic">Acoustic Guitar</option>
                            <option value="Bass">Bass Guitar</option>
                            <option value="Accessories">Phụ Kiện</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL Hình Ảnh</label>
                        <input type="text" name="image" class="form-control" placeholder="https://...">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Lưu Sản Phẩm</button>
            </form>
        </div>
    </div>
</div>

<!-- Products Table -->
<div class="card table-card">
    <div class="card-header">Danh Sách Sản Phẩm (<?php echo count($products); ?>)</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>Hình</th>
                        <th>Tên</th>
                        <th>Danh Mục</th>
                        <th>Giá</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td class="ps-4">#<?php echo $p['id']; ?></td>
                            <td><img src="<?php echo htmlspecialchars($p['image']); ?>" width="50" class="rounded"></td>
                            <td class="fw-bold"><?php echo htmlspecialchars($p['name']); ?></td>
                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($p['category'] ?? 'N/A'); ?></span></td>
                            <td><?php echo number_format($p['price']); ?> ₫</td>
                            <td>
                                <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-warning me-1"><i class="fas fa-edit"></i></a>
                                <a href="products.php?delete=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
