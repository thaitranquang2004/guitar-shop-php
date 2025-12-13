<?php 
include '../config/database.php'; 
include 'includes/header.php'; 

$success = '';
$error = '';

// Get current admin info
$stmt = $pdo->prepare("SELECT * FROM userss WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Update info
    if (!empty($password)) {
        // Plain text as requested
        $stmt = $pdo->prepare("UPDATE userss SET full_name = ?, email = ?, password = ? WHERE id = ?");
        $result = $stmt->execute([$fullName, $email, $password, $_SESSION['user_id']]);
    } else {
        $stmt = $pdo->prepare("UPDATE userss SET full_name = ?, email = ? WHERE id = ?");
        $result = $stmt->execute([$fullName, $email, $_SESSION['user_id']]);
    }
    
    if ($result) {
        $success = "Cập nhật thông tin thành công!";
        // Refresh user data
        $stmt = $pdo->prepare("SELECT * FROM userss WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } else {
        $error = "Có lỗi xảy ra, vui lòng thử lại.";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card table-card">
            <div class="card-header">Cập Nhật Thông Tin</div>
            <div class="card-body p-4">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Tên Đăng Nhập</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                        <div class="form-text">Không thể thay đổi tên đăng nhập.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Họ và Tên</label>
                        <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật Khẩu Mới</label>
                        <input type="password" name="password" class="form-control" placeholder="Để trống nếu không muốn thay đổi">
                        <div class="form-text">Chỉ nhập nếu bạn muốn thay đổi mật khẩu.</div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Lưu Thay Đổi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
