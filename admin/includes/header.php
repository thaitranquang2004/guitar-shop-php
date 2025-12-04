<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}
// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Guitar Shop</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Admin CSS -->
    <link href="../assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">

    <!-- Sidebar -->
    <nav class="admin-sidebar d-flex flex-column">
        <div class="sidebar-brand">
            <i class="fas fa-guitar me-2"></i>Admin Panel
        </div>
        <div class="nav flex-column flex-grow-1">
            <a href="dashboard.php" class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a href="products.php" class="nav-link <?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Sản Phẩm
            </a>
            <a href="banners.php" class="nav-link <?php echo $current_page == 'banners.php' ? 'active' : ''; ?>">
                <i class="fas fa-images"></i> Banner
            </a>
            <a href="blog-posts.php" class="nav-link <?php echo $current_page == 'blog-posts.php' || $current_page == 'edit-blog.php' ? 'active' : ''; ?>">
                <i class="fas fa-blog"></i> Blog
            </a>
            <a href="orders.php" class="nav-link <?php echo $current_page == 'orders.php' || $current_page == 'order-detail.php' ? 'active' : ''; ?>">
                <i class="fas fa-shopping-cart"></i> Đơn Hàng
            </a>
            <a href="profile.php" class="nav-link <?php echo $current_page == 'profile.php' ? 'active' : ''; ?>">
                <i class="fas fa-user-cog"></i> Tài Khoản
            </a>
            <a href="../index.php" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt"></i> Xem Website
            </a>
            <a href="logout.php" class="nav-link text-danger mt-auto mb-4">
                <i class="fas fa-sign-out-alt"></i> Đăng Xuất
            </a>
        </div>
    </nav>

    <!-- Main Content Wrapper -->
    <div class="admin-content">
        <!-- Top Navbar -->
        <nav class="navbar admin-navbar d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-secondary">
                <?php 
                if ($current_page == 'dashboard.php') echo 'Tổng Quan';
                elseif ($current_page == 'products.php') echo 'Quản Lý Sản Phẩm';
                elseif ($current_page == 'banners.php' || $current_page == 'edit-banner.php') echo 'Quản Lý Banner';
                elseif ($current_page == 'blog-posts.php' || $current_page == 'edit-blog.php') echo 'Quản Lý Blog';
                elseif ($current_page == 'orders.php') echo 'Quản Lý Đơn Hàng';
                elseif ($current_page == 'profile.php') echo 'Thông Tin Tài Khoản';
                else echo 'Admin Panel';
                ?>
            </h4>
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted">Xin chào, <strong>Admin</strong></span>
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" class="rounded-circle" width="40" alt="Avatar">
            </div>
        </nav>
