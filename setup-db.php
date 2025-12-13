<?php
include 'config/database.php';

$message = ''; $error = '';

if ($_POST && isset($_POST['setup'])):
    try {
        // =================================================================================
        // PHẦN 1: XÓA BẢNG CŨ (CLEANUP)
        // =================================================================================
        // Dùng CASCADE để xóa sạch bảng và các ràng buộc khóa ngoại liên quan
        $tables = [
            'order_items', 'order_detail', '"order"', 'orders', 
            'book', 'products', 'users', 'userss', 
            'category', 'publisher', 'admin', 'banners', 'blog_posts', 'news'
        ];

        foreach ($tables as $table) {
            $pdo->exec("DROP TABLE IF EXISTS $table CASCADE");
        }

        // =================================================================================
        // PHẦN 2: TẠO CẤU TRÚC BẢNG (POSTGRESQL SYNTAX)
        // =================================================================================

        // 1. Table: admin
        $pdo->exec("CREATE TABLE admin (
            username VARCHAR(30) PRIMARY KEY,
            password VARCHAR(32) DEFAULT NULL,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(60) DEFAULT NULL,
            phone VARCHAR(12) DEFAULT NULL
        )");

        // 2. Table: banners
        $pdo->exec("CREATE TABLE banners (
            id SERIAL PRIMARY KEY,
            image VARCHAR(255) NOT NULL,
            title VARCHAR(255) DEFAULT NULL,
            subtitle VARCHAR(255) DEFAULT NULL,
            link VARCHAR(255) DEFAULT NULL,
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 3. Table: blog_posts
        $pdo->exec("CREATE TABLE blog_posts (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 4. Table: category
        $pdo->exec("CREATE TABLE category (
            cat_id VARCHAR(5) PRIMARY KEY,
            cat_name VARCHAR(50) NOT NULL
        )");

        // 5. Table: publisher
        $pdo->exec("CREATE TABLE publisher (
            pub_id VARCHAR(5) PRIMARY KEY,
            pub_name VARCHAR(30) NOT NULL
        )");

        // 6. Table: news
        $pdo->exec("CREATE TABLE news (
            id SERIAL PRIMARY KEY,
            title VARCHAR(200) NOT NULL,
            img VARCHAR(50) DEFAULT NULL,
            short_content VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            hot INT NOT NULL DEFAULT 0
        )");

        // 7. Table: users (Cần tạo trước order)
        $pdo->exec("CREATE TABLE users (
            email VARCHAR(50) PRIMARY KEY,
            password VARCHAR(32) NOT NULL,
            name VARCHAR(50) NOT NULL,
            address VARCHAR(100) NOT NULL,
            phone VARCHAR(10) DEFAULT NULL
        )");

        // 8. Table: userss (Hệ thống user mới)
        $pdo->exec("CREATE TABLE userss (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE,
            full_name VARCHAR(100) DEFAULT NULL,
            role VARCHAR(20) DEFAULT 'customer',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 9. Table: products
        $pdo->exec("CREATE TABLE products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            description TEXT,
            image VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            category VARCHAR(50) DEFAULT 'Guitar'
        )");

        // 10. Table: book (Có FK tới publisher, category)
        $pdo->exec("CREATE TABLE book (
            book_id VARCHAR(15) PRIMARY KEY,
            book_name VARCHAR(250) NOT NULL,
            description TEXT NOT NULL,
            price INT NOT NULL,
            img VARCHAR(50) NOT NULL,
            pub_id VARCHAR(5) NOT NULL,
            cat_id VARCHAR(5) NOT NULL,
            CONSTRAINT fk_book_pub FOREIGN KEY (pub_id) REFERENCES publisher (pub_id),
            CONSTRAINT fk_book_cat FOREIGN KEY (cat_id) REFERENCES category (cat_id)
        )");

        // 11. Table: "order" (Lưu ý dấu ngoặc kép, FK tới users)
        $pdo->exec("CREATE TABLE \"order\" (
            order_id VARCHAR(100) PRIMARY KEY,
            email VARCHAR(50) NOT NULL,
            order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            consignee_name VARCHAR(50) NOT NULL,
            consignee_add VARCHAR(80) NOT NULL,
            consignee_phone VARCHAR(12) NOT NULL DEFAULT '',
            status INT NOT NULL DEFAULT 0,
            CONSTRAINT fk_order_users FOREIGN KEY (email) REFERENCES users (email) ON DELETE CASCADE
        )");

        // 12. Table: orders (Table mới, độc lập hoặc FK lỏng lẻo trong dump này)
        $pdo->exec("CREATE TABLE orders (
            id SERIAL PRIMARY KEY,
            user_id INT DEFAULT NULL,
            customer_name VARCHAR(100) NOT NULL,
            customer_email VARCHAR(100) NOT NULL,
            customer_phone VARCHAR(20) NOT NULL,
            customer_address TEXT NOT NULL,
            total_amount DECIMAL(10,2) NOT NULL,
            status VARCHAR(20) DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // 13. Table: order_detail (FK tới "order", book)
        $pdo->exec("CREATE TABLE order_detail (
            order_id VARCHAR(100) NOT NULL,
            book_id VARCHAR(15) NOT NULL,
            quantity INT NOT NULL DEFAULT 0,
            price DECIMAL(10,2) NOT NULL DEFAULT 0,
            PRIMARY KEY (order_id, book_id),
            CONSTRAINT fk_od_order FOREIGN KEY (order_id) REFERENCES \"order\" (order_id) ON DELETE CASCADE,
            CONSTRAINT fk_od_book FOREIGN KEY (book_id) REFERENCES book (book_id) ON DELETE CASCADE
        )");

        // 14. Table: order_items (FK logic tới orders, products)
        $pdo->exec("CREATE TABLE order_items (
            id SERIAL PRIMARY KEY,
            order_id INT NOT NULL,
            product_id INT NOT NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL
        )");


        // =================================================================================
        // PHẦN 3: INSERT DATA (ĐẦY ĐỦ TỪ DUMP - KHÔNG RÚT GỌN)
        // =================================================================================
        $inserted_tables = [];

        // --- NHÓM 1: Dữ liệu nền (Không khóa ngoại) ---

        // 1. Admin
        $pdo->exec("INSERT INTO admin (username, password, name, email, phone) VALUES
            ('abcd', '81dc9bdb52d04dc20036dbd8313ed055', 'Nguyễn văn A', NULL, NULL),
            ('hung', 'e10adc3949ba59abbe56e057f20f883e', 'Lên Văn An', NULL, NULL),
            ('admin', '21232f297a57a5a743894a0e4a801fc3', 'Trần Văn Hùng', NULL, NULL)");
        $inserted_tables[] = 'admin';

        // 2. Category
        $pdo->exec("INSERT INTO category (cat_id, cat_name) VALUES
            ('gk', 'Giáo Khoa'), ('khkt', 'Ky Thuat'), ('kt', 'Kinh Tế'),
            ('Ls', 'Lịch sử '), ('LS1', 'Lịch sử'), ('nn', 'Ngoại Ngữ'),
            ('pl', 'Pháp Luật'), ('td', 'Từ Điển'), ('test', 'Loai Moi'),
            ('th', 'Tin Học'), ('tt', 'The Thao Du Lich'), ('vh', 'Văn Học'),
            ('vhxh', 'Van Hoa xa Hoi')");
        $inserted_tables[] = 'category';

        // 3. Publisher
        $pdo->exec("INSERT INTO publisher (pub_id, pub_name) VALUES
            ('gd', 'Giáo dục'), ('hcm', 'Tổng Hợp Hồ Chí Minh'),
            ('hnv', 'Hội Nhà Văn'), ('pn', 'Phụ Nữ'), ('tn', 'Thanh Niên'),
            ('vh', 'Văn Học'), ('vhtt', 'Văn Hóa Thông Tin')");
        $inserted_tables[] = 'publisher';

        // 4. Users
        $pdo->exec("INSERT INTO users (email, password, name, address, phone) VALUES
            ('abcd@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Minh Triết', 'Q1', '99999999'),
            ('hung.stu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Đại Ca - Trần văn Hùng', 'Quận 3', '090090999')");
        $inserted_tables[] = 'users';

        // 5. Userss
        $pdo->exec("INSERT INTO userss (id, username, password, email, full_name, role, created_at) VALUES
            (1, 'admin', '1234', 'admin1@guitarshop.com', 'Administratorr', 'admin', '2025-12-02 18:36:45')");
        // Reset sequence userss_id_seq sau khi insert cứng ID
        $pdo->exec("SELECT setval('userss_id_seq', (SELECT MAX(id) FROM userss))");
        $inserted_tables[] = 'userss';

        // 6. News
        $pdo->exec("INSERT INTO news (id, title, img, short_content, content, hot) VALUES
            (1, 'qqq', 'q', 'q', 'q', 0),
            (2, 'ww', 'w', 'w', 'w', 1),
            (3, 'ee', 'e', 'e', 'e', 1)");
        $pdo->exec("SELECT setval('news_id_seq', (SELECT MAX(id) FROM news))");
        $inserted_tables[] = 'news';

        // 7. Banners
        $pdo->exec("INSERT INTO banners (id, image, title, subtitle, link, display_order, created_at) VALUES
            (1, 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1600&q=80', 'Tuấnnn', 'Khám phá bộ sưu tập guitar đẳng cấp thế giới.', 'shop.php', 1, '2025-12-03 02:14:15'),
            (2, 'https://cdn.pixabay.com/photo/2015/05/07/11/02/guitar-756326_1280.jpg', 'Fender Stratocaster', 'Huyền thoại trở lại với thiết kế mới.', 'shop.php?category=Electric', 2, '2025-12-03 02:14:15'),
            (3, 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=1600&q=80', 'Acoustic Soul', 'Âm thanh mộc mạc, cảm xúc thăng hoa.', 'shop.php?category=Acoustic', 3, '2025-12-03 02:14:15'),
            (4, 'https://cdn.pixabay.com/photo/2020/04/14/17/44/guitar-5043613_1280.jpg', 'Hiiiiiii', 'Khám phá bộ sưu tập guitar đẳng cấp thế giới.', '', 4, '2025-12-03 02:29:20'),
            (5, 'https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', 'Chó Thịnh', 'Thịnh chó điên', 'https://www.facebook.com/', 5, '2025-12-03 08:12:40')");
        $pdo->exec("SELECT setval('banners_id_seq', (SELECT MAX(id) FROM banners))");
        $inserted_tables[] = 'banners';

        // 8. Blog Posts
        $pdo->exec("INSERT INTO blog_posts (id, title, content, image, created_at) VALUES
            (1, 'Cách chọn đàn guitar cho người mới bắt đầu', 'Việc chọn cây đàn guitar đầu tiên rất quan trọng. Bạn nên cân nhắc giữa guitar acoustic (dây sắt) và classic (dây nylon)...', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (2, '5 Lỗi thường gặp khi tự học guitar', 'Tự học guitar là một hành trình thú vị nhưng cũng đầy thử thách...', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (3, 'Bảo quản đàn guitar trong mùa nồm ẩm', 'Độ ẩm là kẻ thù số một của đàn guitar gỗ...', 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (4, 'Lịch sử phát triển của Fender Stratocaster', 'Fender Stratocaster, ra đời năm 1954...', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (5, 'Review Boss Katana-50 MkII: Ampli tốt nhất tầm giá?', 'Boss Katana-50 MkII đang làm mưa làm gió...', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (6, 'Top 5 bài hát guitar acoustic dễ tập cho người mới', 'Bạn mới tập chơi guitar và muốn tìm những bài hát đơn giản...', 'https://images.unsplash.com/photo-1462965326201-d02e4f455804?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (7, 'Phân biệt các loại gỗ làm đàn guitar', 'Gỗ làm đàn ảnh hưởng rất lớn đến âm thanh...', 'https://images.unsplash.com/photo-1542300058-b94b8ab7411b?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
            (8, 'Hướng dẫn thay dây đàn guitar đúng cách', 'Thay dây đàn định kỳ là việc làm cần thiết...', 'https://cdn.pixabay.com/photo/2016/11/23/15/48/guitar-1853661_1280.jpg', '2025-12-03 01:45:18')");
        $pdo->exec("SELECT setval('blog_posts_id_seq', (SELECT MAX(id) FROM blog_posts))");
        $inserted_tables[] = 'blog_posts';

        // 9. Products (Dữ liệu guitar từ dump mới)
        $pdo->exec("INSERT INTO products (id, name, price, description, image, created_at, category) VALUES
            (1, 'Fender Stratocaster Player Series', 18500000.00, 'Đàn guitar điện Fender Player Stratocaster...', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
            (3, 'Ibanez RG550 Genesis', 22000000.00, 'Ibanez RG550 là biểu tượng...', 'https://images.unsplash.com/photo-1605020420620-20c943cc4669?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
            (5, 'Fender Telecaster American Pro II', 38000000.00, 'Fender American Professional II Telecaster...', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
            (8, 'Squier Classic Vibe 50s Strat', 10500000.00, 'Squier Classic Vibe 50s Stratocaster...', 'https://images.unsplash.com/photo-1519508234439-4f23643125c1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
            (9, 'Taylor 214ce Acoustic-Electric', 28000000.00, 'Taylor 214ce là cây đàn acoustic-electric...', 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
            (10, 'Martin D-28 Standard', 75000000.00, 'Martin D-28 là tiêu chuẩn vàng...', 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
            (12, 'Takamine GD20-NS', 6500000.00, 'Takamine GD20-NS là cây đàn dreadnought...', 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
            (13, 'Fender CD-60S All-Mahogany', 5200000.00, 'Fender CD-60S All-Mahogany mang lại âm thanh...', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
            (16, 'Ibanez SR300E Bass', 9500000.00, 'Ibanez SR300E là cây bass hiện đại...', 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Bass'),
            (18, 'Boss Katana-50 MkII', 6500000.00, 'Boss Katana-50 MkII là ampli guitar đa năng...', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
            (19, 'Fender Mustang LT25', 4200000.00, 'Fender Mustang LT25 là ampli tập luyện...', 'https://images.unsplash.com/photo-1593104547489-5cfb3839a3b5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
            (20, 'Marshall DSL40CR', 18500000.00, 'Marshall DSL40CR là ampli đèn 40W...', 'https://images.unsplash.com/photo-1551712744-1963ba88d303?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
            (22, 'Dunlop Cry Baby Wah', 2800000.00, 'Dunlop Cry Baby GCB95 là pedal wah...', 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
            (23, 'Capo Kyser Quick-Change', 450000.00, 'Capo Kyser Quick-Change bền bỉ...', 'https://images.unsplash.com/photo-1588449668365-d15e397f6787?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Accessories'),
            (24, 'Thịnh Thịnh', 13432.00, 'Guitar điênn', 'https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', '2025-12-03 08:15:23', 'Electric')");
        $pdo->exec("SELECT setval('products_id_seq', (SELECT MAX(id) FROM products))");
        $inserted_tables[] = 'products';

        // --- NHÓM 2: Dữ liệu phụ thuộc (Cần nhóm 1 trước) ---

        // 10. Book (Phụ thuộc Category, Publisher)
        // Data này từ dump, rất dài nên cần escape cẩn thận
        $pdo->exec("INSERT INTO book (book_id, book_name, description, price, img, pub_id, cat_id) VALUES
            ('td01', 'Từ Điển mẫu câu tiếng Nhật', 'Tập hợp tất cả các mẫu câu tiếng Nhật. Phong phú, đầu đủ nhất. ', 450000, 'td01.jpg', 'gd', 'td'),
            ('td02', 'Từ Điển Kinh Doanh Và Tiếp Thị Hiện Đại', 'Quyển sách Từ điển Kinh doanh – Tiếp thị Hiện đại (Modern Business & Marketing Dictionary)...', 195000, 'td02.gif', 'vhtt', 'td'),
            ('td03', 'Đại Từ Điển Tiếng Việt (Bản mới 2010)', 'Thêm yêu tiếng Việt...', 450000, 'td03.jpg', 'hcm', 'td'),
            ('td04', 'từ điển y học sức khỏe bệnh lý Anh Anh Việt', 'Từ điển y học...', 380000, 'td04.jpg', 'tn', 'td'),
            ('td05', 'Từ Điển Anh Việt - 75000 Từ', 'Từ điển mới ...', 50000, 'td05.jpg', 'hcm', 'td'),
            ('td06', 'Từ điển địa danh hành chính Nam Bộ', 'Từ điển địa danh...', 265000, 'td06.jpg', 'hcm', 'td'),
            ('th01', '100 thủ thuật với Excel 2010', '100 thủ thuật...', 54000, 'th01.gif', 'hcm', 'th'),
            ('th02', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu', 'Tiếp theo tập 1...', 76000, 'th02.jpg', 'hcm', 'th'),
            ('th03', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1 (Tập1)', 'Tập 1 của cuốn sách...', 76000, 'th03.jpg', 'hcm', 'th'),
            ('th04', 'Làm Quen Với Internet', 'Ngày nay với sự phát triển...', 31000, 'th04.jpg', 'hcm', 'th'),
            ('th05', 'Từng Bước Làm Quen Với Máy Tính', 'Mục Lục: Bài 1...', 31000, 'th05.jpg', 'vhtt', 'th'),
            ('th06', 'Quản Trị Windows Server 2008 - Tập 2', 'Kế thừa những ưu điểm...', 62000, 'th06.jpg', 'hcm', 'th'),
            ('th07', 'Kỹ Thuật Lập Trình C - Cơ Sở Và Nâng Cao', 'Cuốn sách này gồm...', 72000, 'th07.jpg', 'tn', 'th'),
            ('th08', 'Giáo Trình Học Nhanh SQL Server 2008 - Tập 2', 'Bộ sách Giáo trình...', 81000, 'th08.jpg', 'hcm', 'th'),
            ('th09', '160 Vấn Đề Cần Nên Biết Khi Sử Dụng Đồ Họa Máy Vi Tính', '160 Vấn Đề...', 85000, 'th09.jpg', 'tn', 'th'),
            ('th10', 'Giáo Trình Học Nhanh SQL Server 2008 - Tập 1', 'Bộ sách...', 69000, 'th10.jpg', 'tn', 'th'),
            ('th11', 'Microsoft Word 2007 - Căn Bản Và Thủ Thuật', 'Microsoft Word 2007...', 69000, 'th11.jpg', 'gd', 'th'),
            ('th12', 'Kế Toán Doanh Nghiệp Với ACCESS', 'Sách mới...', 76000, 'th12.jpg', 'gd', 'th'),
            ('th13', 'Giáo Trình C++ & Lập Trình Hướng Đối Tượng', 'Cuốn sách gồm 12 chương...', 78000, 'th13.gif', 'gd', 'th'),
            ('th14', 'Các Thủ Thuật Trong HTML Và Thiết Kế Web', 'Cuốn sách này sẽ cung cấp...', 89000, 'th14.jpg', 'gd', 'th'),
            ('th15', 'Tạo Website Hấp Dẫn Với HTML, XHTML Và CSS', 'Ngày nay, việc ứng dụng...', 79000, 'th15.jpg', 'gd', 'th'),
            ('th16', 'Tuyển Tập Thủ Thuật Javascript - Tập 1', 'Tuyển Tập Thủ Thuật...', 66000, 'th16.jpg', 'gd', 'th'),
            ('th17', 'Thiết Kế Web Với CSS', 'Từ khi được giới thiệu...', 82000, 'th17.jpg', 'gd', 'th'),
            ('th18', 'Thiết Kế Web Với JavaScript Và Dom', 'Nội dung cuốn sách...', 79000, 'th18.jpg', 'gd', 'th'),
            ('th88', 'sach giao khoa lop 10', '?', 30000, 'th18.jpg', 'gd', 'gk')");
        $inserted_tables[] = 'book';

        // 11. "order" (Phụ thuộc Users)
        // Dump không có data cho bảng này, thêm mẫu để không bị lỗi logic
        if ($pdo->query("SELECT COUNT(*) FROM \"order\"")->fetchColumn() == 0) {
            $pdo->exec("INSERT INTO \"order\" (order_id, email, order_date, consignee_name, consignee_add, consignee_phone, status) VALUES
            ('ORD001', 'abcd@yahoo.com', NOW(), 'Nguyễn Minh Triết', 'Q1, HCM', '99999999', 0)");
        }
        $inserted_tables[] = 'order (sample)';

        // 12. orders (Bảng mới - có data trong dump)
        $pdo->exec("INSERT INTO orders (id, user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, created_at) VALUES
            (1, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 2500000.00, 'cancelled', '2025-12-03 01:59:15'),
            (2, 1, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 65000000.00, 'processing', '2025-12-03 02:35:13'),
            (3, 1, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 22000000.00, 'completed', '2025-12-03 02:59:59'),
            (4, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 6500000.00, 'completed', '2025-12-04 03:42:30'),
            (5, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '1111', 18500000.00, 'pending', '2025-12-08 08:38:08'),
            (6, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '2222', 22000000.00, 'pending', '2025-12-08 08:54:22'),
            (7, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '333', 18500000.00, 'pending', '2025-12-08 09:30:20'),
            (8, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 99999999.99, 'pending', '2025-12-09 03:10:31'),
            (9, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '222', 22000000.00, 'processing', '2025-12-09 03:49:22')");
        $pdo->exec("SELECT setval('orders_id_seq', (SELECT MAX(id) FROM orders))");
        $inserted_tables[] = 'orders';

        // 13. order_items (Phụ thuộc Orders, Products)
        $pdo->exec("INSERT INTO order_items (id, order_id, product_id, quantity, price) VALUES
            (1, 1, 11, 1, 2500000.00),
            (2, 2, 2, 1, 65000000.00),
            (3, 3, 3, 1, 22000000.00),
            (4, 4, 18, 1, 6500000.00),
            (5, 5, 1, 1, 18500000.00),
            (6, 6, 3, 1, 22000000.00),
            (7, 7, 1, 1, 18500000.00),
            (8, 8, 3, 1, 22000000.00),
            (9, 8, 1, 1, 18500000.00),
            (10, 8, 5, 1, 38000000.00),
            (11, 8, 9, 1, 28000000.00),
            (12, 8, 10, 1, 75000000.00),
            (13, 9, 3, 1, 22000000.00)");
        $pdo->exec("SELECT setval('order_items_id_seq', (SELECT MAX(id) FROM order_items))");
        $inserted_tables[] = 'order_items';

        // 14. order_detail (Phụ thuộc "order", Book)
        // Dump không có data, thêm mẫu cho khớp với mẫu "order"
        $pdo->exec("INSERT INTO order_detail (order_id, book_id, quantity, price) VALUES
            ('ORD001', 'td01', 1, 450000)");
        $inserted_tables[] = 'order_detail (sample)';

        $message = "Setup thành công trên PostgreSQL! 14 Tables đã được tạo và nạp dữ liệu đầy đủ.<br>Tables: " . implode(', ', $inserted_tables);

    } catch (PDOException $e) {
        $error = "Lỗi setup: " . $e->getMessage();
    }
endif;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup DB - Guitar Shop (PostgreSQL Full Data)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Setup Database Guitar Shop (PostgreSQL - Full Data)</h2>
    <p>Script này tạo đầy đủ 14 bảng và insert toàn bộ dữ liệu từ file dump MySQL gốc (đã chuyển đổi sang Postgres).</p>
    
    <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <form method="post">
        <input type="hidden" name="setup" value="1">
        <button type="submit" class="btn btn-primary btn-lg">Chạy Setup (Full Database)</button>
    </form>
    
    <div class="mt-4">
        <h4>Lưu ý quan trọng:</h4>
        <ul>
            <li>Hệ thống sử dụng bảng <code>"order"</code> (có dấu ngoặc kép) để tránh lỗi từ khóa của Postgres.</li>
            <li>Đã tự động reset sequence ID cho các bảng `SERIAL`.</li>
            <li>Dữ liệu bao gồm đầy đủ sách, sản phẩm guitar, banners, users, và tin tức.</li>
        </ul>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>