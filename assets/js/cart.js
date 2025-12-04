// Sử dụng Fetch API để update cart mà không reload
function updateCartCount() {
    fetch('get-cart-count.php')  // Tạo file này để return count via JSON
        .then(response => response.json())
        .then(data => {
            document.querySelector('.nav-link[href="cart.php"]').textContent = `Giỏ Hàng (${data.count})`;
        });
}
// Gọi khi thêm sản phẩm
document.addEventListener('DOMContentLoaded', updateCartCount);