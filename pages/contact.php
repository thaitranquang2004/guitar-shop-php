<?php 
include '../config/database.php'; 
include '../includes/header.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);
    
    // Here you would normally send an email or save to database
    // mail('your-email@example.com', 'Liên Hệ Từ Guitar Shop', $message, "From: $email");
    $success = 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!';
}
?>

<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold mb-4">Liên Hệ Với Chúng Tôi</h2>
                    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Họ và Tên</label>
                            <input type="text" name="name" class="form-control" required placeholder="Nhập tên của bạn">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required placeholder="email@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tin Nhắn</label>
                            <textarea name="message" class="form-control" rows="5" required placeholder="Nội dung tin nhắn..."></textarea>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Gửi Tin Nhắn</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>