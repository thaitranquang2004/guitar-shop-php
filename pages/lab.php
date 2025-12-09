<?php
include '../config/database.php'; 
include '../includes/header.php';
?>
<div style="max-width: 1200px; margin: 0 auto; padding: 20px">
    <h1 style="text-align: center; color: #333; padding-top: 70px;padding-bottom: 70px">LAB THỰC HÀNH</h1>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-top: 20px;">
        <!-- Lab 1: HTML -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 1</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab1.3</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab1.4</a></li>
            </ul>
        </div>

        <!-- Lab 2: Table & List -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 2</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab2.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab2.2</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab2.3</a></li>
            </ul>
        </div>

        <!-- Lab 3: Frame & Form -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 3</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab3.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab3.2</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab3.3</a></li>
            </ul>
        </div>

        <!-- Lab 4: CSS cơ bản -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 4</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab4.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab4.2</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab4.3</a></li>
            </ul>
        </div>

        <!-- Lab 5: Cấu trúc CSS -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 5</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab5.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab5.2</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab5.3</a></li>
            </ul>
        </div>

        <!-- Lab 6: Xây dựng layout responsive bằng CSS -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 6</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab6.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab6.2</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab6.3</a></li>
            </ul>
        </div>

        <!-- Lab 7: Javascript -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 7</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab7.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab7.2</a></li>
            </ul>
        </div>

        <!-- Lab 8: Javascript nâng cao -->
        <div style="background: white; border: 2px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h3 style="margin: 0 0 10px; color: #ac0000ff;">Lab 8</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab8.1</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab8.2</a></li>
                <li style="margin: 5px 0; background: #e9ecef; padding: 5px; border-radius: 4px;"><a href="<?php echo BASE_URL; ?>pages/shop.php" style="text-decoration: none; color: #007bff; font-weight: bold;">Lab8.3</a></li>
            </ul>
        </div>
    </div>
</div>
<?php include '../includes/footer.php'; ?>