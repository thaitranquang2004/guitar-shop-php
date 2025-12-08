<?php
include 'config/database.php';  // Kết nối DB (pgsql cho Render, mysql cho WAMP local)

$message = ''; $error = '';
if ($_POST && isset($_POST['setup'])):  // Submit form để chạy setup
    try {
        // Tạo table banners (IF NOT EXISTS)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS banners (
                id SERIAL PRIMARY KEY,
                image VARCHAR(255) NOT NULL,
                title VARCHAR(255) DEFAULT NULL,
                subtitle VARCHAR(255) DEFAULT NULL,
                link VARCHAR(255) DEFAULT NULL,
                display_order INTEGER DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Tạo table blog_posts
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS blog_posts (
                id SERIAL PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Tạo table orders
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id SERIAL PRIMARY KEY,
                user_id INTEGER DEFAULT NULL,
                customer_name VARCHAR(100) NOT NULL,
                customer_email VARCHAR(100) NOT NULL,
                customer_phone VARCHAR(20) NOT NULL,
                customer_address TEXT NOT NULL,
                total_amount NUMERIC(10,2) NOT NULL,
                status VARCHAR(20) DEFAULT 'pending',  -- ENUM -> VARCHAR (Postgres đơn giản)
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Tạo table products (có category từ dump)
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS products (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                price NUMERIC(10,2) NOT NULL,
                image VARCHAR(255) DEFAULT NULL,
                description TEXT NOT NULL,
                category VARCHAR(50) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Tạo table users
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                full_name VARCHAR(100) DEFAULT NULL,
                role VARCHAR(20) DEFAULT 'customer',  -- ENUM -> VARCHAR
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            );
        ");

        // Kiểm tra và INSERT data mẫu (chỉ nếu table rỗng)
        $tables = ['banners', 'blog_posts', 'orders', 'products', 'users'];
        $inserted = [];

        // Banners (5 records)
        $check = $pdo->query("SELECT COUNT(*) FROM banners")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO banners (image, title, subtitle, link, display_order, created_at) VALUES
                ('https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=1600&q=80', 'Âm Nhạc Là Cuộc Sống', 'Khám phá bộ sưu tập guitar đẳng cấp thế giới.', 'shop.php', 1, '2025-12-03 02:14:15'),
                ('https://cdn.pixabay.com/photo/2015/05/07/11/02/guitar-756326_1280.jpg', 'Fender Stratocaster', 'Huyền thoại trở lại với thiết kế mới.', 'shop.php?category=Electric', 2, '2025-12-03 02:14:15'),
                ('https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=1600&q=80', 'Acoustic Soul', 'Âm thanh mộc mạc, cảm xúc thăng hoa.', 'shop.php?category=Acoustic', 3, '2025-12-03 02:14:15'),
                ('https://cdn.pixabay.com/photo/2020/04/14/17/44/guitar-5043613_1280.jpg', 'Hiiiiiii', 'Khám phá bộ sưu tập guitar đẳng cấp thế giới.', '', 4, '2025-12-03 02:29:20'),
                ('https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', 'Chó Thịnh', 'Thịnh chó điên', 'https://www.facebook.com/', 5, '2025-12-03 08:12:40');
            ");
            $inserted[] = 'banners (5 records)';
        }

        // Blog_posts (8 records)
        $check = $pdo->query("SELECT COUNT(*) FROM blog_posts")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO blog_posts (title, content, image, created_at) VALUES
                ('Cách chọn đàn guitar cho người mới bắt đầu', 'Việc chọn cây đàn guitar đầu tiên rất quan trọng. Bạn nên cân nhắc giữa guitar acoustic (dây sắt) và classic (dây nylon). Guitar classic dây nylon mềm hơn, đỡ đau tay hơn cho người mới, nhưng acoustic lại có âm thanh vang và phù hợp đệm hát hơn. Ngoài ra, ngân sách và kích thước đàn cũng là yếu tố cần xem xét...', 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('5 Lỗi thường gặp khi tự học guitar', 'Tự học guitar là một hành trình thú vị nhưng cũng đầy thử thách. Nhiều người mới thường mắc các lỗi như: cầm đàn sai tư thế, bấm hợp âm không vuông góc, không dùng metronome để giữ nhịp, tập quá nhanh mà không chú trọng độ sạch của nốt, và bỏ qua nhạc lý cơ bản. Hãy khắc phục những lỗi này để tiến bộ nhanh hơn...', 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('Bảo quản đàn guitar trong mùa nồm ẩm', 'Độ ẩm là kẻ thù số một của đàn guitar gỗ. Vào mùa nồm ẩm, gỗ hút nước sẽ nở ra, gây cong cần, nứt mặt đàn hoặc bong ngựa. Để bảo quản, bạn nên để đàn trong bao da hoặc case cứng có gói hút ẩm. Tránh để đàn trực tiếp dưới sàn nhà hoặc dựa vào tường ẩm. Nếu có điều kiện, hãy sử dụng máy hút ẩm trong phòng...', 'https://images.unsplash.com/photo-1550291652-6ea9114a47b1?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('Lịch sử phát triển của Fender Stratocaster', 'Fender Stratocaster, ra đời năm 1954, đã thay đổi bộ mặt âm nhạc thế giới. Với thiết kế đường cong quyến rũ, 3 pickup và cần nhún, nó trở thành biểu tượng của Rock n Roll. Từ Buddy Holly, Jimi Hendrix đến David Gilmour, Stratocaster luôn là sự lựa chọn hàng đầu...', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('Review Boss Katana-50 MkII: Ampli tốt nhất tầm giá?', 'Boss Katana-50 MkII đang làm mưa làm gió trên thị trường ampli tập luyện. Với công suất 50W, loa 12 inch và hàng loạt hiệu ứng tích hợp, liệu nó có thực sự xứng đáng với lời khen ngợi? Hãy cùng chúng tôi đánh giá chi tiết về âm thanh, tính năng và độ bền của sản phẩm này...', 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('Top 5 bài hát guitar acoustic dễ tập cho người mới', 'Bạn mới tập chơi guitar và muốn tìm những bài hát đơn giản để luyện tập? Dưới đây là danh sách 5 bài hát kinh điển với hợp âm dễ bấm và giai điệu quen thuộc: Happy Birthday, Knockin on Heavens Door, Stand By Me, Zombie, và Hallelujah. Cùng bắt đầu nhé!', 'https://images.unsplash.com/photo-1462965326201-d02e4f455804?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('Phân biệt các loại gỗ làm đàn guitar', 'Gỗ làm đàn ảnh hưởng rất lớn đến âm thanh. Spruce (Vân sam) cho âm thanh sáng, rõ nét. Cedar (Tuyết tùng) ấm áp, êm dịu. Mahogany (Gụ) cân bằng, dày tiếng. Rosewood (Cẩm lai) trầm ấm, vang vọng. Hiểu rõ về các loại gỗ sẽ giúp bạn chọn được cây đàn phù hợp với phong cách chơi của mình.', 'https://images.unsplash.com/photo-1542300058-b94b8ab7411b?auto=format&fit=crop&w=800&q=80', '2025-12-03 01:45:18'),
                ('Hướng dẫn thay dây đàn guitar đúng cách', 'Thay dây đàn định kỳ là việc làm cần thiết để giữ cho cây đàn luôn có âm thanh tốt nhất. Tuy nhiên, không phải ai cũng biết cách thay dây đúng chuẩn để tránh làm hỏng đàn hoặc dây nhanh đứt. Bài viết này sẽ hướng dẫn bạn từng bước thay dây cho cả guitar acoustic và classic...', 'https://cdn.pixabay.com/photo/2016/11/23/15/48/guitar-1853661_1280.jpg', '2025-12-03 01:45:18');
            ");
            $inserted[] = 'blog_posts (8 records)';
        }

        // Orders (5 records, truncated trong dump, dùng mẫu)
        $check = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_amount, status, created_at) VALUES
                (NULL, 'Thai Tran', 'smon24690@gmail.com', '0369029500', 'Nha Be', 2500000.00, 'cancelled', '2025-12-04 04:13:00'),
                (NULL, 'John Doe', 'john@example.com', '0123456789', 'HCM City', 15000000.00, 'pending', '2025-12-04 04:13:00'),
                (1, 'Jane Smith', 'jane@example.com', '0987654321', 'HN City', 5000000.00, 'processing', '2025-12-04 04:13:00'),
                (NULL, 'Bob Wilson', 'bob@example.com', '0111222333', 'Da Nang', 8000000.00, 'completed', '2025-12-04 04:13:00'),
                (1, 'Alice Brown', 'alice@example.com', '0444555666', 'Can Tho', 12000000.00, 'cancelled', '2025-12-04 04:13:00');
            ");
            $inserted[] = 'orders (5 records)';
        }

        // Products (24 records từ dump, bao gồm category)
        $check = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO products (name, price, image, description, category, created_at) VALUES
                ('Gibson Les Paul Standard', 70000000.00, 'https://images.unsplash.com/photo-1511379938547-c1f69419868d?auto=format&fit=crop&w=800&q=80', 'Gibson Les Paul Standard là biểu tượng của rock guitar. Âm thanh dày, ấm áp với humbucker pickup kinh điển.', 'Electric', '2025-12-03 01:45:18'),
                ('Fender Stratocaster American Professional II', 55000000.00, 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', 'Fender American Professional II Stratocaster mang đến sự cải tiến cho thiết kế cổ điển. Pickup V-Mod II mới, cần đàn Deep C thoải mái và ngựa đàn mới giúp tăng cường độ ngân và ngữ điệu.', 'Electric', '2025-12-03 01:45:18'),
                ('PRS SE Custom 24', 19500000.00, 'https://images.unsplash.com/photo-1550985543-4418d87a7ae1?auto=format&fit=crop&w=800&q=80', 'PRS SE Custom 24 mang đến chất lượng PRS huyền thoại với mức giá phải chăng. Mặt đàn Maple đẹp mắt, cần đàn Wide Thin thoải mái và pickup 85/15 \"S\" linh hoạt.', 'Electric', '2025-12-03 01:45:18'),
                ('Gretsch G2622 Streamliner', 12500000.00, 'https://images.unsplash.com/photo-1569388330292-7a6a84165c6c?auto=format&fit=crop&w=800&q=80', 'Gretsch G2622 Streamliner Center Block mang đến âm thanh Gretsch cổ điển với khả năng chống hú tốt. Pickup BroadTron BT-2S cho âm thanh mạnh mẽ, rõ nét.', 'Electric', '2025-12-03 01:45:18'),
                ('Squier Classic Vibe 50s Strat', 10500000.00, 'https://images.unsplash.com/photo-1519508234439-4f23643125c1?auto=format&fit=crop&w=800&q=80', 'Squier Classic Vibe 50s Stratocaster tái hiện lại sự ra đời của Stratocaster vào những năm 1950. Âm thanh Fender cổ điển với mức giá cực kỳ hấp dẫn.', 'Electric', '2025-12-03 01:45:18'),
                ('Taylor 214ce Acoustic-Electric', 28000000.00, 'https://images.unsplash.com/photo-1556449895-a33c9dba33dd?auto=format&fit=crop&w=800&q=80', 'Taylor 214ce là cây đàn acoustic-electric dáng Grand Auditorium linh hoạt. Mặt đàn Sitka Spruce, lưng và hông Rosewood tạo nên âm thanh cân bằng, rõ nét. Hệ thống pickup ES2 giúp trình diễn sân khấu tuyệt vời.', 'Acoustic', '2025-12-03 01:45:18'),
                ('Martin D-28 Standard', 75000000.00, 'https://images.unsplash.com/photo-1541689592655-f5f52825a3b8?auto=format&fit=crop&w=800&q=80', 'Martin D-28 là tiêu chuẩn vàng của đàn acoustic guitar. Được các huyền thoại như Hank Williams, The Beatles, Johnny Cash sử dụng. Âm thanh trầm ấm, vang vọng và đầy uy lực.', 'Acoustic', '2025-12-03 01:45:18'),
                ('Yamaha C40 Classical Guitar', 2500000.00, 'https://images.unsplash.com/photo-1593693397690-362cb9666c64?auto=format&fit=crop&w=800&q=80', 'Yamaha C40 là lựa chọn hàng đầu cho người mới bắt đầu học guitar cổ điển. Chất lượng xây dựng tốt, âm thanh ấm áp và dễ chơi. Một cây đàn giá rẻ nhưng chất lượng vượt trội.', 'Acoustic', '2025-12-03 01:45:18'),
                ('Takamine GD20-NS', 6500000.00, 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=800&q=80', 'Takamine GD20-NS là cây đàn dreadnought với mặt đàn Cedar nguyên tấm, mang lại âm thanh ấm áp và chi tiết. Thiết kế cần đàn mỏng giúp dễ chơi.', 'Acoustic', '2025-12-03 01:45:18'),
                ('Fender CD-60S All-Mahogany', 5200000.00, 'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?auto=format&fit=crop&w=800&q=80', 'Fender CD-60S All-Mahogany mang lại âm thanh êm dịu và ấm áp. Mặt đàn Mahogany nguyên tấm giúp âm thanh càng hay hơn theo thời gian.', 'Guitar', '2025-12-03 01:45:18'),
                ('Gibson J-45 Standard', 68000000.00, 'https://images.unsplash.com/photo-1550985543-92bf55919010?auto=format&fit=crop&w=800&q=80', 'Gibson J-45, được mệnh danh là \"The Workhorse\", là cây đàn acoustic bán chạy nhất của Gibson. Âm thanh cân bằng, ấm áp và khả năng phóng âm tuyệt vời.', 'Acoustic', '2025-12-03 01:45:18'),
                ('Fender Player Precision Bass', 19500000.00, 'https://images.unsplash.com/photo-1568225556223-e99a77329d1c?auto=format&fit=crop&w=800&q=80', 'Fender Player Precision Bass mang đến âm thanh trầm ấm, mạnh mẽ đã định hình nên âm nhạc hiện đại. Thiết kế cổ điển, dễ chơi và bền bỉ.', 'Bass', '2025-12-03 01:45:18'),
                ('Ibanez SR300E Bass', 9500000.00, 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?auto=format&fit=crop&w=800&q=80', 'Ibanez SR300E là cây bass hiện đại với cần đàn mỏng, nhẹ và hệ thống pickup PowerSpan linh hoạt. EQ 3 băng tần giúp bạn tạo ra mọi loại âm thanh.', 'Bass', '2025-12-03 01:45:18'),
                ('Music Man StingRay Special', 55000000.00, 'https://images.unsplash.com/photo-1583265584283-93666b683838?auto=format&fit=crop&w=800&q=80', 'Music Man StingRay Special là tiêu chuẩn của dòng bass hiện đại. Âm thanh mạnh mẽ, punchy đặc trưng và thiết kế ergonomic thoải mái.', 'Bass', '2025-12-03 01:45:18'),
                ('Boss Katana-50 MkII', 6500000.00, 'https://images.unsplash.com/photo-1564507004663-b6dfb3c824d5?auto=format&fit=crop&w=800&q=80', 'Boss Katana-50 MkII là ampli guitar đa năng với 5 kiểu amp độc đáo và hơn 60 hiệu ứng Boss tích hợp. Công suất 50W đủ cho tập luyện và biểu diễn nhỏ.', 'Guitar', '2025-12-03 01:45:18'),
                ('Fender Mustang LT25', 4200000.00, 'https://images.unsplash.com/photo-1593104547489-5cfb3839a3b5?auto=format&fit=crop&w=800&q=80', 'Fender Mustang LT25 là ampli tập luyện hoàn hảo với giao diện dễ sử dụng, 30 preset âm thanh tuyệt vời và cổng USB để thu âm.', 'Guitar', '2025-12-03 01:45:18'),
                ('Marshall DSL40CR', 18500000.00, 'https://images.unsplash.com/photo-1551712744-1963ba88d303?auto=format&fit=crop&w=800&q=80', 'Marshall DSL40CR là ampli đèn 40W mang đến âm thanh Marshall huyền thoại. Từ clean trong trẻo đến distortion bùng nổ.', 'Guitar', '2025-12-03 01:45:18'),
                ('Elixir Nanoweb Strings', 350000.00, 'https://images.unsplash.com/photo-1516280440614-6697288d5d38?auto=format&fit=crop&w=800&q=80', 'Dây đàn Elixir Nanoweb phủ lớp bảo vệ siêu mỏng giúp kéo dài tuổi thọ dây gấp 3-5 lần so với dây thường, giữ âm thanh sáng lâu hơn.', 'Accessories', '2025-12-03 01:45:18'),
                ('Dunlop Cry Baby Wah', 2800000.00, 'https://images.unsplash.com/photo-1616455579100-2ceaa4eb2d37?auto=format&fit=crop&w=800&q=80', 'Dunlop Cry Baby GCB95 là pedal wah kinh điển nhất mọi thời đại. Được sử dụng bởi Jimi Hendrix, Eric Clapton và vô số nghệ sĩ khác.', 'Guitar', '2025-12-03 01:45:18'),
                ('Capo Kyser Quick-Change', 450000.00, 'https://images.unsplash.com/photo-1588449668365-d15e397f6787?auto=format&fit=crop&w=800&q=80', 'Capo Kyser Quick-Change bền bỉ, dễ sử dụng và giữ dây chắc chắn. Phụ kiện không thể thiếu cho mọi guitarist.', 'Accessories', '2025-12-03 01:45:18'),
                ('Thịnh Thịnh', 13432.00, 'Guitar điên', 'https://cdn.pixabay.com/photo/2019/12/06/17/52/guitar-4677875_1280.jpg', 'Electric', '2025-12-03 08:15:23'),
                ('Fender Telecaster American Pro II', 38000000.00, 'Fender American Professional II Telecaster mang đến sự cải tiến cho thiết kế cổ điển. Pickup V-Mod II mới, cần đàn Deep C thoải mái và ngựa đàn mới giúp tăng cường độ ngân và ngữ điệu.', 'https://images.unsplash.com/photo-1516924962500-2b4b3b99ea02?auto=format&fit=crop&w=800&q=80', 'Electric', '2025-12-03 01:45:18');
            ");
            $inserted[] = 'products (24 records)';
        }

        // Users (1 record)
        $check = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($check == 0) {
            $pdo->exec("
                INSERT INTO users (username, password, email, full_name, role, created_at) VALUES
                ('admin', '123', 'admin@guitarshop.com', 'Administrator', 'admin', '2025-12-03 01:36:45');
            ");
            $inserted[] = 'users (1 record)';
        }

        $message = "Setup thành công! Tạo tables: banners, blog_posts, orders, products, users.<br>Đã thêm data mẫu: " . implode(', ', $inserted) . ".";

    } catch (PDOException $e) {
        $error = "Lỗi setup: " . $e->getMessage();
    }
endif;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Setup DB - Guitar Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2>Setup Database Từ SQL Dump (Chạy Một Lần)</h2>
    <?php if ($message) echo "<div class='alert alert-success'>$message</div>"; ?>
    <?php if ($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="post">
        <input type="hidden" name="setup" value="1">
        <button type="submit" class="btn btn-primary">Chạy Setup (Tạo Tables + Data Mẫu)</button>
    </form>
    <p class="mt-3">Sau khi chạy, kiểm tra <a href="index.php">trang chủ</a> để xem data load từ DB.</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>