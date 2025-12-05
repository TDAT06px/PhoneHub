-- Xóa cơ sở dữ liệu cũ nếu tồn tại và tạo cơ sở dữ liệu mới
DROP DATABASE IF EXISTS `DoAnCoSo2`;
CREATE DATABASE `DoAnCoSo2` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `DoAnCoSo2`;

-- --------------------------------------------------------
-- XÓA BẢNG THEO THỨ TỰ (CON TRƯỚC, CHA SAU)
-- --------------------------------------------------------
DROP TABLE IF EXISTS `binhluan`;
DROP TABLE IF EXISTS `donhang_chitiet`;
DROP TABLE IF EXISTS `sanpham`;
DROP TABLE IF EXISTS `donhang`;
DROP TABLE IF EXISTS `danhmuc`;
DROP TABLE IF EXISTS `nguoidung`;

-- --------------------------------------------------------
-- Bảng: nguoidung (Tạo trước)
-- --------------------------------------------------------
CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `so_dien_thoai` varchar(15) NULL UNIQUE,
  `mat_khau` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `gioi_tinh` enum('Nam','Nữ','Khác') NULL,
  `ngay_sinh` date NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu: nguoidung
INSERT INTO `nguoidung` (`id`, `ho_ten`, `email`, `mat_khau`, `role`) VALUES
(1, 'Admin', 'admin@shop.com', '$2y$10$fA.5.GL8.7.YmF2pb.OMz.IdRGHfF9.c.dJkWyBY/Tj91yD10fKjO', 'admin'),
(2, 'Nguyễn Văn A', 'nguyenvana@gmail.com', '$2y$10$fA.5.GL8.7.YmF2pb.OMz.IdRGHfF9.c.dJkWyBY/Tj91yD10fKjO', 'user'),
(3, 'TienDat', 'tiendat@gmail.com', '$2b$10$AVhA91wKb0CTRySn5amBO.nYSrrA.bMEeIb5ofnM1dL2o0z8XEafO', 'user'),
(4, 'TienDat Admin', 'Tiendat@gmail', '$2b$10$AVhA91wKb0CTRySn5amBO.nYSrrA.bMEeIb5ofnM1dL2o0z8XEafO', 'admin');

-- --------------------------------------------------------
-- Bảng: danhmuc (Tạo trước sanpham)
-- --------------------------------------------------------
CREATE TABLE `danhmuc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NULL DEFAULT NULL,
  `ten_danhmuc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `fk_danhmuc_parent` FOREIGN KEY (`parent_id`) REFERENCES `danhmuc` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu: danhmuc (Thêm danh mục cha và con)
INSERT INTO `danhmuc` (`id`, `parent_id`, `ten_danhmuc`) VALUES
(1, NULL, 'Điện thoại'),
(2, NULL, 'Laptop'),
(3, NULL, 'Phụ kiện'),
(4, 1, 'SamSung'),
(5, 1, 'iPhone'),
(6, 1, 'Xiaomi'),
(7, 2, 'Macbook'),
(8, 2, 'Dell'),
(9, 2, 'HP'),
(10, 2, 'Asus'),
(11, 3, 'Sạc & Cáp'),
(12, 3, 'Tai nghe'),
(13, 3, 'Chuột & Bàn phím');

-- --------------------------------------------------------
-- Bảng: donhang (Tạo trước donhang_chitiet)
-- --------------------------------------------------------
CREATE TABLE `donhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nguoidung` int(11) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `tong_tien` decimal(12,0) NOT NULL,
  `trang_thai` varchar(50) NOT NULL DEFAULT 'Thanh toán thành công',
  PRIMARY KEY (`id`),
  KEY `id_nguoidung` (`id_nguoidung`),
  CONSTRAINT `fk_donhang_nguoidung` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Bảng: sanpham
