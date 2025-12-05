-- ========================================================
-- 1. KHỞI TẠO CƠ SỞ DỮ LIỆU
-- ========================================================
DROP DATABASE IF EXISTS `DoAnCoSo2`;
CREATE DATABASE `DoAnCoSo2` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `DoAnCoSo2`;

-- ========================================================
-- 2. TẠO CẤU TRÚC BẢNG
-- ========================================================

-- Bảng Người dùng (Đã cập nhật cho Admin Panel)
-- Thêm: role 'staff', cột 'trang_thai'
CREATE TABLE `nguoidung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ho_ten` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `so_dien_thoai` varchar(15) NULL,
  `mat_khau` varchar(255) NOT NULL,
  `role` enum('admin','staff','user') NOT NULL DEFAULT 'user', -- Đã thêm quyền staff
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Active, 0: Blocked', -- Đã thêm trạng thái
  `gioi_tinh` enum('Nam','Nữ','Khác') NULL DEFAULT 'Khác',
  `ngay_sinh` date NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Danh mục
CREATE TABLE `danhmuc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NULL DEFAULT NULL,
  `ten_danhmuc` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `fk_danhmuc_parent` FOREIGN KEY (`parent_id`) REFERENCES `danhmuc` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Sản phẩm (Có quản lý tồn kho: so_luong_ton)
CREATE TABLE `sanpham` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ten_sanpham` varchar(255) NOT NULL,
  `gia` decimal(12,0) NOT NULL DEFAULT 0,
  `hinhanh` varchar(255) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `thong_so_ky_thuat` text DEFAULT NULL,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `luot_xem` int(11) NOT NULL DEFAULT 0,
  `avg_rating` float DEFAULT 0, 
  `id_danhmuc` int(11) DEFAULT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_danhmuc` (`id_danhmuc`),
  CONSTRAINT `fk_sanpham_danhmuc` FOREIGN KEY (`id_danhmuc`) REFERENCES `danhmuc` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Đơn hàng (Quản lý doanh thu)
CREATE TABLE `donhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_nguoidung` int(11) NOT NULL,
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  `tong_tien` decimal(12,0) NOT NULL,
  `trang_thai` varchar(50) NOT NULL DEFAULT 'Chờ xử lý',
  PRIMARY KEY (`id`),
  KEY `id_nguoidung` (`id_nguoidung`),
  CONSTRAINT `fk_donhang_nguoidung` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Chi tiết đơn hàng
CREATE TABLE `donhang_chitiet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_donhang` int(11) NOT NULL,
  `id_sanpham` int(11) NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia_luc_mua` decimal(12,0) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_donhang` (`id_donhang`),
  KEY `id_sanpham` (`id_sanpham`),
  CONSTRAINT `fk_chitiet_donhang` FOREIGN KEY (`id_donhang`) REFERENCES `donhang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_chitiet_sanpham` FOREIGN KEY (`id_sanpham`) REFERENCES `sanpham` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng Bình luận
CREATE TABLE `binhluan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_sanpham` int(11) NOT NULL,
  `id_nguoidung` int(11) NOT NULL,
  `noi_dung` text NOT NULL,
  `danh_gia` tinyint(1) NOT NULL CHECK (`danh_gia` >= 1 AND `danh_gia` <= 5),
  `ngay_tao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_sanpham` (`id_sanpham`),
  KEY `id_nguoidung` (`id_nguoidung`),
  CONSTRAINT `fk_binhluan_nguoidung` FOREIGN KEY (`id_nguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_binhluan_sanpham` FOREIGN KEY (`id_sanpham`) REFERENCES `sanpham` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ========================================================
-- 3. THÊM DỮ LIỆU MẪU (SEEDING)
-- ========================================================

-- A. Người dùng (Mật khẩu mặc định là: 12345)
INSERT INTO `nguoidung` (`id`, `ho_ten`, `email`, `mat_khau`, `role`, `trang_thai`) VALUES
(1, 'Admin Quản Trị', 'admin@gmail.com', '$2y$10$fA.5.GL8.7.YmF2pb.OMz.IdRGHfF9.c.dJkWyBY/Tj91yD10fKjO', 'admin', 1),
(2, 'Khách Hàng A', 'user@gmail.com', '$2y$10$fA.5.GL8.7.YmF2pb.OMz.IdRGHfF9.c.dJkWyBY/Tj91yD10fKjO', 'user', 1),
(3, 'Nhân Viên Kho', 'staff@gmail.com', '$2y$10$fA.5.GL8.7.YmF2pb.OMz.IdRGHfF9.c.dJkWyBY/Tj91yD10fKjO', 'staff', 1),
(4, 'User Bị Khóa', 'block@gmail.com', '$2y$10$fA.5.GL8.7.YmF2pb.OMz.IdRGHfF9.c.dJkWyBY/Tj91yD10fKjO', 'user', 0);

-- B. Danh mục (Cha & Con)
INSERT INTO `danhmuc` (`id`, `parent_id`, `ten_danhmuc`) VALUES
(1, NULL, 'Điện thoại'),
(2, NULL, 'Laptop'),
(3, NULL, 'Phụ kiện'),
(4, 1, 'iPhone'),
(5, 1, 'Samsung'),
(6, 1, 'Xiaomi'),
(7, 1, 'Oppo'),
(8, 2, 'Macbook'),
(9, 2, 'Dell'),
(10, 2, 'Asus'),
(11, 2, 'HP'),
(12, 3, 'Tai nghe'),
(13, 3, 'Sạc cáp'),
(14, 3, 'Chuột & Phím');

-- C. Sản phẩm (Đầy đủ 50 sản phẩm)
INSERT INTO `sanpham` (`id`, `ten_sanpham`, `gia`, `hinhanh`, `mo_ta`, `thong_so_ky_thuat`, `so_luong_ton`, `id_danhmuc`, `avg_rating`) VALUES
-- iPhone (ID: 4)
(1, 'iPhone 15 Pro Max 256GB', 34990000, 'iphone15promax.jpg', 'Khung Titan, Chip A17 Pro mạnh mẽ nhất.', 'Màn hình: 6.7 inch OLED\nChip: A17 Pro', 50, 4, 0),
(2, 'iPhone 15 Plus 128GB', 25990000, 'iphone15plus.jpg', 'Màn hình lớn, pin trâu, Dynamic Island.', 'Màn hình: 6.7 inch OLED\nChip: A16 Bionic', 30, 4, 0),
(3, 'iPhone 14 Pro Max 128GB', 27490000, 'iphone14promax.jpg', 'Màu tím Deep Purple sang trọng.', 'Màn hình: 6.7 inch OLED\nChip: A16 Bionic', 20, 4, 0),
(4, 'iPhone 13 128GB', 13990000, 'iphone13.jpg', 'Thiết kế bền bỉ, camera kép sắc nét.', 'Màn hình: 6.1 inch OLED\nChip: A15 Bionic', 100, 4, 0),
(5, 'iPhone 11 64GB', 8990000, 'iphone11.jpg', 'Hiệu năng ổn định, giá tốt nhất.', 'Màn hình: 6.1 inch IPS LCD\nChip: A13 Bionic', 15, 4, 0),
(6, 'iPhone 12 64GB', 11990000, 'iphone12.jpg', 'Màn hình OLED, thiết kế vuông vức.', 'Màn hình: 6.1 inch OLED\nChip: A14 Bionic', 40, 4, 0),
(7, 'iPhone 15 128GB', 22990000, 'iphone15.jpg', 'Camera 48MP, cổng USB-C tiện lợi.', 'Màn hình: 6.1 inch OLED\nChip: A16 Bionic', 60, 4, 0),
(8, 'iPhone SE 2022', 10990000, 'iphonese.jpg', 'Nhỏ gọn, mạnh mẽ với chip A15.', 'Màn hình: 4.7 inch LCD\nChip: A15 Bionic', 25, 4, 0),

-- Samsung (ID: 5)
(9, 'Samsung Galaxy S24 Ultra', 29990000, 's24ultra.jpg', 'Quyền năng Galaxy AI, bút S-Pen.', 'Màn hình: 6.8 inch AMOLED\nChip: Snapdragon 8 Gen 3', 40, 5, 0),
(10, 'Samsung Galaxy Z Flip5', 16990000, 'zflip5.jpg', 'Gập mở linh hoạt, màn hình phụ lớn.', 'Màn hình: 6.7 inch AMOLED\nChip: Snapdragon 8 Gen 2', 25, 5, 0),
(11, 'Samsung Galaxy A55 5G', 9690000, 'a55.jpg', 'Thiết kế kim loại, chụp đêm ấn tượng.', 'Màn hình: 6.6 inch AMOLED\nChip: Exynos 1480', 60, 5, 0),
(12, 'Samsung Galaxy S23 FE', 11990000, 's23fe.jpg', 'Hiệu năng mạnh mẽ, giá hấp dẫn.', 'Màn hình: 6.4 inch AMOLED\nChip: Exynos 2200', 35, 5, 0),
(13, 'Samsung Galaxy M15', 4690000, 'm15.jpg', 'Pin khủng 6000mAh.', 'Màn hình: 6.5 inch AMOLED\nChip: Dimensity 6100+', 80, 5, 0),
(14, 'Samsung Galaxy Z Fold5', 30990000, 'zfold5.jpg', 'Đa nhiệm mạnh mẽ như PC.', 'Màn hình: 7.6 inch AMOLED\nChip: Snapdragon 8 Gen 2', 15, 5, 0),
(15, 'Samsung Galaxy A35 5G', 7990000, 'a35.jpg', 'Màn hình 120Hz mượt mà.', 'Màn hình: 6.6 inch AMOLED\nChip: Exynos 1380', 50, 5, 0),

-- Xiaomi (ID: 6) & Oppo (ID: 7)
(16, 'Xiaomi 14 Ultra', 29990000, 'mi14ultra.jpg', 'Đỉnh cao nhiếp ảnh Leica.', 'Màn hình: 6.73 inch AMOLED\nChip: Snapdragon 8 Gen 3', 10, 6, 0),
(17, 'Xiaomi Redmi Note 13', 4990000, 'redminote13.jpg', 'Viền siêu mỏng, camera 108MP.', 'Màn hình: 6.67 inch AMOLED\nChip: Snapdragon 685', 100, 6, 0),
(18, 'Xiaomi Poco X6 Pro', 8490000, 'pocox6.jpg', 'Chiến game đỉnh cao.', 'Màn hình: 6.67 inch AMOLED\nChip: Dimensity 8300 Ultra', 45, 6, 0),
(19, 'OPPO Find N3 Flip', 22990000, 'n3flip.jpg', 'Camera Hasselblad chuyên nghiệp.', 'Màn hình: 6.8 inch AMOLED\nChip: Dimensity 9200', 15, 7, 0),
(20, 'OPPO Reno11 F', 8990000, 'reno11f.jpg', 'Chuyên gia chân dung.', 'Màn hình: 6.7 inch AMOLED\nChip: Dimensity 7050', 55, 7, 0),

-- Laptop (ID: 8, 9, 10, 11)
(21, 'MacBook Air M3 13"', 27990000, 'macairm3.jpg', 'Chip M3, mỏng nhẹ, pin 18h.', 'CPU: M3\nRAM: 8GB\nSSD: 256GB', 25, 8, 0),
(22, 'MacBook Pro 14" M3 Pro', 49990000, 'macpro14.jpg', 'Hiệu năng chuyên nghiệp.', 'CPU: M3 Pro\nRAM: 18GB\nSSD: 512GB', 10, 8, 0),
(23, 'MacBook Air M1', 18490000, 'macairm1.jpg', 'Huyền thoại giá rẻ.', 'CPU: M1\nRAM: 8GB\nSSD: 256GB', 150, 8, 0),
(24, 'MacBook Pro 16" M3 Max', 89990000, 'macpro16.jpg', 'Sức mạnh không giới hạn.', 'CPU: M3 Max\nRAM: 36GB\nSSD: 1TB', 5, 8, 0),
(25, 'Dell XPS 13 Plus', 45000000, 'dellxps13.jpg', 'Thiết kế tương lai.', 'CPU: Core i7\nRAM: 16GB\nSSD: 512GB', 5, 9, 0),
(26, 'Dell Inspiron 15', 12490000, 'dellinspiron15.jpg', 'Bền bỉ cho sinh viên.', 'CPU: Core i5\nRAM: 8GB\nSSD: 512GB', 80, 9, 0),
(27, 'Asus ROG Strix G16', 32990000, 'rogstrixg16.jpg', 'Laptop Gaming chiến mọi game.', 'CPU: Core i7\nGPU: RTX 4050', 20, 10, 0),
(28, 'Asus Zenbook 14 OLED', 24990000, 'zenbook14.jpg', 'Màn hình OLED tuyệt đẹp.', 'CPU: Core Ultra 5\nRAM: 16GB', 30, 10, 0),
(29, 'HP Pavilion 15', 15490000, 'hppavilion15.jpg', 'Thời trang và hiệu năng.', 'CPU: Core i5\nRAM: 16GB', 40, 11, 0),
(30, 'HP Envy x360', 22990000, 'hpenvyx360.jpg', 'Xoay gập 360 độ.', 'CPU: Core i7\nRAM: 16GB', 15, 11, 0),
(31, 'MSI Gaming GF63', 14990000, 'msigf63.jpg', 'Gaming giá rẻ.', 'CPU: Core i5\nGPU: RTX 2050', 50, 10, 0),
(32, 'Acer Nitro 5', 17990000, 'acernitro5.jpg', 'Quốc dân gaming.', 'CPU: Core i5\nGPU: RTX 3050', 45, 10, 0),

-- Phụ kiện (ID: 12, 13, 14)
(33, 'AirPods Pro 2 USB-C', 5490000, 'airpodspro2.jpg', 'Chống ồn chủ động 2X.', 'Pin: 30h\nCổng: USB-C', 100, 12, 0),
(34, 'Samsung Buds2 Pro', 2990000, 'buds2pro.jpg', 'Âm thanh Hi-Fi 24bit.', 'Pin: 18h\nChống nước: IPX7', 50, 12, 0),
(35, 'Sony WH-1000XM5', 7490000, 'sonywh1000xm5.jpg', 'Chống ồn tốt nhất thế giới.', 'Pin: 30h\nChống ồn: Có', 20, 12, 0),
(36, 'Marshall Major IV', 3290000, 'marshall4.jpg', 'Thiết kế cổ điển, pin 80h.', 'Pin: 80h\nSạc không dây: Có', 35, 12, 0),
(37, 'JBL Tour Pro 2', 5990000, 'jbltourpro2.jpg', 'Hộp sạc màn hình cảm ứng.', 'Pin: 40h\nChống nước: IPX5', 15, 12, 0),
(38, 'Sạc Apple 20W', 490000, 'sac20w.jpg', 'Sạc nhanh chính hãng.', 'Công suất: 20W', 200, 13, 0),
(39, 'Sạc dự phòng Anker', 650000, 'anker10000.jpg', 'Nhỏ gọn 10000mAh.', 'Dung lượng: 10000mAh', 150, 13, 0),
(40, 'Logitech MX Master 3S', 2390000, 'mxmaster3s.jpg', 'Chuột văn phòng tốt nhất.', 'DPI: 8000\nPin: 70 ngày', 60, 14, 0),
(41, 'Keychron K2 Pro', 2690000, 'keychronk2pro.jpg', 'Bàn phím cơ custom.', 'Switch: Brown/Red/Blue', 40, 14, 0),
(42, 'Chuột Gaming G102', 390000, 'g102.jpg', 'Ngon bổ rẻ.', 'DPI: 8000\nLED: RGB', 500, 14, 0),
(43, 'Loa JBL Flip 6', 2590000, 'jblflip6.jpg', 'Âm bass mạnh mẽ.', 'Công suất: 20W\nPin: 12h', 40, 12, 0),
(44, 'Apple Watch SE', 5990000, 'applewatchse.jpg', 'Thông minh, giá hợp lý.', 'Size: 40mm/44mm', 45, 12, 0),
(45, 'Samsung Watch6', 4990000, 'galaxywatch6.jpg', 'Theo dõi sức khỏe.', 'Size: 40mm/44mm', 35, 12, 0),
(46, 'Camera Xiaomi C200', 590000, 'cameraxiao.jpg', 'Xoay 360 độ.', 'Độ phân giải: 1080p', 100, 13, 0),
(47, 'Kính Meta Quest 3', 14990000, 'metaquest3.jpg', 'Thực tế ảo đỉnh cao.', 'Dung lượng: 128GB', 10, 12, 0),
(48, 'Tay cầm PS5', 1690000, 'dualsense.jpg', 'Rung phản hồi.', 'Kết nối: Bluetooth', 50, 14, 0),
(49, 'Đế tản nhiệt Laptop', 450000, 'coolermaster.jpg', 'Mát mẻ.', 'Quạt: 160mm', 150, 14, 0),
(50, 'Bộ vệ sinh Laptop', 50000, 'bovesinh.jpg', 'Giữ thiết bị luôn sạch sẽ.', 'Gồm: 7 món', 1000, 14, 0);

-- D. Đơn hàng mẫu (Để test Dashboard và Admin Panel)
INSERT INTO `donhang` (`id`, `id_nguoidung`, `tong_tien`, `trang_thai`, `ngay_tao`) VALUES
(1, 2, 34990000, 'Đã giao', DATE_SUB(NOW(), INTERVAL 5 DAY)),
(2, 2, 25990000, 'Đã giao', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(3, 2, 13990000, 'Đã giao', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(4, 2, 29990000, 'Đã giao', DATE_SUB(NOW(), INTERVAL 20 DAY)),
(5, 2, 5490000, 'Đã giao', DATE_SUB(NOW(), INTERVAL 25 DAY)),
(6, 2, 27990000, 'Đang giao', DATE_SUB(NOW(), INTERVAL 2 DAY)),
(7, 2, 18490000, 'Đã xác nhận', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(8, 2, 8990000, 'Chờ xử lý', NOW()),
(9, 2, 22990000, 'Chờ xử lý', DATE_SUB(NOW(), INTERVAL 3 HOUR)),
(10, 2, 11990000, 'Đã hủy', DATE_SUB(NOW(), INTERVAL 7 DAY));

-- Chi tiết đơn hàng mẫu
INSERT INTO `donhang_chitiet` (`id_donhang`, `id_sanpham`, `so_luong`, `don_gia_luc_mua`) VALUES
(1, 1, 1, 34990000),
(2, 2, 1, 25990000),
(3, 4, 1, 13990000),
(4, 9, 1, 29990000),
(5, 33, 1, 5490000),
(6, 21, 1, 27990000),
(7, 23, 1, 18490000),
(8, 5, 1, 8990000),
(9, 7, 1, 22990000),
(10, 6, 1, 11990000);

-- E. Bình luận mẫu
INSERT INTO `binhluan` (`id_sanpham`, `id_nguoidung`, `noi_dung`, `danh_gia`) VALUES
(1, 1, 'Sản phẩm tuyệt vời, đáng tiền!', 5), (1, 2, 'Màu titan đẹp lắm.', 5),
(2, 1, 'Pin trâu thật sự.', 5),
(3, 2, 'Màn hình Dynamic Island thú vị.', 5),
(4, 1, 'Nhỏ gọn, vừa tay.', 4),
(5, 2, 'Giá rẻ mà dùng vẫn ngon.', 4),
(6, 1, 'Thiết kế vuông vức nam tính.', 5),
(7, 2, 'Cổng C tiện lợi.', 5),
(8, 1, 'Hơi nhỏ nhưng mạnh.', 4),
(9, 2, 'AI quá đỉnh.', 5),
(10, 1, 'Gập lại nhỏ xíu, tiện bỏ túi.', 5),
(11, 2, 'Viền kim loại sang trọng.', 4),
(12, 1, 'Chơi game mượt.', 4),
(13, 2, 'Pin dùng 2 ngày không hết.', 5),
(14, 1, 'Đa nhiệm tốt.', 5),
(15, 2, 'Màn hình đẹp.', 4),
(16, 1, 'Chụp ảnh như máy cơ.', 5),
(17, 2, 'Viền mỏng dính.', 5),
(18, 1, 'Cấu hình mạnh nhất tầm giá.', 5),
(19, 2, 'Chụp chân dung đẹp.', 5),
(20, 1, 'Sạc siêu nhanh.', 4),
(21, 2, 'Mỏng nhẹ, pin trâu.', 5),
(22, 1, 'Màn hình 120Hz mượt.', 5),
(23, 2, 'Vẫn là best choice tầm giá.', 5),
(24, 1, 'Làm đồ họa bao phê.', 5),
(25, 2, 'Thiết kế đẹp như tương lai.', 5),
(26, 1, 'Bền bỉ, chắc chắn.', 4),
(27, 2, 'Led RGB đẹp.', 5),
(28, 1, 'Màn OLED đen sâu.', 5),
(29, 2, 'Loa hay.', 4),
(30, 1, 'Xoay gập tiện.', 5),
(31, 2, 'Giá rẻ cấu hình cao.', 4),
(32, 1, 'Tản nhiệt tốt.', 4),
(33, 2, 'Chống ồn đỉnh.', 5),
(34, 1, 'Đeo êm tai.', 5),
(35, 2, 'Chống ồn tốt nhất.', 5),
(36, 1, 'Pin trâu nghe cả tuần.', 5),
(37, 2, 'Hộp sạc màn hình độc lạ.', 5),
(38, 1, 'Sạc nhanh.', 5),
(39, 2, 'Nhỏ gọn.', 5),
(40, 1, 'Chuột ngon nhất từng dùng.', 5),
(41, 2, 'Gõ đầm tay.', 5),
(42, 1, 'Led đẹp rẻ.', 4),
(43, 2, 'Bass căng.', 5),
(44, 1, 'Đủ dùng.', 4),
(45, 2, 'Màn hình sáng.', 5),
(46, 1, 'Nét căng.', 4),
(47, 2, 'Trải nghiệm thực tế ảo phê.', 5),
(48, 1, 'Rung phản hồi tốt.', 5),
(49, 2, 'Giảm nhiệt tốt.', 4),
(50, 1, 'Rẻ mà tiện.', 5);

-- ========================================================
-- 4. TÍNH ĐIỂM TRUNG BÌNH (AVG_RATING)
-- ========================================================
SET SQL_SAFE_UPDATES = 0;
UPDATE sanpham 
SET avg_rating = (
    SELECT COALESCE(AVG(danh_gia), 0)
    FROM binhluan
    WHERE binhluan.id_sanpham = sanpham.id
);
SET SQL_SAFE_UPDATES = 1;

-- KẾT THÚC --