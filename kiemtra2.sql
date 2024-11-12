-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1:3306
-- Thời gian đã tạo: Th10 10, 2024 lúc 04:55 PM
-- Phiên bản máy phục vụ: 5.7.36
-- Phiên bản PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ktra2`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loaisp`
--

DROP TABLE IF EXISTS `loaisp`;
CREATE TABLE IF NOT EXISTS `loaisp` (
  `maloai` varchar(10) NOT NULL,
  `tenloai` varchar(50) NOT NULL,
  `mota` text,
  PRIMARY KEY (`maloai`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `loaisp`
--

INSERT INTO `loaisp` (`maloai`, `tenloai`, `mota`) VALUES
('ML01', 'Sữa rửa mặt', 'Sản phẩm giúp làm sạch da mặt, loại bỏ bụi bẩn và dầu thừa'),
('ML02', 'Kem dưỡng da', 'Sản phẩm cung cấp độ ẩm và dưỡng chất cho da'),
('ML03', 'Toner', 'Sản phẩm cân bằng độ pH và làm sạch sâu cho da'),
('ML04', 'Serum', 'Sản phẩm chứa dưỡng chất đậm đặc giúp cải thiện tình trạng da'),
('ML05', 'Kem chống nắng', 'Sản phẩm bảo vệ da khỏi tác hại của tia UV'),
('ML06', 'Mặt nạ dưỡng da', 'Sản phẩm dưỡng da, cung cấp độ ẩm và chất dinh dưỡng cho da'),
('ML07', 'Nước tẩy trang', 'Sản phẩm giúp làm sạch lớp trang điểm và bụi bẩn'),
('ML08', 'Son môi', 'Sản phẩm làm đẹp cho môi với nhiều màu sắc khác nhau'),
('ML09', 'Phấn nền', 'Sản phẩm giúp che phủ khuyết điểm, làm đều màu da'),
('ML10', 'Mascara', 'Sản phẩm giúp tạo độ dày và dài cho lông mi');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sanpham`
--

DROP TABLE IF EXISTS `sanpham`;
CREATE TABLE IF NOT EXISTS `sanpham` (
  `masp` int(11) NOT NULL AUTO_INCREMENT,
  `tensp` varchar(100) NOT NULL,
  `soluong` int(11) DEFAULT NULL,
  `dongia` decimal(10,2) DEFAULT NULL,
  `mota` text,
  `maloai` varchar(10) DEFAULT NULL,
  `anh` text NOT NULL,
  PRIMARY KEY (`masp`),
  KEY `maloai` (`maloai`)
) ENGINE=InnoDB AUTO_INCREMENT=123134 DEFAULT CHARSET=utf8mb4;

--
-- Đang đổ dữ liệu cho bảng `sanpham`
--

INSERT INTO `sanpham` (`masp`, `tensp`, `soluong`, `dongia`, `mota`, `maloai`, `anh`) VALUES
(1, '1231', NULL, '123.00', NULL, 'ML01', '[\"uploads\\/cua-thit-5555.jpg\"]');

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `sanpham`
--
ALTER TABLE `sanpham`
  ADD CONSTRAINT `sanpham_ibfk_1` FOREIGN KEY (`maloai`) REFERENCES `loaisp` (`maloai`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
