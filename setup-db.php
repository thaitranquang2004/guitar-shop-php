<?php
include 'config/database.php';  // Kết nối DB (pgsql cho Render, mysql cho WAMP local)

$message = ''; $error = '';
if ($_POST && isset($_POST['setup'])):  // Submit form để chạy setup
    try {

        // Tắt kiểm tra FK để drop tables mà không lỗi dependency (như order_items depend on products)
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

        // DROP tất cả tables nếu tồn tại (bắt đầu với users như yêu cầu, sau đó các table khác theo thứ tự an toàn)
        $tables_to_drop = ['users', 'userss'];
        foreach ($tables_to_drop as $table) {
            $pdo->exec("DROP TABLE IF EXISTS $table");
        }

        // Tạo table category (thiếu trong dump, cần cho book FK)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS category (
                cat_id VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                cat_name VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                PRIMARY KEY (cat_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Tạo table publisher (từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS publisher (
                pub_id VARCHAR(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                pub_name VARCHAR(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
                PRIMARY KEY (pub_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
        ");

        // Tạo table admin (từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS admin (
                username VARCHAR(30) NOT NULL,
                password VARCHAR(32) DEFAULT NULL,
                name VARCHAR(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
                email VARCHAR(60) DEFAULT NULL,
                phone VARCHAR(12) DEFAULT NULL,
                PRIMARY KEY (username)
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
        ");

        // Tạo table banners (từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS banners (
                id INT NOT NULL AUTO_INCREMENT,
                image VARCHAR(255) NOT NULL,
                title VARCHAR(255) DEFAULT NULL,
                subtitle VARCHAR(255) DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                display_order INT DEFAULT 0,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=6;
        ");

        // Tạo table blog_posts (từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS blog_posts (
                id INT NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=9;
        ");

        // Tạo table news (thêm từ lab 8.5, tương tự blog_posts cho module news)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS news (
                id INT NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=10;
        ");

        // Tạo table book (từ dump)
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

        // Tạo table order (table cũ từ dump, không có data)
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

        // Tạo table orders (table mới từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT NOT NULL AUTO_INCREMENT,
                user_id INT DEFAULT NULL,
                customer_name VARCHAR(100) NOT NULL,
                customer_email VARCHAR(100) NOT NULL,
                customer_phone VARCHAR(20) NOT NULL,
                customer_address TEXT NOT NULL,
                total_amount DECIMAL(10,2) NOT NULL,
                status VARCHAR(20) DEFAULT 'pending',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY user_id (user_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=10;
        ");

        // Tạo table order_detail (table cũ từ dump, không có data)
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

        // Tạo table order_items (table mới từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS order_items (
                id INT NOT NULL AUTO_INCREMENT,
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                KEY order_id (order_id),
                KEY product_id (product_id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=14;
        ");

        // Tạo table products (từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id INT NOT NULL AUTO_INCREMENT,
                name VARCHAR(255) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                description TEXT,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                category VARCHAR(50) DEFAULT 'Guitar',
                PRIMARY KEY (id)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=25;
        ");

        // Tạo table users (table cũ từ dump)
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

        // Tạo table userss (table mới từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS userss (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                username VARCHAR(50) NOT NULL,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100) NOT NULL,
                full_name VARCHAR(100) DEFAULT NULL,
                role VARCHAR(20) DEFAULT 'customer',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY username (username),
                UNIQUE KEY email (email)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=2;
        ");

        // Kiểm tra và INSERT data mẫu (chỉ nếu table rỗng, dùng data từ dump)
        $tables = ['category', 'publisher', 'admin', 'banners', 'blog_posts', 'news', 'book', 'order', 'orders', 'order_detail', 'order_items', 'products', 'users', 'userss'];
        $inserted = [];

        // Category (thêm mẫu cho book)
        $check = $pdo->query("SELECT COUNT(*) FROM category")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO category (cat_id, cat_name) VALUES
                ('td', 'Từ điển'),
                ('th', 'Thủ thuật');
            ");
            $inserted[] = 'category (2 records mẫu)';
        }

        // Publisher (7 records)
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

        // Admin (3 records)
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

        // Banners (5 records)
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

        // Blog_posts (8 records)
        $check = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO blog_posts (id, title, content, image, created_at) VALUES
                (1, 'Cách chọn đàn guitar cho người mới bắt đầu', 'Việc chọn cây đàn guitar đầu tiên rất quan trọng. Bạn nên cân nhắc giữa guitar acoustic (dây sắt) và classic (dây nylon). Guitar classic dây nylon mềm hơn, đỡ đau tay hơn cho người mới, nhưng acoustic lại có âm thanh vang và phù hợp đệm hát hơn. Ngoài ra, ngân sách và kích thước đàn cũng là yếu tố cần xem xét...', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (2, '5 Lỗi thường gặp khi tự học guitar', 'Tự học guitar là một hành trình thú vị nhưng cũng đầy thử thách. Nhiều người mới thường mắc các lỗi như: cầm đàn sai tư thế, bấm hợp âm không vuông góc, không dùng metronome để giữ nhịp, tập quá nhanh mà không chú trọng độ sạch của nốt, và bỏ qua nhạc lý cơ bản. Hãy khắc phục những lỗi này để tiến bộ nhanh hơn...', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (3, 'Bảo quản đàn guitar trong mùa nồm ẩm', 'Độ ẩm là kẻ thù số một của đàn guitar gỗ. Vào mùa nồm ẩm, gỗ hút nước sẽ nở ra, gây cong cần, nứt mặt đàn hoặc bong ngựa. Để bảo quản, bạn nên để đàn trong bao da hoặc case cứng có gói hút ẩm. Tránh để đàn trực tiếp dưới sàn nhà hoặc dựa vào tường ẩm. Nếu có điều kiện, hãy sử dụng máy hút ẩm trong phòng...', 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (4, 'Lịch sử phát triển của Fender Stratocaster', 'Fender Stratocaster, ra đời năm 1954, đã thay đổi bộ mặt âm nhạc thế giới. Với thiết kế đường cong quyến rũ, 3 pickup và cần nhún, nó trở thành biểu tượng của Rock n Roll. Từ Buddy Holly, Jimi Hendrix đến David Gilmour, Stratocaster luôn là sự lựa chọn hàng đầu...', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (5, 'Review Boss Katana-50 MkII: Ampli tốt nhất tầm giá?', 'Boss Katana-50 MkII đang làm mưa làm gió trên thị trường ampli tập luyện. Với công suất 50W, loa 12 inch và hàng loạt hiệu ứng tích hợp, liệu nó có thực sự xứng đáng với lời khen ngợi? Hãy cùng chúng tôi đánh giá chi tiết về âm thanh, tính năng và độ bền của sản phẩm này...', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (6, 'Top 5 bài hát guitar acoustic dễ tập cho người mới', 'Bạn mới tập chơi guitar và muốn tìm những bài hát đơn giản để luyện tập? Dưới đây là danh sách 5 bài hát kinh điển với hợp âm dễ bấm và giai điệu quen thuộc: Happy Birthday, Knockin on Heavens Door, Stand By Me, Zombie, và Hallelujah. Cùng bắt đầu nhé!', 'https://images.unsplash.com/photo-1462965326201-d02e4f455804?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (7, 'Phân biệt các loại gỗ làm đàn guitar', 'Gỗ làm đàn ảnh hưởng rất lớn đến âm thanh. Spruce (Vân sam) cho âm thanh sáng, rõ nét. Cedar (Tuyết tùng) ấm áp, êm dịu. Mahogany (Gụ) cân bằng, dày tiếng. Rosewood (Cẩm lai) trầm ấm, vang vọng. Hiểu rõ về các loại gỗ sẽ giúp bạn chọn được cây đàn phù hợp với phong cách chơi của mình.', 'https://images.unsplash.com/photo-1542300058-b94b8ab7411b?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                (8, 'Hướng dẫn thay dây đàn guitar đúng cách', 'Thay dây đàn định kỳ là việc làm cần thiết để giữ cho cây đàn luôn có âm thanh tốt nhất. Tuy nhiên, không phải ai cũng biết cách thay dây đúng chuẩn để tránh làm hỏng đàn hoặc dây nhanh đứt. Bài viết này sẽ hướng dẫn bạn từng bước thay dây cho cả guitar acoustic và classic...', 'https://cdn.pixabay.com/photo/2016/11/23/15/48/guitar-1853661_1280.jpg', '2025-12-03 01:45:18');
            ");
            $inserted[] = 'blog_posts (8 records)';
        }

        // News (10 records mẫu từ lab 8.5, tương tự blog_posts cho module news)
        $check = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO news (id, title, content, image, created_at) VALUES
                (1, 'Tin tức mới: Fender ra mắt Stratocaster 2025', 'Fender vừa công bố mẫu Stratocaster mới với công nghệ pickup hiện đại, giá chỉ từ 20 triệu VNĐ. Đây là lựa chọn lý tưởng cho guitarist chuyên nghiệp.', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?auto=format&fit=crop&w=800&q=80', '2025-12-13 10:00:00'),
                (2, 'Khuyến mãi ampli Boss Katana', 'Giảm 20% ampli Boss Katana-50 MkII trong tuần này. Âm thanh mạnh mẽ, hiệu ứng đa dạng – hoàn hảo cho tập luyện tại nhà.', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-13 11:00:00'),
                (3, 'Hướng dẫn bảo dưỡng guitar mùa đông', 'Mùa đông lạnh giá có thể làm khô cần đàn. Sử dụng dầu dưỡng để giữ đàn luôn mượt mà.', 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?auto=format&fit=crop&w=800&q=80', '2025-12-13 12:00:00'),
                (4, 'Review Taylor 214ce: Đàn acoustic đỉnh cao', 'Taylor 214ce với âm thanh cân bằng, pickup ES2 – lý tưởng cho biểu diễn live.', 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?auto=format&fit=crop&w=800&q=80', '2025-12-13 13:00:00'),
                (5, 'Top 10 pedal effect phải có', 'Từ wah đến delay, đây là những pedal thiết yếu cho setup guitar của bạn.', 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?auto=format&fit=crop&w=800&q=80', '2025-12-13 14:00:00'),
                (6, 'Cập nhật: Martin D-28 restock', 'Martin D-28 đã về hàng, chỉ còn 5 cây – đặt ngay để tránh hết!', 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8?auto=format&fit=crop&w=800&q=80', '2025-12-13 15:00:00'),
                (7, 'Học guitar online miễn phí', 'Khóa học cơ bản trên YouTube: Từ hợp âm đến solo đơn giản.', 'https://images.unsplash.com/photo-1462965326201-d02e4f455804?auto=format&fit=crop&w=800&q=80', '2025-12-13 16:00:00'),
                (8, 'Tin nóng: Gibson Les Paul giảm giá', 'Giảm sốc 15% Gibson Les Paul Standard – cơ hội vàng cho rocker.', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', '2025-12-13 17:00:00'),
                (9, 'Sự kiện workshop guitar miễn phí', 'Ngày 20/12: Học chơi acoustic với nghệ sĩ nổi tiếng tại cửa hàng.', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-13 18:00:00'),
                (10, 'Mẹo chọn dây đàn Elixir', 'Elixir Nanoweb: Độ bền cao, âm thanh sáng lâu – khuyến nghị cho mọi guitarist.', 'https://images.unsplash.com/photo-1516280440614-6697288d5d38?auto=format&fit=crop&w=800&q=80', '2025-12-13 19:00:00');
            ");
            $inserted[] = 'news (10 records mẫu từ lab 8.5)';
        }

        // Book (11 records đầy đủ từ dump)
        $check = $pdo->query("SELECT COUNT(*) FROM book")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO book (book_id, book_name, description, price, img, pub_id, cat_id) VALUES
                ('td01', 'Từ Điển mẫu câu tiếng Nhật', 'Tập hợp tất cả các mẫu câu tiếng Nhật. Phong phú, đầu đủ nhất. ', 450000, 'td01.jpg', 'gd', 'td'),
                ('td02', 'Từ Điển Kinh Doanh Và Tiếp Thị Hiện Đại', 'Quyển sách “Từ điển Kinh doanh – Tiếp thị Hiện đại” (Modern Business & Marketing Dictionary) của tác giả Cung Kim Tiến (Bút danh Anh Tuấn) trình bày các thuật ngữ đang sử dụng thịnh hành trong giao dịch kinh doanh và tiếp thị trong nước và quốc tế. Đặc điểm của quyển sách là các thuật ngữ được đặt trong các bối cảnh khác nhau, bằng cách dẫn các đoạn văn xuất hiện trong thực tiễn kinh doanh quốc tế, giúp bạn đọc hiểu rõ được ý nghĩa và cách sử dụng trong thực tiễn của các thuật ngữ chuyên biệt này, với các nội dung thú vị khác nhau.\r\n Tác giả đã chọn lọc một cách công phu các đoạn văn đa dạng và phong phú, xuất hiện trên các ấn phẩm quốc tế khác nhau, giúp độc giả có cơ hội thuận lợi trong giao tiếp, soạn thảo, hoặc tham gia các buổi họp liên quan đến kinh doanh, đảm nhiệm các nhiệm vụ về kinh doanh, quản lý và tiếp thị trong các doanh nghiệp.\r\nQuyển sách này được kỳ vọng sẽ trợ giúp hiệu quả để bạn đọc tiếp cận một lĩnh vực tri thức kinh doanh bằng Anh ngữ, là bạn đồng hành trên con đường sự nghiệp trong thời kỳ quốc tế hóa.', 195000, 'td02.gif', 'vhtt', 'td'),
                ('td03', 'Đại Từ Điển Tiếng Việt (Bản mới 2010)', 'Thêm yêu tiếng Việt\r\n\r\n \r\n\r\nTừ lâu chúng ta đã có nhiều công trình nghiên cứu về kho tàng tiếng Việt, thế nhưng “Đại từ điển tiếng Việt” (NXB Đại học Quốc gia TPHCM - Nguyễn Như Ý chủ biên) vừa ra mắt bạn đọc là công trình đầy đặn và đồ sộ nhất. Cuốn sách đã bắt nhịp cầu cho những ai yêu tiếng mẹ…\r\n\r\n \r\n\r\nCầm trên tay cuốn Đại từ điển dày gần 2.000 trang mới cảm nhận hết tâm huyết của những người làm sách. Cuốn từ điển này được in lần đầu tiên vào năm 1999, đến nay, đáp ứng nhu cầu của bạn đọc, các tác giả đã tiến hành nghiên cứu, bổ sung.\r\n\r\n \r\n\r\nTrong lần tái bản này, ban biên soạn đã chọn và đưa vào sách những từ ngữ mới xuất hiện và đã được dùng rộng rãi trong đời sống và trên các phương tiện thông tin đại chúng nhằm làm tăng tính mới mẻ và tiện ích cho người sử dụng.\r\n\r\nMột trong những ý tưởng chinh phục người đọc là tính đa dạng của Đại từ điển tiếng Việt. Bởi nó không chỉ đơn thuần là sự tra cứu nghĩa các từ mà mở ra chân trời kiến thức mới. Việc đan xen những kiến thức cơ bản về văn hóa, văn minh Việt Nam và thế giới, giới thiệu tổng quan và hệ thống các hiện vật văn hóa như: Đơn vị đo lường của Việt Nam và thế giới, đồng bạc Việt xưa và nay, các loại trống đồng hiện có ở Việt Nam, quốc kỳ các nước trên thế giới… Đây là những thông tin bổ ích đáp ứng nhu cầu bổ sung kiến thức cơ bản của học sinh - sinh viên và các bạn trẻ Việt Nam.\r\n\r\n\r\n', 450000, 'td03.jpg', 'hcm', 'td'),
                ('td04', 'từ điển y học sức khỏe bệnh lý Anh Anh Việt', 'Từ điển y học - sức khỏe bệnh lý Anh Anh Việt này được biên soạn để đáp ứng nhu cầu tìm hiểu, tra cướu và dịch thuật các tư liệu y khoa bằng tiếng anh, cũng như tăng cường kiến thức về các bệnh thường gặp của các thành phần độc giả trong xã hội. ', 380000, 'td04.jpg', 'tn', 'td'),
                ('td05', 'Từ Điển Anh Việt - 75000 Từ', 'Từ điển mới ...', 50000, 'td05.jpg', 'hcm', 'td'),
                ('td06', 'Từ điển địa danh hành chính Nam Bộ', 'Từ điển địa danh hành chính Nam Bộ do tác giả Nguyễn Đình Tư biên soạn hết sức công phu, tổng hợp được nhiều tư liệu quý, là công cụ giúp bạn đọc tra cứu một cách khoa học về địa danh hành chính. Đây là cuốn sách có giá trị không chỉ bởi nó cung cấp một lượng mục từ khá đồ sộ, mà còn bởi tác giả đã dành rất nhiều công sức và tâm huyết để sưu tầm, xử lý tư liệu về vùng đất có bề dày truyền thống lịch sử, nhưng cũng có sự thay đổi nhiều và phức tạp nhất về địa danh hành chính', 265000, 'td06.jpg', 'hcm', 'td'),
                ('th01', '100 thủ thuật với Excel 2010', '100 thủ thuật ứng với 100 bài tập thực hành được hướng dẫn, giải thích theo bố cục chặt chẽ, cách trình bày rõ ràng, dễ sử dụng, bạn đọc có thể tự mình xử lý những vấn đề nảy sinh trong quá trình thực hành đồng thời giúp các bạn thao tác nhanh trên bảng tính.\r\n', 54000, 'th01.gif', 'hcm', 'th'),
                ('th02', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu', 'Tiếp theo tập 1, tập 2 của cuốn sách \"Lập trình Web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1\" bao gồm 10 chương và ứng dụng đính kèm lần lượt giới thiệu cùng bạn đọc các kiến thức liên quan đến Session, Cookie, giỏ hàng trực tuyến, tìm kiếm và phân trang dữ liệu, lập trình hướng đối tượng và sử dụng Zend Framework.\r\n\r\nChương 8 trình bày kiến thức cơ bản của kịch bản trình chủ PHP và cơ sở dữ liệu MySQL.\r\n\r\nSang chương 9, bạn tiếp tục tìm hiểu cách thiết kế trang Web cho phép người sử dụng tìm kiếm và phân trang dữ liệu trình bày với nhiều hình thức khác nhau.\r\n\r\nĐể xây dựng ứng dụng thương mại điện tử hoàn chỉnh và mang tính chuyên nghiệp cao, bạn tiếp tục tìm hiểu cách sử dụng hàm Session và Cookie trong chương 10 để lưu trữ thông tin của người sử dụng nhằm vào mục đích quản lý tài nguyên của Website.\r\n\r\nMọi ứng dụng thương mại điện tử đều cung cấp chức năng giỏ hàng, trong đó người sử dụng có thể thêm, xóa hoặc thay đổi số lượng các mặt hàng. Chương này hướng dẫn bạn cách xây dựng một giỏ hàng trực tuyến hoàn chỉnh sử dụng Session và Cookie.', 76000, 'th02.jpg', 'hcm', 'th'),
                ('th03', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1 (Tập1)', 'Tập 1 của cuốn sách \"Lập trình Web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1\" bao gồm 7 chương và ứng dụng đính kèm. Chương 1 cung cấp cho bạn kiến thức từ chức năng của Website, cài đặt gói WamSever 2.0 và cấu hình để có thể vận hành ứng dụng Web bằng PHP, MySQL và Apache Web Sever.\r\n\r\nSang chương 2, bạn tiếp tục tìm hiểu cách tạo Website và thiết kế cấu trúc dùng cho doanh nghiệp bằng hệ quản trị nội dung mã nguồn mở Joomla. Nhằm thỏa mãn nội dung trình bày, bạn tiếp tục tìm hiểu cách thiết kế trang Web tĩnh hay động bằng mã tự sinh PHP với phần mềm Dreamweaver CS trong chương 3 và thẻ HTML trong chương 4.\r\n\r\nTiếp theo, bạn có thể tìm hiểu cú pháp của kịch bản PHP trong chương 5 và học cách sử dụng ứng dụng PhpMyAdmin để quản trị cơ sở dữ liệu MySQL trong chương 6. Sang chương 7 bạn tìm hiểu phát biểu SQL của cơ sở dữ liệu MySQL dùng để xây dựng ứng dụng bán hàng trực tuyến.', 76000, 'th03.jpg', 'hcm', 'th'),
                ('th04', 'Làm Quen Với Internet', 'Ngày nay với sự phát triển không ngừng của kinh tế nói chung và ngành công nghệ thông tin nói riêng, chúng ta có thể dễ dàng tiếp xúc và làm quen với máy vi tính. Tuy nhiên đây là một lĩnh vực mới lại chưa được phổ cập ở mọi cấp học nên các em sẽ có cảm giác bỡ ngỡ, thiếu tự tin khi lần đầu làm quen với chiếc máy tính đa năng. Mỗi bài học trong cuốn sách là một bài thực hành, được thực hiện qua từng bước cơ bản với hình ảnh minh họa trực quan và những lời giải thích chi tiết.', 31000, 'th04.jpg', 'hcm', 'th'),
                ('th05', 'Từng Bước Làm Quen Với Máy Tính', 'Mục Lục:\r\n\r\nBài 1: Máy tính điện tử và hệ điều hành\r\n\r\nBài 2: Hệ điều hành Window XP\r\n\r\nBài 3: Làm việc với máy tính qua desktop\r\n\r\nBài 4: Tệp tin và thư mục\r\n\r\nBài 5: Sử dụng Window Explorer\r\n\r\nBài 6: Một số thao tác cần biết\r\n\r\nPhụ lục – Những tổ hợp phím tắt', 31000, 'th05.jpg', 'vhtt', 'th'),
                ('th06', 'Quản Trị Windows Server 2008 - Tập 2', 'Kế thừa những ưu điểm vượt trội và sự thành công của Windows Server 2003 cùng những phiên bản Windows trước đó, hãng Microsoft tiếp tục cho ra đời một phiên bản hệ điều hành dành cho máy chủ mới, Windows Server 2008. Phiên bản này đem đến cho người dùng sự nhanh chóng trong cài đặt;', 0, 'th06.jpg', 'hcm', 'th');
            ");
            $inserted[] = 'book (11 records đầy đủ)';
        }

        // Order (không có data trong dump, thêm mẫu nếu cần)
        $check = $pdo->query("SELECT COUNT(*) FROM `order`")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO `order` (order_id, email, order_date, consignee_name, consignee_add, consignee_phone, status) VALUES
                ('ORD001', 'abcd@yahoo.com', '2025-12-13 10:00:00', 'Nguyễn Minh Triết', 'Q1, HCM', '99999999', 0),
                ('ORD002', 'hung.stu@gmail.com', '2025-12-13 11:00:00', 'Trần Văn Hùng', 'Quận 3, HCM', '090090999', 1);
            ");
            $inserted[] = 'order (2 records mẫu)';
        }

        // Order_detail (không có data trong dump, thêm mẫu)
        $check = $pdo->query("SELECT COUNT(*) FROM order_detail")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO order_detail (order_id, book_id, quantity, price) VALUES
                ('ORD001', 'td01', 1, 450000),
                ('ORD002', 'th01', 2, 54000);
            ");
            $inserted[] = 'order_detail (2 records mẫu)';
        }

        // Orders (9 records)
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

        // Order_items (13 records)
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

        // Products (15 records từ dump)
        $check = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO products (id, name, price, description, image, created_at, category) VALUES
                (1, 'Fender Stratocaster Player Series', 18500000.00, 'Đàn guitar điện Fender Player Stratocaster mang đến âm thanh và cảm giác đích thực của Fender. Với thiết kế kinh điển, âm thanh linh hoạt và phong cách vượt thời gian, đây là cây đàn hoàn hảo cho mọi nghệ sĩ.', 'https://images.unsplash.com/photo-1564186763535-ebb21ef5277f?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (3, 'Ibanez RG550 Genesis', 22000000.00, 'Ibanez RG550 là biểu tượng của dòng đàn shred guitar. Cần đàn Super Wizard siêu mỏng, pickup V7/S1/V8 linh hoạt và nhún Edge tremolo huyền thoại. Hoàn hảo cho rock và metal.', 'https://images.unsplash.com/photo-1605020420620-20c943cc4669?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (5, 'Fender Telecaster American Pro II', 38000000.00, 'Fender American Professional II Telecaster mang đến sự cải tiến cho thiết kế cổ điển. Pickup V-Mod II mới, cần đàn Deep C thoải mái và ngựa đàn mới giúp tăng cường độ ngân và ngữ điệu.', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (8, 'Squier Classic Vibe 50s Strat', 10500000.00, 'Squier Classic Vibe 50s Stratocaster tái hiện lại sự ra đời của Stratocaster vào những năm 1950. Âm thanh Fender cổ điển với mức giá cực kỳ hấp dẫn.', 'https://images.unsplash.com/photo-1519508234439-4f23643125c1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Electric'),
                (9, 'Taylor 214ce Acoustic-Electric', 28000000.00, 'Taylor 214ce là cây đàn acoustic-electric dáng Grand Auditorium linh hoạt. Mặt đàn Sitka Spruce, lưng và hông Rosewood tạo nên âm thanh cân bằng, rõ nét. Hệ thống pickup ES2 giúp trình diễn sân khấu tuyệt vời.', 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
                (10, 'Martin D-28 Standard', 75000000.00, 'Martin D-28 là tiêu chuẩn vàng của đàn acoustic guitar. Được các huyền thoại như Hank Williams, The Beatles, Johnny Cash sử dụng. Âm thanh trầm ấm, vang vọng và đầy uy lực.', 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
                (12, 'Takamine GD20-NS', 6500000.00, 'Takamine GD20-NS là cây đàn dreadnought với mặt đàn Cedar nguyên tấm, mang lại âm thanh ấm áp và chi tiết. Thiết kế cần đàn mỏng giúp dễ chơi.', 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Acoustic'),
                (13, 'Fender CD-60S All-Mahogany', 5200000.00, 'Fender CD-60S All-Mahogany mang lại âm thanh êm dịu và ấm áp. Mặt đàn Mahogany nguyên tấm giúp âm thanh càng hay hơn theo thời gian.', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (16, 'Ibanez SR300E Bass', 9500000.00, 'Ibanez SR300E là cây bass hiện đại với cần đàn mỏng, nhẹ và hệ thống pickup PowerSpan linh hoạt. EQ 3 băng tần giúp bạn tạo ra mọi loại âm thanh.', 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Bass'),
                (18, 'Boss Katana-50 MkII', 6500000.00, 'Boss Katana-50 MkII là ampli guitar đa năng với 5 kiểu amp độc đáo và hơn 60 hiệu ứng Boss tích hợp. Công suất 50W đủ cho tập luyện và biểu diễn nhỏ.', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (19, 'Fender Mustang LT25', 4200000.00, 'Fender Mustang LT25 là ampli tập luyện hoàn hảo với giao diện dễ sử dụng, 30 preset âm thanh tuyệt vời và cổng USB để thu âm.', 'https://images.unsplash.com/photo-1593104547489-5cfb3839a3b5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (20, 'Marshall DSL40CR', 18500000.00, 'Marshall DSL40CR là ampli đèn 40W mang đến âm thanh Marshall huyền thoại. Từ clean trong trẻo đến distortion bùng nổ.', 'https://images.unsplash.com/photo-1551712744-1963ba88d303?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (22, 'Dunlop Cry Baby Wah', 2800000.00, 'Dunlop Cry Baby GCB95 là pedal wah kinh điển nhất mọi thời đại. Được sử dụng bởi Jimi Hendrix, Eric Clapton và vô số nghệ sĩ khác.', 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Guitar'),
                (23, 'Capo Kyser Quick-Change', 450000.00, 'Capo Kyser Quick-Change bền bỉ, dễ sử dụng và giữ dây chắc chắn. Phụ kiện không thể thiếu cho mọi guitarist.', 'https://images.unsplash.com/photo-1588449668365-d15e397f6787?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18', 'Accessories'),
                (24, 'Thịnh Thịnh', 13432.00, 'Guitar điênn', 'https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', '2025-12-03 08:15:23', 'Electric');
            ");
            $inserted[] = 'products (15 records)';
        }

        // Users (2 records)
        $check = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO users (email, password, name, address, phone) VALUES
                ('abcd@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Minh Triết', 'Q1', '99999999'),
                ('hung.stu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Đại Ca - Trần văn Hùng', 'Quận 3', '090090999');
            ");
            $inserted[] = 'users (2 records)';
        }

        // Userss (1 record)
        $check = $pdo->query("SELECT COUNT(*) FROM userss")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO userss (id, username, password, email, full_name, role, created_at) VALUES
                (1, 'admin', '1234', 'admin1@guitarshop.com', 'Administratorr', 'admin', '2025-12-02 18:36:45');
            ");
            $inserted[] = 'userss (1 record)';
        }

        $message = "Setup thành công từ guitar_shop.sql + lab 8.5! Tạo đầy đủ 14 tables (thêm news).<br>Đã thêm data mẫu: " . implode(', ', $inserted) . ".";

    } catch (PDOException $e) {
        $error = "Lỗi setup: " . $e->getMessage();
    }
endif;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Setup DB - Guitar Shop (Đầy Đủ 14 Tables Với News Từ Lab 8.5)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Setup Database Từ guitar_shop.sql + Lab 8.5 (Chạy Một Lần - Đầy Đủ 14 Tables)</h2>
    <p>Sử dụng PDO để tạo tất cả tables từ dump (admin, banners, blog_posts, book, category, order, orders, order_detail, order_items, products, publisher, users, userss) và thêm table `news` từ lab 8.5 (cho module news). Tương thích MySQL (WAMP).</p>
    <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <input type="hidden" name="setup" value="1">
        <button type="submit" class="btn btn-primary">Chạy Setup (Tạo 14 Tables + Data Đầy Đủ, Bao Gồm News)</button>
    </form>
    <p class="mt-3">Sau khi chạy, kiểm tra phpMyAdmin để xem data (news: 10 tin tức mẫu). Sử dụng VS Code để edit code PHP và WAMP Server để test ứng dụng web (module news với class News kế thừa Db).</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>