<?php

function postIndex($index, $value="")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}


$username   = postIndex("username");
$password1  = postIndex("password1");
$password2  = postIndex("password2");
$name       = postIndex("name");
$thong_tin  = postIndex("thong_tin");
$sm         = postIndex("submit");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lab6_1 - Xử lý chuỗi và Mã hóa</title>
<style>
    fieldset { width: 50%; margin: 50px auto; border: 1px solid #ccc; padding: 20px; }
    legend { font-weight: bold; color: #006; }
    .info { width: 600px; color: #333; background: #e8f0fe; margin: 20px auto; padding: 15px; border: 1px solid #b8d0f5; }
    .error { color: red; margin-bottom: 10px; }
    textarea { width: 98%; height: 100px; }
    input[type="text"], input[type="password"] { width: 98%; padding: 5px; }
    td { padding: 5px; }
</style>
</head>

<body>

<fieldset>
    <legend>Thông tin đăng ký</legend>
    <form action="" method="post" enctype="multipart/form-data">
        <table width="100%">
            <tr>
                <td width="30%">Tên đăng nhập:</td>
                <td><input type="text" name="username" value="<?php echo $username;?>"></td>
            </tr>
            <tr>
                <td>Mật khẩu:</td>
                <td><input type="password" name="password1" /></td>
            </tr>
             <tr>
                <td>Nhập lại mật khẩu:</td>
                <td><input type="password" name="password2" /></td>
            </tr>
            <tr>
                <td>Họ tên:</td>
                <td><input type="text" name="name" value="<?php echo $name;?>" /></td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Thông tin:</td>
                <td>
                    <textarea name="thong_tin"><?php echo $thong_tin; ?></textarea>
                </td>
            </tr>
            <tr>
                <td colspan="2" align="center">
                    <br>
                    <input type="submit" value="Đăng ký" name="submit">
                </td>
            </tr>
        </table>
    </form>
</fieldset>

<?php
if ($sm != "")
{
    $err = "";

    if (strlen($username) < 6) 
        $err .= "Username ít nhất phải 6 ký tự!<br>";
    if ($password1 != $password2) 
        $err .= "Mật khẩu và mật khẩu nhập lại không khớp.<br>";
    if (strlen($password1) < 8) 
        $err .= "Mật khẩu phải ít nhất 8 ký tự.<br>";
    if (str_word_count($name) < 2) 
        $err .= "Họ tên phải chứa ít nhất 2 từ.<br>";

  
    ?>
    <div class="info">
        <?php 
        if ($err != "") {
            echo "<div class='error'>$err</div>";
        } else {
            echo "<h3>Đăng ký thành công!</h3>";
            echo "<b>Username:</b> $username <br><hr>";
            $pass_sha1 = sha1($password1);
          
            $pass_combined = sha1(md5($password1));

            echo "<b>Mật khẩu (SHA1):</b> " . $pass_sha1 . "<br>";
            echo "<b>Mật khẩu (SHA1 lồng MD5):</b> " . $pass_combined . "<br>";
            echo "<hr>";

            echo "<b>Họ tên:</b> " . ucwords(strtolower($name)) . "<br>";
            echo "<b>Thông tin gốc:</b> " . htmlspecialchars($thong_tin) . "<br><br>";

           
            $step1 = strip_tags($thong_tin);
            echo "<i>1. Sau khi strip_tags (loại bỏ HTML):</i> " . $step1 . "<br>";

            $step2 = nl2br($step1);
            echo "<i>2. Sau khi nl2br (xuống dòng):</i> " . $step2 . "<br>";
            $step3 = addslashes($step2);
            echo "<i>3. Sau khi addslashes (thêm \ trước '):</i> " . htmlspecialchars($step3) . "<br>";
            $step4 = stripslashes($step3);
            echo "<i>4. Sau khi stripslashes (bỏ \ ):</i> " . $step4 . "<br>";
        }
        ?>
    </div>
    <?php
}
?>

</body>
</html>