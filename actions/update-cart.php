<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = (int)$_POST['id'];
    $quantity = (int)$_POST['quantity'];

    if (isset($_SESSION['cart'][$id])) {
        if ($quantity > 0) {
            $_SESSION['cart'][$id]['quantity'] = $quantity;
        } else {
            unset($_SESSION['cart'][$id]);
        }
    }
}
header('Location: ../pages/cart.php');
exit;
?>
