-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th6 16, 2025 lúc 04:00 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `danhom5`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `IdCart` int(11) NOT NULL,
  `IdUser` int(11) NOT NULL,
  `ChiTiet` longtext NOT NULL,
  `GiaTien` int(11) NOT NULL,
  `TrangThai` text NOT NULL,
  `ThoiGian` longtext NOT NULL,
  `shipping_address` varchar(250) NOT NULL,
  `Phone` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `kho`
--

CREATE TABLE `kho` (
  `IdKho` int(11) NOT NULL,
  `TenKho` varchar(100) NOT NULL,
  `DiaChi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `kho`
--

INSERT INTO `kho` (`IdKho`, `TenKho`, `DiaChi`) VALUES
(32, 'Kho Q1 Kho Vật Dụng', 'Đa Kao'),
(33, 'Kho Q1 Kho Đồ Chơi SKien', 'Đa Kao'),
(34, 'Kho Q1 Thiết Bị', 'Đa Kao'),
(36, 'Kho Q5 Băng Đĩa', 'Hùng Vương');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhomsp`
--

CREATE TABLE `nhomsp` (
  `IdNhomSP` int(11) NOT NULL,
  `TenNhomSP` varchar(50) NOT NULL,
  `HinhNSP` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhomsp`
--

INSERT INTO `nhomsp` (`IdNhomSP`, `TenNhomSP`, `HinhNSP`) VALUES
(59, 'Đồ Chơi ', ''),
(60, '123123', 'Screenshot 2025-06-05 173312.png'),
(61, '3423423', 'Screenshot 2025-06-05 173312.png'),
(62, '123123', 'katana_gf76gf66_bb_wallpaper_preload_3840x2160.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phieu`
--

CREATE TABLE `phieu` (
  `id` int(11) NOT NULL,
  `ma_phieu` varchar(50) NOT NULL,
  `ngay` date NOT NULL,
  `nhan_vien` varchar(100) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `loai_phieu` enum('nhap','xuat') NOT NULL,
  `id_sp` int(11) NOT NULL,
  `id_nhomsp` int(11) NOT NULL,
  `id_kho` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

CREATE TABLE `sanpham` (
  `IdSP` int(11) NOT NULL,
  `IdNhomSP` int(11) NOT NULL,
  `TenSP` varchar(50) NOT NULL,
  `Hinh` varchar(255) NOT NULL,
  `HanSD` date NOT NULL,
  `ChiTiet` longtext NOT NULL,
  `HetHan` date NOT NULL,
  `SoLuong` varchar(255) DEFAULT NULL,
  `id_kho` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`IdSP`, `IdNhomSP`, `TenSP`, `Hinh`, `HanSD`, `ChiTiet`, `HetHan`, `SoLuong`, `id_kho`) VALUES
(63, 59, 'Bút Chì B', '/sanpham/Screenshot 2025-06-05 173312.png', '2222-02-22', '', '2223-02-22', '5', 36),
(64, 59, 'Máy Bắn Loz', '/sanpham/katana_gf76gf66_bb_wallpaper_preload_3840x2160.jpg', '2225-02-22', '', '2226-02-22', '555', 36),
(65, 59, 'asdasd', '/sanpham/katana_gf76gf66_bb_wallpaper_preload_3840x2160.jpg', '2222-02-22', '', '2225-02-22', '4', 36),
(66, 59, 'vdavasd', '/sanpham/Screenshot 2025-06-05 173312.png', '2222-02-22', '', '2225-02-22', '1231', 34);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `IdUser` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `MatKhau` varchar(50) NOT NULL,
  `Loai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`IdUser`, `Username`, `Email`, `MatKhau`, `Loai`) VALUES
(2, 'admin', 'admin', 'admin', 2),
(4, '215125125', '123123@123', '125125', 1),
(7, 'hth1831990', '123@123', '123123', 1),
(8, 'vinh', 'jinbao1994@gmail.com', '123', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`IdCart`);

--
-- Chỉ mục cho bảng `kho`
--
ALTER TABLE `kho`
  ADD PRIMARY KEY (`IdKho`);

--
-- Chỉ mục cho bảng `nhomsp`
--
ALTER TABLE `nhomsp`
  ADD PRIMARY KEY (`IdNhomSP`);

--
-- Chỉ mục cho bảng `phieu`
--
ALTER TABLE `phieu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_sp` (`id_sp`),
  ADD KEY `id_nhomsp` (`id_nhomsp`),
  ADD KEY `id_kho` (`id_kho`);

--
-- Chỉ mục cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD PRIMARY KEY (`IdSP`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`IdUser`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `IdCart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT cho bảng `kho`
--
ALTER TABLE `kho`
  MODIFY `IdKho` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT cho bảng `nhomsp`
--
ALTER TABLE `nhomsp`
  MODIFY `IdNhomSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT cho bảng `phieu`
--
ALTER TABLE `phieu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  MODIFY `IdSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `IdUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `phieu`
--
ALTER TABLE `phieu`
  ADD CONSTRAINT `phieu_ibfk_1` FOREIGN KEY (`id_sp`) REFERENCES `sanpham` (`IdSP`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_ibfk_2` FOREIGN KEY (`id_nhomsp`) REFERENCES `nhomsp` (`IdNhomSP`) ON DELETE CASCADE,
  ADD CONSTRAINT `phieu_ibfk_3` FOREIGN KEY (`id_kho`) REFERENCES `kho` (`IdKho`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
