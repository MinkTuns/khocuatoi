// ================================================
// BOOKSTORE - MAIN JAVASCRIPT
// ================================================

/**
 * Hàm khởi tạo khi tài liệu tải xong
 */
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các tính năng
    initAlertsAutoClose();
    initFormValidation();
    initMobileMenu();
});

/**
 * Tự động đóng alert messages sau 5 giây
 */
function initAlertsAutoClose() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.display = 'none';
        }, 5000);
    });
}

/**
 * Xác nhận trước khi xóa
 */
function confirmDelete(message = 'Bạn chắc chắn muốn xóa?') {
    return confirm(message);
}

/**
 * Format tiền tệ
 */
function formatCurrency(value) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(value);
}

/**
 * Validate form cơ bản
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Vui lòng điền đầy đủ các trường bắt buộc');
            }
        });
        
        // Xóa lỗi khi user focus vào field
        form.querySelectorAll('[required]').forEach(field => {
            field.addEventListener('focus', function() {
                this.style.borderColor = '';
            });
        });
    });
}

/**
 * Toggle mobile menu
 */
function initMobileMenu() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navbar = document.querySelector('.navbar');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            navbar.classList.toggle('active');
        });
    }
}

/**
 * Thêm vào giỏ hàng (AJAX - nâng cao)
 */
function addToCart(bookId, quantity = 1) {
    const formData = new FormData();
    formData.append('book_id', bookId);
    formData.append('quantity', quantity);
    
    fetch('?page=add_to_cart', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Thêm vào giỏ hàng thành công!', 'success');
            updateCartCount();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Có lỗi xảy ra', 'error');
    });
}

/**
 * Hiển thị notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.maxWidth = '300px';
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

/**
 * Cập nhật số lượng giỏ hàng
 */
function updateCartCount() {
    // Có thể AJAX để lấy số lượng mới từ server
    // hoặc reload trang
    location.reload();
}

/**
 * Xử lý tìm kiếm (search optimization)
 */
const searchInput = document.querySelector('input[name="keyword"]');
if (searchInput) {
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        // Có thể thêm xử lý tìm kiếm thời gian thực ở đây
    });
}

/**
 * Toggle dropdown menu
 */
function toggleDropdown(element) {
    const menu = element.nextElementSibling;
    if (menu) {
        menu.classList.toggle('active');
    }
}

/**
 * Xử lý input tiền tệ
 */
function initCurrencyInputs() {
    const currencyInputs = document.querySelectorAll('input[data-type="currency"]');
    
    currencyInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const value = parseInt(this.value);
            if (!isNaN(value)) {
                this.value = formatCurrency(value);
            }
        });
    });
}

/**
 * Validate email
 */
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Validate phone
 */
function validatePhone(phone) {
    const regex = /^(\+84|0)[0-9]{9,10}$/;
    return regex.test(phone);
}

/**
 * Toggle password visibility
 */
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

/**
 * Xử lý submit form async
 */
function submitFormAsync(formId) {
    const form = document.getElementById(formId);
    
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const action = form.getAttribute('action');
        
        fetch(action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(html => {
            // Process response
            console.log('Form submitted successfully');
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Có lỗi xảy ra', 'error');
        });
    });
}

/**
 * Sidebar toggle cho mobile
 */
function toggleSidebar() {
    const sidebar = document.querySelector('.products-sidebar');
    if (sidebar) {
        sidebar.classList.toggle('active');
    }
}

/**
 * Print order
 */
function printOrder() {
    window.print();
}

/**
 * Copy text to clipboard
 */
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showNotification('Đã sao chép!', 'success');
    }).catch(err => {
        console.error('Lỗi:', err);
    });
}

// ================================================
// Export functions (nếu sử dụng modules)
// ================================================
// Hàm có thể được sử dụng từ HTML như: onclick="functionName()"
