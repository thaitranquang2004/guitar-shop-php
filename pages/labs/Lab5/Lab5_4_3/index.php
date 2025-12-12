<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký thành viên</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; box-shadow: 0 0 10px #eee; }
        .form-group { margin-bottom: 15px; }
        label { display: inline-block; width: 150px; font-weight: bold; vertical-align: top; }
        .error-msg { color: red; margin-bottom: 10px; }
        .success-msg { color: green; border: 1px solid green; padding: 10px; background: #eaffea; }
        .required { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h2>ĐĂNG KÝ THÀNH VIÊN</h2>

    <?php
   
    $errors = array(); 
    $success = false; 
    $data = array();   

  
    $listTinh = array(
        "" => "-- Chọn tỉnh --",
        "hanoi" => "Hà Nội",
        "hcm" => "TP. Hồ Chí Minh",
        "danang" => "Đà Nẵng",
        "cantho" => "Cần Thơ"
    );

 
    if (isset($_POST['btnRegister'])) {
        
       
        $username = isset($_POST['txtUser']) ? trim($_POST['txtUser']) : '';
        $pass = isset($_POST['txtPass']) ? $_POST['txtPass'] : '';
        $repass = isset($_POST['txtRepass']) ? $_POST['txtRepass'] : '';
        $gender = isset($_POST['radGender']) ? $_POST['radGender'] : '';
        $hobbies = isset($_POST['chkHobby']) ? $_POST['chkHobby'] : array(); // Mảng sở thích
        $province = isset($_POST['slProvince']) ? $_POST['slProvince'] : '';

       

      
        if (empty($username)) {
            $errors[] = "Vui lòng nhập Tên đăng nhập.";
        }

     
        if (empty($pass)) {
            $errors[] = "Vui lòng nhập Mật khẩu.";
        }

      
        if (empty($repass)) {
            $errors[] = "Vui lòng Nhập lại mật khẩu.";
        } elseif ($pass != $repass) {
            $errors[] = "Mật khẩu nhập lại không trùng khớp.";
        }

      
        if (empty($gender)) {
            $errors[] = "Vui lòng chọn Giới tính.";
        }

        
        if (empty($province)) {
            $errors[] = "Vui lòng chọn Tỉnh thành.";
        }

        
        $fileName = "";
        if (isset($_FILES['fileAvatar']) && $_FILES['fileAvatar']['error'] == 0) {
            $allowed = array('jpg', 'png', 'bmp', 'gif');
            $fileInfo = pathinfo($_FILES['fileAvatar']['name']);
            $fileExt = strtolower($fileInfo['extension']);

            if (!in_array($fileExt, $allowed)) {
                $errors[] = "File hình ảnh không hợp lệ. Chỉ chấp nhận: .jpg, .png, .bmp, .gif";
            } else {
                $fileName = $_FILES['fileAvatar']['name'];
            }
        }

      
        if (empty($errors)) {
            $success = true;
           
            $data['username'] = $username;
            $data['gender'] = ($gender == 'male') ? 'Nam' : 'Nữ';
            $data['hobbies'] = implode(", ", $hobbies); 
            $data['province'] = $listTinh[$province];
            $data['avatar'] = $fileName;
        }
    }
    ?>

    <?php if (!empty($errors)): ?>
        <div class="error-msg">
            <b>Dữ liệu nhập vào không hợp lệ:</b>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo $err; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-msg">
            <h3>Đăng ký thành công!</h3>
            <p><b>Tên đăng nhập:</b> <?php echo $data['username']; ?></p>
            <p><b>Giới tính:</b> <?php echo $data['gender']; ?></p>
            <p><b>Sở thích:</b> <?php echo empty($data['hobbies']) ? "Không có" : $data['hobbies']; ?></p>
            <p><b>Tỉnh thành:</b> <?php echo $data['province']; ?></p>
            <p><b>Hình ảnh:</b> <?php echo empty($data['avatar']) ? "Chưa upload" : $data['avatar']; ?></p>
        </div>
        <br>
        <a href="lab5_4_3.php">Quay lại form đăng ký</a>
    <?php else: ?>
        <form action="" method="post" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Tên đăng nhập <span class="required">(*)</span>:</label>
                <input type="text" name="txtUser" value="<?php echo isset($_POST['txtUser']) ? $_POST['txtUser'] : ''; ?>">
            </div>

            <div class="form-group">
                <label>Mật khẩu <span class="required">(*)</span>:</label>
                <input type="password" name="txtPass">
            </div>

            <div class="form-group">
                <label>Nhập lại MK <span class="required">(*)</span>:</label>
                <input type="password" name="txtRepass">
            </div>

            <div class="form-group">
                <label>Giới tính <span class="required">(*)</span>:</label>
                <input type="radio" name="radGender" value="male" <?php echo (isset($_POST['radGender']) && $_POST['radGender']=='male')?'checked':'' ?>> Nam
                <input type="radio" name="radGender" value="female" <?php echo (isset($_POST['radGender']) && $_POST['radGender']=='female')?'checked':'' ?>> Nữ
            </div>

            <div class="form-group">
                <label>Sở thích:</label>
                <input type="checkbox" name="chkHobby[]" value="Đọc sách"> Đọc sách
                <input type="checkbox" name="chkHobby[]" value="Du lịch"> Du lịch
                <input type="checkbox" name="chkHobby[]" value="Thể thao"> Thể thao
            </div>

            <div class="form-group">
                <label>Hình ảnh:</label>
                <input type="file" name="fileAvatar">
            </div>

            <div class="form-group">
                <label>Tỉnh thành <span class="required">(*)</span>:</label>
                <select name="slProvince">
                    <?php 
                        foreach($listTinh as $key => $val){
                            $selected = (isset($_POST['slProvince']) && $_POST['slProvince'] == $key) ? 'selected' : '';
                            echo "<option value='$key' $selected>$val</option>";
                        }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label></label>
                <input type="submit" name="btnRegister" value="Đăng ký">
                <input type="reset" value="Nhập lại">
            </div>

        </form>
    <?php endif; ?>

</div>

</body>
</html>