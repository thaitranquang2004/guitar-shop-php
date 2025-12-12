<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lấy tin tức từ VnExpress</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .news-item { border-bottom: 1px solid #ccc; padding: 10px 0; }
        .news-item a { text-decoration: none; color: #333; font-weight: bold; font-size: 18px; }
        .news-item a:hover { color: #006064; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Tin tức Thể thao (Crawl từ VnExpress)</h1>

<?php

$url = "https://vnexpress.net/the-thao";


$content = @file_get_contents($url);

if ($content === FALSE) {
    echo "<div class='error'>Không thể lấy dữ liệu. Hãy kiểm tra lại kết nối mạng hoặc cấu hình allow_url_fopen/openssl.</div>";
} else {

    $pattern = '/<h3 class="title-news">.*?<a.*?href="(.*?)".*?>(.*?)<\/a>.*?<\/h3>/ms';
    
    preg_match_all($pattern, $content, $matches);
    
    if (!empty($matches[2])) {
        echo "<p>Tìm thấy <b>" . count($matches[2]) . "</b> tin tức:</p>";
        
        foreach ($matches[2] as $key => $title) {
            $link = $matches[1][$key];
            
        
            $title = trim(strip_tags($title));
            
            echo "<div class='news-item'>";
            echo "<a href='$link' target='_blank'>$title</a>";
            echo "</div>";
        }
    } else {
        echo "Không tìm thấy tin tức nào khớp mẫu (Có thể web nguồn đã đổi cấu trúc).";
    }
    
    
}
?>

</body>
</html>