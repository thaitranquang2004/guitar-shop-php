<pre>
<?php

$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ],
]);

$url = "https://vnexpress.net/the-thao";
$content = file_get_contents($url, false, $context);

if ($content) {
  
    $pattern = '/<h3 class="title-news">.*?<a.*?>(.*?)<\/a>.*?<\/h3>/ims';

    preg_match_all($pattern, $content, $matches);

    echo "<h3>Danh sách tiêu đề tin thể thao lấy được:</h3>";
    

    print_r($matches[1]); 
} else {
    echo "Lỗi kết nối.";
}
?>
</pre>