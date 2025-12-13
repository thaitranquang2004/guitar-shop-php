<?php
include 'config/database.php';

$message = ''; $error = '';
if ($_POST && isset($_POST['setup'])):
    try {
        // --- 1. CLEAN UP: XÓA BẢNG CŨ ---
        // PostgreSQL dùng CASCADE để xóa bảng cha và bảng con liên quan
        $tables_to_drop = [
            'order_items', 'order_detail', '"order"', 'orders', 
            'book', 'products', 'users', 'userss', 
            'category', 'publisher', 'admin', 'banners', 'blog_posts', 'news'
        ];

        foreach ($tables_to_drop as $table) {
            $pdo->exec("DROP TABLE IF EXISTS $table CASCADE");
        }

        // --- 2. TẠO BẢNG (PostgreSQL Syntax) ---

        // Table: category
        $pdo->exec("
            CREATE TABLE category (
                cat_id VARCHAR(5) PRIMARY KEY,
                cat_name VARCHAR(50) NOT NULL
            );
        ");

        // Table: publisher
        $pdo->exec("
            CREATE TABLE publisher (
                pub_id VARCHAR(5) PRIMARY KEY,
                pub_name VARCHAR(50) NOT NULL
            );
        ");

        // Table: users
        $pdo->exec("
            CREATE TABLE users (
                email VARCHAR(50) PRIMARY KEY,
                password VARCHAR(100) NOT NULL,
                name VARCHAR(100) NOT NULL,
                address VARCHAR(200) NOT NULL,
                phone VARCHAR(20) DEFAULT NULL
            );
        ");

        // Table: userss (Dùng SERIAL cho auto increment)
        $pdo->exec("
            CREATE TABLE userss (
                id SERIAL PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                full_name VARCHAR(100) DEFAULT NULL,
                role VARCHAR(20) DEFAULT 'customer',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Table: admin
        $pdo->exec("
            CREATE TABLE admin (
                username VARCHAR(50) PRIMARY KEY,
                password VARCHAR(100) DEFAULT NULL,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(100) DEFAULT NULL,
                phone VARCHAR(20) DEFAULT NULL
            );
        ");

        // Table: book (FK đến publisher, category)
        $pdo->exec("
            CREATE TABLE book (
                book_id VARCHAR(15) PRIMARY KEY,
                book_name VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                price INT NOT NULL,
                img VARCHAR(100) NOT NULL,
                pub_id VARCHAR(5) NOT NULL,
                cat_id VARCHAR(5) NOT NULL,
                CONSTRAINT fk_book_pub FOREIGN KEY (pub_id) REFERENCES publisher (pub_id),
                CONSTRAINT fk_book_cat FOREIGN KEY (cat_id) REFERENCES category (cat_id)
            );
        ");

        // Table: "order" (QUAN TRỌNG: Tên bảng phải nằm trong ngoặc kép)
        $pdo->exec("
            CREATE TABLE \"order\" (
                order_id VARCHAR(100) PRIMARY KEY,
                email VARCHAR(50) NOT NULL,
                order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                consignee_name VARCHAR(100) NOT NULL,
                consignee_add VARCHAR(200) NOT NULL,
                consignee_phone VARCHAR(20) NOT NULL DEFAULT '',
                status INT NOT NULL DEFAULT 0,
                CONSTRAINT fk_order_users FOREIGN KEY (email) REFERENCES users (email) ON DELETE CASCADE
            );
        ");

        // Table: order_detail
        $pdo->exec("
            CREATE TABLE order_detail (
                order_id VARCHAR(100) NOT NULL,
                book_id VARCHAR(15) NOT NULL,
                quantity INT NOT NULL DEFAULT 0,
                price DECIMAL(10,2) NOT NULL DEFAULT 0,
                PRIMARY KEY (order_id, book_id),
                CONSTRAINT fk_od_order FOREIGN KEY (order_id) REFERENCES \"order\" (order_id) ON DELETE CASCADE,
                CONSTRAINT fk_od_book FOREIGN KEY (book_id) REFERENCES book (book_id) ON DELETE CASCADE
            );
        ");

        // Table: products
        $pdo->exec("
            CREATE TABLE products (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                description TEXT,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                category VARCHAR(50) DEFAULT 'Guitar'
            );
        ");

        // Table: orders (Bảng mới)
        $pdo->exec("
            CREATE TABLE orders (
                id SERIAL PRIMARY KEY,
                user_id INT DEFAULT NULL,
                customer_name VARCHAR(100) NOT NULL,
                customer_email VARCHAR(100) NOT NULL,
                customer_phone VARCHAR(20) NOT NULL,
                customer_address TEXT NOT NULL,
                total_amount DECIMAL(10,2) NOT NULL,
                status VARCHAR(20) DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Table: order_items
        $pdo->exec("
            CREATE TABLE order_items (
                id SERIAL PRIMARY KEY,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Table: banners
        $pdo->exec("
            CREATE TABLE banners (
                id SERIAL PRIMARY KEY,
                image VARCHAR(255) NOT NULL,
                title VARCHAR(255) DEFAULT NULL,
                subtitle VARCHAR(255) DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                display_order INT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Table: blog_posts
        $pdo->exec("
            CREATE TABLE blog_posts (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Table: news
        $pdo->exec("
            CREATE TABLE news (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");


        // --- 3. INSERT DATA (Đúng thứ tự logic) ---
        $inserted = [];

        // 1. Users (Data mẫu)
        if ($pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO users (email, password, name, address, phone) VALUES 
            ('abcd@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Minh Triết', 'Q1', '99999999'),
            ('hung.stu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Trần Văn Hùng', 'Quận 3', '090090999')");
            $inserted[] = 'users';
        }

        // 2. Category
        if ($pdo->query("SELECT COUNT(*) FROM category")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO category (cat_id, cat_name) VALUES ('td', 'Từ điển'), ('th', 'Thủ thuật')");
            $inserted[] = 'category';
        }

        // 3. Publisher
        if ($pdo->query("SELECT COUNT(*) FROM publisher")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO publisher (pub_id, pub_name) VALUES ('gd', 'Giáo dục'), ('hcm', 'Tổng Hợp HCM'), ('hnv', 'Hội Nhà Văn'), ('pn', 'Phụ Nữ'), ('tn', 'Thanh Niên'), ('vh', 'Văn Học'), ('vhtt', 'Văn Hóa Thông Tin')");
            $inserted[] = 'publisher';
        }

        // 4. Book (Sách mẫu)
        if ($pdo->query("SELECT COUNT(*) FROM book")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO book (book_id, book_name, description, price, img, pub_id, cat_id) VALUES 
            ('td01', 'Từ Điển mẫu câu tiếng Nhật', 'Tập hợp tất cả các mẫu câu tiếng Nhật.', 450000, 'td01.jpg', 'gd', 'td'),
            ('td02', 'Từ Điển Kinh Doanh', 'Sách kinh doanh.', 195000, 'td02.gif', 'vhtt', 'td'),
            ('th01', '100 thủ thuật với Excel 2010', '100 thủ thuật excel.', 54000, 'th01.gif', 'hcm', 'th')");
            $inserted[] = 'book';
        }

        // 5. Order ("order" phải có ngoặc kép)
        if ($pdo->query("SELECT COUNT(*) FROM \"order\"")->fetchColumn() == 0) {
            // Dùng NOW() thay vì chuỗi cứng
            $pdo->exec("INSERT INTO \"order\" (order_id, email, order_date, consignee_name, consignee_add, consignee_phone, status) VALUES 
            ('ORD001', 'abcd@yahoo.com', NOW(), 'Nguyễn Minh Triết', 'Q1, HCM', '99999999', 0),
            ('ORD002', 'hung.stu@gmail.com', NOW(), 'Trần Văn Hùng', 'Quận 3, HCM', '090090999', 1)");
            $inserted[] = 'order';
        }

        // 6. Order Detail
        if ($pdo->query("SELECT COUNT(*) FROM order_detail")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO order_detail (order_id, book_id, quantity, price) VALUES 
            ('ORD001', 'td01', 1, 450000),
            ('ORD002', 'th01', 2, 54000)");
            $inserted[] = 'order_detail';
        }

        // 7. Userss (Admin)
        if ($pdo->query("SELECT COUNT(*) FROM userss")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO userss (username, password, email, full_name, role) VALUES ('admin', '1234', 'admin1@guitarshop.com', 'Administrator', 'admin')");
            $inserted[] = 'userss';
        }

        // 8. Products, Admin, Banners... (Dữ liệu mẫu còn lại)
        if ($pdo->query("SELECT COUNT(*) FROM admin")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO admin (username, password, name) VALUES ('admin', '123456', 'Admin Default')");
            $inserted[] = 'admin';
        }
        
        if ($pdo->query("SELECT COUNT(*) FROM products")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO products (name, price, description, image, category) VALUES 
            ('Fender Stratocaster', 18500000.00, 'Guitar điện', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f', 'Electric'),
            ('Martin D-28', 75000000.00, 'Acoustic đỉnh cao', 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8', 'Acoustic')");
            $inserted[] = 'products';
        }

        if ($pdo->query("SELECT COUNT(*) FROM banners")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO banners (image, title, subtitle, link, display_order) VALUES ('https://images.unsplash.com/photo-1511379938547-c1f69419868d', 'Banner 1', 'Sub 1', '#', 1)");
            $inserted[] = 'banners';
        }

        if ($pdo->query("SELECT COUNT(*) FROM news")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO news (title, content, image) VALUES ('News 1', 'Content 1', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f')");
            $inserted[] = 'news';
        }

        $message = "Setup thành công trên PostgreSQL! Tables: " . implode(', ', $inserted);

    } catch (PDOException $e) {
        $error = "Lỗi setup: " . $e->getMessage();
    }
endif;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Setup DB - Guitar Shop (PostgreSQL)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Setup Database Guitar Shop (Phiên bản PostgreSQL)</h2>
    <p>Script này đã được sửa lỗi cú pháp đặc biệt cho PostgreSQL (loại bỏ foreign_key_checks, sửa lỗi tên bảng order).</p>
    <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <input type="hidden" name="setup" value="1">
        <button type="submit" class="btn btn-primary">Chạy Setup (Postgres)</button>
    </form>
</body>
</html>