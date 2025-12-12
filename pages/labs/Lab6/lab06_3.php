<?php

function postIndex($index, $value="")
{
    if (!isset($_POST[$index])) return $value;
    return trim($_POST[$index]);
}


function checkUserName($string)
{

    if (preg_match("/^[a-zA-Z0-9._-]{5,}$/", $string)) 
        return true;
    return false;
}


function checkPassword($string)
{
  
    if (preg_match("/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/", $string))
        return true;
    return false;
}

function checksdt($string)
{
   
    if (preg_match("/^[0-9]{10,11}$/", $string)) {
        return true;
    }
    return false;
}


function formatNgaysinh($date)
{
    
    if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])[\/-](0[1-9]|1[0-2])[\/-][0-9]{4}$/", $date))
        return true;
    return false;
}


function checkEmail($string)
{
    if (preg_match("/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/", $string))
        return true;
    return false;
}


$sm = postIndex("submit");
$username = postIndex("username");
$password = postIndex("password");
$email = postIndex("email");
$phone = postIndex("phone");
$birthday = postIndex("date");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Đăng ký thông tin</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7fc; margin: 0; padding: 0; }
        .container { max-width: 800px; margin: 50px auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { text-align: center; color: #4caf50; }
        fieldset { border: none; margin-bottom: 20px; }
        label { font-weight: bold; margin-bottom: 5px; display: inline-block; color: #333; }
        input[type="text"], input[type="password"], input[type="email"], input[type="date"], input[type="file"] { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 16px; box-sizing: border-box; }
        input[type="submit"] { background-color: #4caf50; color: white; border: none; padding: 12px 20px; font-size: 16px; cursor: pointer; border-radius: 5px; width: 100%; }
        input[type="submit"]:hover { background-color: #45a049; }
        .error-msg { background-color: #f9d6d5; color: #d9534f; padding: 10px; border-radius: 5px; margin-top: 20px; }
        .success-msg { background-color: #dff0d8; color: #3c763d; padding: 10px; border-radius: 5px; margin-top: 20px; border: 1px solid #d6e9c6; }
        .checkbox-container { display: flex; align-items: center; }
        .checkbox-container input { margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Đăng ký thông tin</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <fieldset>
                <label for="username">UserName</label>
                <input type="text" name="username" id="username" value="<?php echo $username; ?>" placeholder="Nhập tên đăng nhập" required />
            </fieldset>
            <fieldset>
                <label for="password">Mật khẩu</label>
                <input type="password" name="password" id="password" placeholder="Tối thiểu 8 ký tự, 1 hoa, 1 thường, 1 số" required />
            </fieldset>
            <fieldset>
                <div class="checkbox-container">
                    <input type="checkbox" onclick="displayPass()" />
                    <label>Hiển thị mật khẩu</label>
                </div>
            </fieldset>
            <fieldset>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo $email; ?>" placeholder="example@mail.com" required />
            </fieldset>
            <fieldset>
                <label for="date">Ngày sinh</label>
                <input type="text" name="date" id="date" value="<?php echo $birthday; ?>" placeholder="dd/mm/yyyy hoặc dd-mm-yyyy" required />
            </fieldset>
            <fieldset>
                <label for="phone">Điện thoại</label>
                <input type="text" name="phone" id="phone" value="<?php echo $phone; ?>" placeholder="Nhập số điện thoại" required />
            </fieldset>
            <fieldset>
                <input type="submit" value="Đăng ký" name="submit">
            </fieldset>
        </form>

        <script>
            function displayPass() {
                var passwordField = document.getElementById('password');
                var checkbox = document.querySelector('input[type="checkbox"]');
                if (checkbox.checked) {
                    passwordField.type = 'text';
                } else {
                    passwordField.type = 'password';
                }
            }
        </script>

        <?php
        if ($sm != "") {
            $errors = ""; // Chuỗi chứa lỗi

            if (!checkUserName($username)) {
                $errors .= "- Username không hợp lệ (ít nhất 5 ký tự, không chứa ký tự đặc biệt ngoài . _ -)<br>";
            } 
            
            if (!checkPassword($password)) {
                $errors .= "- Mật khẩu phải có tối thiểu 8 ký tự, 1 chữ hoa, 1 chữ thường và 1 số.<br>";
            } 
            
            if (!checkEmail($email)) {
                $errors .= "- Định dạng Email không hợp lệ.<br>";
            } 
            
            if (!checksdt($phone)) {
                $errors .= "- Số điện thoại phải là số (10-11 số).<br>";
            } 
            
            if (!formatNgaysinh($birthday)) {
                $errors .= "- Ngày sinh phải nhập theo định dạng dd/mm/yyyy hoặc dd-mm-yyyy (Ví dụ: 20/10/2000).<br>";
            }

            
            if ($errors != "") {
                echo '<div class="error-msg"><b>Vui lòng kiểm tra lại:</b><br>' . $errors . '</div>';
            } else {
                echo '<div class="success-msg"><b>Đăng ký thành công!</b><br>';
                echo "Xin chào, $username</div>";
            }
        }
        ?>
    </div>
</body>
</html>