-- (Đã gộp TẤT CẢ các cột mới)
-- --------------------------------------------------------
CREATE TABLE `sanpham` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ten_sanpham` varchar(255) NOT NULL,
  `gia` decimal(12,0) NOT NULL DEFAULT 0,
  `avg_rating` decimal(3,1) NOT NULL DEFAULT 0.0,
  `luot_xem` int(11) NOT NULL DEFAULT 0,
  `hinhanh` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `thong_so_ky_thuat` text DEFAULT NULL,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `id_danhmuc` int(11) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_danhmuc` (`id_danhmuc`),
  CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`id_danhmuc`) REFERENCES `danhmuc` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu: sanpham
-- (Đã sửa lỗi 1. thành 1,)
INSERT INTO `sanpham` (`id`, `ten_sanpham`, `gia`, `hinhanh`, `mo_ta`, `so_luong_ton`, `id_danhmuc`) VALUES
(1, 'iPhone 15 Pro Max 256GB', 32000000, 'ip17.jpg', 'iPhone 15 Pro Max, chiếc điện thoại đỉnh cao...', 50, 5),
(2, 'MacBook Air M3 13-inch', 28000000, 'macbookpro.webp', 'MacBook Air M3 siêu mỏng nhẹ...', 30, 7),
(3, 'Samsung Galaxy S24 Ultra', 29500000, 's23.jpg', 'Trải nghiệm Galaxy AI trên S24 Ultra...', 40, 4),
(4, 'Chuột không dây Logitech MX Master 3S', 2300000, 'chuot.webp', 'Chuột không dây công thái học cao cấp...', 100, 13),
(5, 'Laptop Dell XPS 15', 45000000, 'dell-xps.jpg', 'Laptop cao cấp với màn hình InfinityEdge OLED...', 20, 8);

-- --------------------------------------------------------
-- Bảng: binhluan
-- --------------------------------------------------------
CREATE TABLE `binhluan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanpham` int(11) NOT NULL,
  `id_nguoidung` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `danh_gia` tinyint(1) NOT NULL COMMENT 'Từ 1 đến 5 (sao)',
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_sanpham` (`id_sanpham`),
  KEY `id_nguoidung` (`id_nguoidung`),
  CONSTRAINT `fk_binhluan_nguoidung` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_binhluan_sanpham` FOREIGN KEY (`id_sanpham`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CHECK (`danh_gia` >= 1 AND `danh_gia` <= 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu: binhluan
INSERT INTO `binhluan` (`id`, `id_sanpham`, `id_nguoidung`, `noi_dung`, `danh_gia`, `ngay_tao`) VALUES
(1, 1, 2, 'Sản phẩm tuyệt vời! Camera chụp ảnh quá đẹp. Rất đáng tiền.', 5, '2025-11-14 10:30:00'),
(2, 2, 2, 'Macbook dùng mượt, pin trâu, mỏng nhẹ. Rất hài lòng với sản phẩm này.', 5, '2025-11-15 02:00:00');

-- --------------------------------------------------------
-- Bảng: donhang_chitiet
-- --------------------------------------------------------
CREATE TABLE `donhang_chitiet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_donhang` int(11) NOT NULL,
  `id_sanpham` int(11) NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia_luc_mua` decimal(12,0) NOT NULL COMMENT 'Lưu lại giá tại thời điểm mua',
  PRIMARY KEY (`id`),
  KEY `id_donhang` (`id_donhang`),
  KEY `id_sanpham` (`id_sanpham`),
  CONSTRAINT `fk_chitiet_donhang` FOREIGN KEY (`id_donhang`) REFERENCES `donhang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_chitiet_sanpham` FOREIGN KEY (`id_sanpham`) REFERENCES `sanpham` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- CẬP NHẬT DỮ LIỆU MẪU (RATING VÀ THÔNG SỐ)
-- --------------------------------------------------------

-- --------------------------------------------------------
-- KIỂM TRA DỮ LIỆU ĐÃ INSERT
-- --------------------------------------------------------
SELECT * FROM `nguoidung`;
SELECT * FROM `danhmuc`;
SELECT * FROM `donhang`;
SELECT * FROM `sanpham`;
SELECT * FROM `binhluan`;
SELECT * FROM `donhang_chitiet`;