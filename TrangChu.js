// Khai báo chung
let img = []; // Khai báo mảng chứa ảnh
let index = 0; // Chỉ số ảnh hiện tại
let autoSlideInterval = null; // Biến lưu interval cho auto slide

// Tải ảnh vào mảng
for (let i = 0; i < 2; i++) {
  img[i] = new Image();
  img[i].src = `Ảnh/${i}.png`; // Đường dẫn ảnh
}

// Hiển thị ảnh đầu tiên khi trang được tải
window.onload = function () {
    updateImage(); // Cập nhật ảnh đầu tiên
};

// Nút "next"
function next() {
  index++;
  if (index >= img.length) index = 0; // Quay lại ảnh đầu tiên nếu vượt quá
  updateImage();
}

// Nút "pre"
function pre() {
  index--;
  if (index < 0) index = img.length - 1; // Quay lại ảnh cuối cùng nếu lùi quá
  updateImage();
}

// Cập nhật ảnh hiển thị
function updateImage() {
  const anh = document.getElementById("anh");
  if (anh) {
    anh.src = img[index].src;
    document.getElementById("soanh").innerText = index + 1; // Hiển thị số ảnh (bắt đầu từ 1)
  }
}

// Nút "auto"
function auto() {
  if (!autoSlideInterval) {
    autoSlideInterval = setInterval(next, 5000); // Tự động chuyển ảnh 5 giây
  }
}
