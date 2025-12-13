<?php
include 'config/database.php';  // Kết nối DB

$message = ''; $error = '';
if ($_POST && isset($_POST['setup'])):  // Submit form để chạy setup
    try {

        // 1. Tắt kiểm tra FK để drop tables an toàn
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        // 2. DROP tất cả tables cũ (14 tables)
        $tables_to_drop = [
            'order_detail', 'order_items', 'book', 'order', 'orders', 
            'users', 'userss', 'category', 'publisher', 'products', 
            'admin', 'banners', 'blog_posts', 'news'
        ];
        foreach ($tables_to_drop as $table) {
            $pdo->exec("DROP TABLE IF EXISTS `$table`");
        }

        // 3. Bật lại kiểm tra FK
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

        // --- KHU VỰC TẠO TABLES (STRUCTURE) ---

        // Table: category
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS category (
                cat_id VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                cat_name VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                PRIMARY KEY (cat_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Table: publisher
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS publisher (
                pub_id VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                pub_name VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                PRIMARY KEY (pub_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Table: users (Cần tạo sớm để order tham chiếu)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                email VARCHAR(50) NOT NULL DEFAULT '',
                password VARCHAR(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                name VARCHAR(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                address VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                phone VARCHAR(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
                PRIMARY KEY (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Table: book (Phụ thuộc category, publisher)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS book (
                book_id VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                book_name VARCHAR(250) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                description TEXT CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                price INT NOT NULL,
                img VARCHAR(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                pub_id VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                cat_id VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                PRIMARY KEY (book_id),
                KEY manxb (pub_id, cat_id),
                KEY maloai (cat_id),
                CONSTRAINT book_ibfk_1 FOREIGN KEY (pub_id) REFERENCES publisher (pub_id) ON UPDATE CASCADE,
                CONSTRAINT book_ibfk_2 FOREIGN KEY (cat_id) REFERENCES category (cat_id) ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Table: order (Phụ thuộc users)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `order` (
                order_id VARCHAR(100) NOT NULL,
                email VARCHAR(50) NOT NULL DEFAULT '',
                order_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                consignee_name VARCHAR(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                consignee_add VARCHAR(80) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                consignee_phone VARCHAR(12) NOT NULL DEFAULT '',
                status INT NOT NULL DEFAULT 0 COMMENT 'Trạng thái:0-3',
                PRIMARY KEY (order_id),
                KEY email (email),
                CONSTRAINT order_ibfk_1 FOREIGN KEY (email) REFERENCES users (email) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Table: order_detail (Phụ thuộc order, book)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS order_detail (
                order_id VARCHAR(100) NOT NULL,
                book_id VARCHAR(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                quantity TINYINT UNSIGNED NOT NULL DEFAULT 0,
                price FLOAT NOT NULL DEFAULT 0,
                PRIMARY KEY (order_id, book_id),
                KEY masach (book_id),
                CONSTRAINT order_detail_ibfk_1 FOREIGN KEY (order_id) REFERENCES `order` (order_id) ON DELETE CASCADE ON UPDATE CASCADE,
                CONSTRAINT order_detail_ibfk_2 FOREIGN KEY (book_id) REFERENCES book (book_id) ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Các tables độc lập khác (MyISAM hoặc không có ràng buộc chặt chẽ lúc tạo)
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin (username VARCHAR(30) NOT NULL, password VARCHAR(32) DEFAULT NULL, name VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL, email VARCHAR(60) DEFAULT NULL, phone VARCHAR(12) DEFAULT NULL, PRIMARY KEY (username)) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS banners (id INT NOT NULL AUTO_INCREMENT, image VARCHAR(255) NOT NULL, title VARCHAR(255) DEFAULT NULL, subtitle VARCHAR(255) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, display_order INT DEFAULT 0, created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=6;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS blog_posts (id INT NOT NULL AUTO_INCREMENT, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=9;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS news (id INT NOT NULL AUTO_INCREMENT, title VARCHAR(255) NOT NULL, content TEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=10;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS orders (id INT NOT NULL AUTO_INCREMENT, user_id INT DEFAULT NULL, customer_name VARCHAR(100) NOT NULL, customer_email VARCHAR(100) NOT NULL, customer_phone VARCHAR(20) NOT NULL, customer_address TEXT NOT NULL, total_amount DECIMAL(10,2) NOT NULL, status VARCHAR(20) DEFAULT 'pending', created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id), KEY user_id (user_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=10;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (id INT NOT NULL AUTO_INCREMENT, order_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, price DECIMAL(10,2) NOT NULL, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id), KEY order_id (order_id), KEY product_id (product_id)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=14;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS products (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255) NOT NULL, price DECIMAL(10,2) NOT NULL, description TEXT, image VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, category VARCHAR(50) DEFAULT 'Guitar', PRIMARY KEY (id)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=25;");
        
        $pdo->exec("CREATE TABLE IF NOT EXISTS userss (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT, username VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(100) NOT NULL, full_name VARCHAR(100) DEFAULT NULL, role VARCHAR(20) DEFAULT 'customer', created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (id), UNIQUE KEY username (username), UNIQUE KEY email (email)) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=2;");


        // --- KHU VỰC INSERT DATA (ĐÃ SẮP XẾP LẠI THỨ TỰ LOGIC) ---
        $inserted = [];

        // 1. Users (Ưu tiên SỐ 1 - Phải có user mới có order)
        $check = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO users (email, password, name, address, phone) VALUES
                ('abcd@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Minh Triết', 'Q1', '99999999'),
                ('hung.stu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Đại Ca - Trần văn Hùng', 'Quận 3', '090090999');
            ");
            $inserted[] = 'users (2 records)';
        }

        // 2. Userss (Độc lập)
        $check = $pdo->query("SELECT COUNT(*) FROM userss")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO userss (id, username, password, email, full_name, role, created_at) VALUES
                (1, 'admin', '1234', 'admin1@guitarshop.com', 'Administratorr', 'admin', '2025-12-02 18:36:45');
            ");
            $inserted[] = 'userss (1 record)';
        }

        // 3. Category (Ưu tiên SỐ 2 - Cho Book)
        $check = $pdo->query("SELECT COUNT(*) FROM category")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO category (cat_id, cat_name) VALUES
                ('td', 'Từ điển'),
                ('th', 'Thủ thuật');
            ");
            $inserted[] = 'category (2 records)';
        }

        // 4. Publisher (Ưu tiên SỐ 2 - Cho Book)
        $check = $pdo->query("SELECT COUNT(*) FROM publisher")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO publisher (pub_id, pub_name) VALUES
                ('gd', 'Giáo dục'),
                ('hcm', 'Tổng Hợp Hồ Chí Minh'),
                ('hnv', 'Hội Nhà Văn'),
                ('pn', 'Phụ Nữ'),
                ('tn', 'Thanh Niên'),
                ('vh', 'Văn Học'),
                ('vhtt', 'Văn Hóa Thông Tin');
            ");
            $inserted[] = 'publisher (7 records)';
        }

        // 5. Admin (Độc lập)
        $check = $pdo->query("SELECT COUNT(*) FROM admin")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO admin (username, password, name, email, phone) VALUES
                ('abcd', '81dc9bdb52d04dc20036dbd8313ed055', 'Nguyễn văn A', NULL, NULL),
                ('hung', 'e10adc3949ba59abbe56e057f20f883e', 'Lên Văn An', NULL, NULL),
                ('admin', '21232f297a57a5a743894a0e4a801fc3', 'Trần Văn Hùng', NULL, NULL);
            ");
            $inserted[] = 'admin (3 records)';
        }

        // 6. Products (Độc lập)
        $check = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO products (id, name, price, description, image, created_at, category) VALUES
                (1, 'Fender Stratocaster Player Series', 18500000.00, 'Đàn guitar điện Fender Player Stratocaster mang đến âm thanh và cảm giác đích thực của Fender.', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (3, 'Ibanez RG550 Genesis', 22000000.00, 'Ibanez RG550 là biểu tượng của dòng đàn shred guitar.', 'https://images.unsplash.com/photo-1605020420620-20c943cc4669?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (5, 'Fender Telecaster American Pro II', 38000000.00, 'Fender American Professional II Telecaster mang đến sự cải tiến cho thiết kế cổ điển.', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (8, 'Squier Classic Vibe 50s Strat', 10500000.00, 'Squier Classic Vibe 50s Stratocaster tái hiện lại sự ra đời của Stratocaster vào những năm 1950.', 'https://images.unsplash.com/photo-1519508234439-4f23643125c1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (9, 'Taylor 214ce Acoustic-Electric', 28000000.00, 'Taylor 214ce là cây đàn acoustic-electric dáng Grand Auditorium linh hoạt.', 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
                (10, 'Martin D-28 Standard', 75000000.00, 'Martin D-28 là tiêu chuẩn vàng của đàn acoustic guitar.', 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
                (12, 'Takamine GD20-NS', 6500000.00, 'Takamine GD20-NS là cây đàn dreadnought với mặt đàn Cedar nguyên tấm.', 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
                (13, 'Fender CD-60S All-Mahogany', 5200000.00, 'Fender CD-60S All-Mahogany mang lại âm thanh êm dịu và ấm áp.', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (16, 'Ibanez SR300E Bass', 9500000.00, 'Ibanez SR300E là cây bass hiện đại với cần đàn mỏng, nhẹ và hệ thống pickup PowerSpan linh hoạt.', 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Bass'),
                (18, 'Boss Katana-50 MkII', 6500000.00, 'Boss Katana-50 MkII là ampli guitar đa năng với 5 kiểu amp độc đáo và hơn 60 hiệu ứng Boss tích hợp.', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (19, 'Fender Mustang LT25', 4200000.00, 'Fender Mustang LT25 là ampli tập luyện hoàn hảo với giao diện dễ sử dụng.', 'https://images.unsplash.com/photo-1593104547489-5cfb3839a3b5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (20, 'Marshall DSL40CR', 18500000.00, 'Marshall DSL40CR là ampli đèn 40W mang đến âm thanh Marshall huyền thoại.', 'https://images.unsplash.com/photo-1551712744-1963ba88d303?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (22, 'Dunlop Cry Baby Wah', 2800000.00, 'Dunlop Cry Baby GCB95 là pedal wah kinh điển nhất mọi thời đại.', 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (23, 'Capo Kyser Quick-Change', 450000.00, 'Capo Kyser Quick-Change bền bỉ, dễ sử dụng và giữ dây chắc chắn.', 'https://images.unsplash.com/photo-1588449668365-d15e397f6787?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Accessories'),
                (24, 'Thịnh Thịnh', 13432.00, 'Guitar điênn', 'https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', '2025-12-03 08:15:23', 'Electric');
            ");
            $inserted[] = 'products (15 records)';
        }

        // 7. Banners & Blog Posts & News (Độc lập)
        $check = $pdo->query("SELECT COUNT(*) FROM banners")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO banners (id, image, title, subtitle, link, display_order, created_at) VALUES
                (1, 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1600&q=80', 'Tuấnnn', 'Khám phá bộ sưu tập guitar đẳng cấp thế giới.', 'shop.php', 1, '2025-12-03 02:14:15'),
                (2, 'https://cdn.pixabay.com/photo/2015/05/07/11/02/guitar-756326_1280.jpg', 'Fender Stratocaster', 'Huyền thoại trở lại với thiết kế mới.', 'shop.php?category=Electric', 2, '2025-12-03 02:14:15'),
                (3, 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=1600&q=80', 'Acoustic Soul', 'Âm thanh mộc mạc, cảm xúc thăng hoa.', 'shop.php?category=Acoustic', 3, '2025-12-03 02:14:15'),
                (4, 'https://cdn.pixabay.com/photo/2020/04/14/17/44/guitar-5043613_1280.jpg', 'Hiiiiiii', 'Khám phá bộ sưu tập guitar đẳng cấp thế giới.', '', 4, '2025-12-03 02:29:20'),
                (5, 'https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', 'Chó Thịnh', 'Thịnh chó điên', 'https://www.facebook.com/', 5, '2025-12-03 08:12:40');
            ");
            $inserted[] = 'banners (5 records)';
        }

        $check = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO blog_posts (id, title, content, image, created_at) VALUES
                (1, 'Cách chọn đàn guitar cho người mới bắt đầu', 'Việc chọn cây đàn guitar đầu tiên rất quan trọng. Bạn nên cân nhắc giữa guitar acoustic (dây sắt) và classic (dây nylon)...', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (2, '5 Lỗi thường gặp khi tự học guitar', 'Tự học guitar là một hành trình thú vị nhưng cũng đầy thử thách...', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (3, 'Bảo quản đàn guitar trong mùa nồm ẩm', 'Độ ẩm là kẻ thù số một của đàn guitar gỗ...', 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (4, 'Lịch sử phát triển của Fender Stratocaster', 'Fender Stratocaster, ra đời năm 1954, đã thay đổi bộ mặt âm nhạc thế giới...', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (5, 'Review Boss Katana-50 MkII: Ampli tốt nhất tầm giá?', 'Boss Katana-50 MkII đang làm mưa làm gió trên thị trường ampli tập luyện...', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (6, 'Top 5 bài hát guitar acoustic dễ tập cho người mới', 'Bạn mới tập chơi guitar và muốn tìm những bài hát đơn giản để luyện tập...', 'https://images.unsplash.com/photo-1462965326201-d02e4f455804?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (7, 'Phân biệt các loại gỗ làm đàn guitar', 'Gỗ làm đàn ảnh hưởng rất lớn đến âm thanh...', 'https://images.unsplash.com/photo-1542300058-b94b8ab7411b?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (8, 'Hướng dẫn thay dây đàn guitar đúng cách', 'Thay dây đàn định kỳ là việc làm cần thiết để giữ cho cây đàn luôn có âm thanh tốt nhất...', 'https://cdn.pixabay.com/photo/2016/11/23/15/48/guitar-1853661_1280.jpg', '2025-12-03 01:45:18');
            ");
            $inserted[] = 'blog_posts (8 records)';
        }

        $check = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO news (id, title, content, image, created_at) VALUES
                (1, 'Tin tức mới: Fender ra mắt Stratocaster 2025', 'Fender vừa công bố mẫu Stratocaster mới...', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?auto=format&fit=crop&w=800&q=80', '2025-12-13 10:00:00'),
                (2, 'Khuyến mãi ampli Boss Katana', 'Giảm 20% ampli Boss Katana-50 MkII trong tuần này...', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-13 11:00:00'),
                (3, 'Hướng dẫn bảo dưỡng guitar mùa đông', 'Mùa đông lạnh giá có thể làm khô cần đàn...', 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?auto=format&fit=crop&w=800&q=80', '2025-12-13 12:00:00'),
                (4, 'Review Taylor 214ce: Đàn acoustic đỉnh cao', 'Taylor 214ce với âm thanh cân bằng, pickup ES2...', 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?auto=format&fit=crop&w=800&q=80', '2025-12-13 13:00:00'),
                (5, 'Top 10 pedal effect phải có', 'Từ wah đến delay, đây là những pedal thiết yếu...', 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?auto=format&fit=crop&w=800&q=80', '2025-12-13 14:00:00'),
                (6, 'Cập nhật: Martin D-28 restock', 'Martin D-28 đã về hàng, chỉ còn 5 cây...', 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8?auto=format&fit=crop&w=800&q=80', '2025-12-13 15:00:00'),
                (7, 'Học guitar online miễn phí', 'Khóa học cơ bản trên YouTube...', 'https://images.unsplash.com/photo-1462965326201-d02e4f455804?auto=format&fit=crop&w=800&q=80', '2025-12-13 16:00:00'),
                (8, 'Tin nóng: Gibson Les Paul giảm giá', 'Giảm sốc 15% Gibson Les Paul Standard...', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', '2025-12-13 17:00:00'),
                (9, 'Sự kiện workshop guitar miễn phí', 'Ngày 20/12: Học chơi acoustic với nghệ sĩ nổi tiếng...', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-13 18:00:00'),
                (10, 'Mẹo chọn dây đàn Elixir', 'Elixir Nanoweb: Độ bền cao, âm thanh sáng lâu...', 'https://images.unsplash.com/photo-1516280440614-6697288d5d38?auto=format&fit=crop&w=800&q=80', '2025-12-13 19:00:00');
            ");
            $inserted[] = 'news (10 records)';
        }

        // 8. Book (Có FK đến Category và Publisher -> Chạy sau 3, 4)
        $check = $pdo->query("SELECT COUNT(*) FROM book")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO book (book_id, book_name, description, price, img, pub_id, cat_id) VALUES
                ('td01', 'Từ Điển mẫu câu tiếng Nhật', 'Tập hợp tất cả các mẫu câu tiếng Nhật. Phong phú, đầu đủ nhất. ', 450000, 'td01.jpg', 'gd', 'td'),
                ('td02', 'Từ Điển Kinh Doanh Và Tiếp Thị Hiện Đại', 'Quyển sách “Từ điển Kinh doanh – Tiếp thị Hiện đại”...', 195000, 'td02.gif', 'vhtt', 'td'),
                ('td03', 'Đại Từ Điển Tiếng Việt (Bản mới 2010)', 'Thêm yêu tiếng Việt...', 450000, 'td03.jpg', 'hcm', 'td'),
                ('td04', 'từ điển y học sức khỏe bệnh lý Anh Anh Việt', 'Từ điển y học - sức khỏe bệnh lý...', 380000, 'td04.jpg', 'tn', 'td'),
                ('td05', 'Từ Điển Anh Việt - 75000 Từ', 'Từ điển mới ...', 50000, 'td05.jpg', 'hcm', 'td'),
                ('td06', 'Từ điển địa danh hành chính Nam Bộ', 'Từ điển địa danh hành chính Nam Bộ...', 265000, 'td06.jpg', 'hcm', 'td'),
                ('th01', '100 thủ thuật với Excel 2010', '100 thủ thuật ứng với 100 bài tập thực hành...', 54000, 'th01.gif', 'hcm', 'th'),
                ('th02', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu', 'Tiếp theo tập 1, tập 2...', 76000, 'th02.jpg', 'hcm', 'th'),
                ('th03', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1 (Tập1)', 'Tập 1 của cuốn sách...', 76000, 'th03.jpg', 'hcm', 'th'),
                ('th04', 'Làm Quen Với Internet', 'Ngày nay với sự phát triển không ngừng...', 31000, 'th04.jpg', 'hcm', 'th'),
                ('th05', 'Từng Bước Làm Quen Với Máy Tính', 'Mục Lục: Bài 1...', 31000, 'th05.jpg', 'vhtt', 'th'),
                ('th06', 'Quản Trị Windows Server 2008 - Tập 2', 'Kế thừa những ưu điểm vượt trội...', 0, 'th06.jpg', 'hcm', 'th');
            ");
            $inserted[] = 'book (11 records)';
        }

        // 9. Order (Có FK đến Users -> Chạy sau 1)
        $check = $pdo->query("SELECT COUNT(*) FROM `order`")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO `order` (order_id, email, order_date, consignee_name, consignee_add, consignee_phone, status) VALUES
                ('ORD001', 'abcd@yahoo.com', '2025-12-13 10:00:00', 'Nguyễn Minh Triết', 'Q1, HCM', '99999999', 0),
                ('ORD002', 'hung.stu@gmail.com', '2025-12-13 11:00:00', 'Trần Văn Hùng', 'Quận 3, HCM', '090090999', 1);
            ");
            $inserted[] = 'order (2 records)';
        }

        // 10. Orders (Table mới)
        $check = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO orders (id, user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, created_at) VALUES
                (1, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 2500000.00, 'cancelled', '2025-12-03 01:59:15'),
                (2, 1, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 65000000.00, 'processing', '2025-12-03 02:35:13'),
                (3, 1, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 22000000.00, 'completed', '2025-12-03 02:59:59'),
                (4, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 6500000.00, 'completed', '2025-12-04 03:42:30'),
                (5, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '1111', 18500000.00, 'pending', '2025-12-08 08:38:08'),
                (6, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '2222', 22000000.00, 'pending', '2025-12-08 08:54:22'),
                (7, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '333', 18500000.00, 'pending', '2025-12-08 09:30:20'),
                (8, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 99999999.99, 'pending', '2025-12-09 03:10:31'),
                (9, NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', '222', 22000000.00, 'processing', '2025-12-09 03:49:22');
            ");
            $inserted[] = 'orders (9 records)';
        }

        // 11. Order Detail (Có FK đến Order và Book -> Chạy sau 8 và 9)
        $check = $pdo->query("SELECT COUNT(*) FROM order_detail")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO order_detail (order_id, book_id, quantity, price) VALUES
                ('ORD001', 'td01', 1, 450000),
                ('ORD002', 'th01', 2, 54000);
            ");
            $inserted[] = 'order_detail (2 records)';
        }

        // 12. Order Items (Table mới)
        $check = $pdo->query("SELECT COUNT(*) FROM order_items")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO order_items (id, order_id, product_id, quantity, price, created_at) VALUES
                (1, 1, 11, 1, 2500000.00, '2025-12-03 01:59:15'),
                (2, 2, 2, 1, 65000000.00, '2025-12-03 02:35:13'),
                (3, 3, 3, 1, 22000000.00, '2025-12-03 02:59:59'),
                (4, 4, 18, 1, 6500000.00, '2025-12-04 03:42:30'),
                (5, 5, 1, 1, 18500000.00, '2025-12-08 08:38:08'),
                (6, 6, 3, 1, 22000000.00, '2025-12-08 08:54:22'),
                (7, 7, 1, 1, 18500000.00, '2025-12-08 09:30:20'),
                (8, 8, 3, 1, 22000000.00, '2025-12-09 03:10:31'),
                (9, 8, 1, 1, 18500000.00, '2025-12-09 03:10:31'),
                (10, 8, 5, 1, 38000000.00, '2025-12-09 03:10:31'),
                (11, 8, 9, 1, 28000000.00, '2025-12-09 03:10:31'),
                (12, 8, 10, 1, 75000000.00, '2025-12-09 03:10:31'),
                (13, 9, 3, 1, 22000000.00, '2025-12-09 03:49:22');
            ");
            $inserted[] = 'order_items (13 records)';
        }

        $message = "Setup thành công! Đầy đủ 14 tables và data đã được fix lỗi FK.<br>Tables: " . implode(', ', $inserted) . ".";

    } catch (PDOException $e) {
        $error = "Lỗi setup: " . $e->getMessage();
    }
endif;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Setup DB - Guitar Shop (Fix FK Error)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Setup Database Guitar Shop (Fixed Version)</h2>
    <p>Script này đã được sửa lỗi 1452: Tự động sắp xếp insert `users` trước `order`, `category` trước `book`.</p>
    <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <input type="hidden" name="setup" value="1">
        <button type="submit" class="btn btn-primary">Chạy Setup (Clean & Fix)</button>
    </form>
    <p class="mt-3">Sau khi chạy xong, hãy kiểm tra phpMyAdmin để đảm bảo data đã vào đủ.</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>