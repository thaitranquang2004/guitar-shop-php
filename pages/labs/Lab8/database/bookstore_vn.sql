/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : bookstore_vn

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2015-10-02 00:42:34
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for chitiethd
-- ----------------------------
DROP TABLE IF EXISTS `chitiethd`;
CREATE TABLE `chitiethd` (
  `mahd` varchar(100) NOT NULL,
  `masach` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `soluong` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `gia` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`mahd`,`masach`),
  KEY `masach` (`masach`),
  CONSTRAINT `chitiethd_ibfk_1` FOREIGN KEY (`mahd`) REFERENCES `hoadon` (`mahd`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `chitiethd_ibfk_2` FOREIGN KEY (`masach`) REFERENCES `sach` (`masach`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of chitiethd
-- ----------------------------
INSERT INTO `chitiethd` VALUES ('abcd@yahoo.com_1286282293', 'td01', '1', '450000');
INSERT INTO `chitiethd` VALUES ('abcd@yahoo.com_1286282293', 'th12', '1', '76000');

-- ----------------------------
-- Table structure for hoadon
-- ----------------------------
DROP TABLE IF EXISTS `hoadon`;
CREATE TABLE `hoadon` (
  `mahd` varchar(100) NOT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `ngayhd` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `tennguoinhan` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `diachinguoinhan` varchar(80) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ngaynhan` date NOT NULL DEFAULT '0000-00-00',
  `dienthoainguoinhan` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`mahd`),
  KEY `email` (`email`),
  CONSTRAINT `hoadon_ibfk_1` FOREIGN KEY (`email`) REFERENCES `khachhang` (`email`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of hoadon
-- ----------------------------
INSERT INTO `hoadon` VALUES ('abcd@yahoo.com_1286282293', 'abcd@yahoo.com', '2010-10-05 19:38:13', 'abc', 'd,skl', '2010-10-06', '234567');

-- ----------------------------
-- Table structure for khachhang
-- ----------------------------
DROP TABLE IF EXISTS `khachhang`;
CREATE TABLE `khachhang` (
  `email` varchar(50) NOT NULL DEFAULT '',
  `matkhau` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tenkh` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `diachi` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `dienthoai` varchar(10) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of khachhang
-- ----------------------------
INSERT INTO `khachhang` VALUES ('abcd@yahoo.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Minh Triết', 'Q1', '99999999');
INSERT INTO `khachhang` VALUES ('hung.stu@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Đại Ca - Trần văn Hùng', 'Quận 3', '090090999');

-- ----------------------------
-- Table structure for loai
-- ----------------------------
DROP TABLE IF EXISTS `loai`;
CREATE TABLE `loai` (
  `maloai` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tenloai` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`maloai`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of loai
-- ----------------------------
INSERT INTO `loai` VALUES ('gk', 'Giáo Khoa');
INSERT INTO `loai` VALUES ('khkt', 'Ky Thuat');
INSERT INTO `loai` VALUES ('kt', 'Kinh Tế');
INSERT INTO `loai` VALUES ('nn', 'Ngoại Ngữ');
INSERT INTO `loai` VALUES ('pl', 'Pháp Luật');
INSERT INTO `loai` VALUES ('td', 'Từ Điển');
INSERT INTO `loai` VALUES ('test', 'Loai Moi');
INSERT INTO `loai` VALUES ('th', 'Tin Học');
INSERT INTO `loai` VALUES ('to', 'Toán Học');
INSERT INTO `loai` VALUES ('tt', 'The Thao Du Lich');
INSERT INTO `loai` VALUES ('vh', 'Văn Học');
INSERT INTO `loai` VALUES ('vhxh', 'Van Hoa xa Hoi');

-- ----------------------------
-- Table structure for nhaxb
-- ----------------------------
DROP TABLE IF EXISTS `nhaxb`;
CREATE TABLE `nhaxb` (
  `manxb` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tennxb` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`manxb`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of nhaxb
-- ----------------------------
INSERT INTO `nhaxb` VALUES ('gd', 'Giáo dục');
INSERT INTO `nhaxb` VALUES ('hcm', 'Tổng Hợp Hồ Chí Minh');
INSERT INTO `nhaxb` VALUES ('hnv', 'Hội Nhà Văn');
INSERT INTO `nhaxb` VALUES ('pn', 'Phụ Nữ');
INSERT INTO `nhaxb` VALUES ('tn', 'Thanh Niên');
INSERT INTO `nhaxb` VALUES ('vh', 'Văn Học');
INSERT INTO `nhaxb` VALUES ('vhtt', 'Văn Hóa Thông Tin');

-- ----------------------------
-- Table structure for quantri
-- ----------------------------
DROP TABLE IF EXISTS `quantri`;
CREATE TABLE `quantri` (
  `username` varchar(30) NOT NULL,
  `matkhau` varchar(32) DEFAULT NULL,
  `hoten` varchar(100) CHARACTER SET utf8 NOT NULL,
  `quyen` int(1) NOT NULL COMMENT '1:  supper admin, 2:nhan viên, 3:...',
  PRIMARY KEY (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of quantri
-- ----------------------------
INSERT INTO `quantri` VALUES ('abcd', '81dc9bdb52d04dc20036dbd8313ed055', 'Nguyễn văn A', '2');
INSERT INTO `quantri` VALUES ('hung', 'e10adc3949ba59abbe56e057f20f883e', 'Lên Văn An', '2');
INSERT INTO `quantri` VALUES ('admin', '21232f297a57a5a743894a0e4a801fc3', 'Trần Văn Hùng', '1');

-- ----------------------------
-- Table structure for sach
-- ----------------------------
DROP TABLE IF EXISTS `sach`;
CREATE TABLE `sach` (
  `masach` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `tensach` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mota` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gia` int(10) NOT NULL,
  `hinh` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `manxb` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `maloai` varchar(5) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`masach`),
  KEY `manxb` (`manxb`,`maloai`),
  KEY `maloai` (`maloai`),
  CONSTRAINT `sach_ibfk_1` FOREIGN KEY (`manxb`) REFERENCES `nhaxb` (`manxb`) ON UPDATE CASCADE,
  CONSTRAINT `sach_ibfk_2` FOREIGN KEY (`maloai`) REFERENCES `loai` (`maloai`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sach
-- ----------------------------
INSERT INTO `sach` VALUES ('td01', 'Từ Điển mẫu câu tiếng Nhật', 'Tập hợp tất cả các mẫu câu tiếng Nhật. Phong phú, đầu đủ nhất. ', '450000', 'td01.jpg', 'gd', 'td');
INSERT INTO `sach` VALUES ('td02', 'Từ Điển Kinh Doanh Và Tiếp Thị Hiện Đại', 'Quyển sách “Từ điển Kinh doanh – Tiếp thị Hiện đại” (Modern Business & Marketing Dictionary) của tác giả Cung Kim Tiến (Bút danh Anh Tuấn) trình bày các thuật ngữ đang sử dụng thịnh hành trong giao dịch kinh doanh và tiếp thị trong nước và quốc tế. Đặc điểm của quyển sách là các thuật ngữ được đặt trong các bối cảnh khác nhau, bằng cách dẫn các đoạn văn xuất hiện trong thực tiễn kinh doanh quốc tế, giúp bạn đọc hiểu rõ được ý nghĩa và cách sử dụng trong thực tiễn của các thuật ngữ chuyên biệt này, với các nội dung thú vị khác nhau.\r\n Tác giả đã chọn lọc một cách công phu các đoạn văn đa dạng và phong phú, xuất hiện trên các ấn phẩm quốc tế khác nhau, giúp độc giả có cơ hội thuận lợi trong giao tiếp, soạn thảo, hoặc tham gia các buổi họp liên quan đến kinh doanh, đảm nhiệm các nhiệm vụ về kinh doanh, quản lý và tiếp thị trong các doanh nghiệp.\r\nQuyển sách này được kỳ vọng sẽ trợ giúp hiệu quả để bạn đọc tiếp cận một lĩnh vực tri thức kinh doanh bằng Anh ngữ, là bạn đồng hành trên con đường sự nghiệp trong thời kỳ quốc tế hóa.', '195000', 'td02.gif', 'vhtt', 'td');
INSERT INTO `sach` VALUES ('td03', 'Đại Từ Điển Tiếng Việt (Bản mới 2010)', 'Thêm yêu tiếng Việt\r\n\r\n \r\n\r\nTừ lâu chúng ta đã có nhiều công trình nghiên cứu về kho tàng tiếng Việt, thế nhưng “Đại từ điển tiếng Việt” (NXB Đại học Quốc gia TPHCM - Nguyễn Như Ý chủ biên) vừa ra mắt bạn đọc là công trình đầy đặn và đồ sộ nhất. Cuốn sách đã bắt nhịp cầu cho những ai yêu tiếng mẹ…\r\n\r\n \r\n\r\nCầm trên tay cuốn Đại từ điển dày gần 2.000 trang mới cảm nhận hết tâm huyết của những người làm sách. Cuốn từ điển này được in lần đầu tiên vào năm 1999, đến nay, đáp ứng nhu cầu của bạn đọc, các tác giả đã tiến hành nghiên cứu, bổ sung.\r\n\r\n \r\n\r\nTrong lần tái bản này, ban biên soạn đã chọn và đưa vào sách những từ ngữ mới xuất hiện và đã được dùng rộng rãi trong đời sống và trên các phương tiện thông tin đại chúng nhằm làm tăng tính mới mẻ và tiện ích cho người sử dụng.\r\n\r\n \r\n\r\nMột trong những ý tưởng chinh phục người đọc là tính đa dạng của Đại từ điển tiếng Việt. Bởi nó không chỉ đơn thuần là sự tra cứu nghĩa các từ mà mở ra chân trời kiến thức mới. Việc đan xen những kiến thức cơ bản về văn hóa, văn minh Việt Nam và thế giới, giới thiệu tổng quan và hệ thống các hiện vật văn hóa như: Đơn vị đo lường của Việt Nam và thế giới, đồng bạc Việt xưa và nay, các loại trống đồng hiện có ở Việt Nam, quốc kỳ các nước trên thế giới… Đây là những thông tin bổ ích đáp ứng nhu cầu bổ sung kiến thức cơ bản của học sinh - sinh viên và các bạn trẻ Việt Nam.\r\n\r\n\r\n', '450000', 'td03.jpg', 'hcm', 'td');
INSERT INTO `sach` VALUES ('td04', 'từ điển y học sức khỏe bệnh lý Anh Anh Việt', 'Từ điển y học - sức khỏe bệnh lý Anh Anh Việt này được biên soạn để đáp ứng nhu cầu tìm hiểu, tra cướu và dịch thuật các tư liệu y khoa bằng tiếng anh, cũng như tăng cường kiến thức về các bệnh thường gặp của các thành phần độc giả trong xã hội. ', '380000', 'td04.jpg', 'tn', 'td');
INSERT INTO `sach` VALUES ('td05', 'Từ Điển Anh Việt - 75000 Từ', 'Từ điển mới ...', '50000', 'td05.jpg', 'hcm', 'td');
INSERT INTO `sach` VALUES ('td06', 'Từ điển địa danh hành chính Nam Bộ', 'Từ điển địa danh hành chính Nam Bộ do tác giả Nguyễn Đình Tư biên soạn hết sức công phu, tổng hợp được nhiều tư liệu quý, là công cụ giúp bạn đọc tra cứu một cách khoa học về địa danh hành chính. Đây là cuốn sách có giá trị không chỉ bởi nó cung cấp một lượng mục từ khá đồ sộ, mà còn bởi tác giả đã dành rất nhiều công sức và tâm huyết để sưu tầm, xử lý tư liệu về vùng đất có bề dày truyền thống lịch sử, nhưng cũng có sự thay đổi nhiều và phức tạp nhất về địa danh hành chính', '265000', 'td06.jpg', 'hcm', 'td');
INSERT INTO `sach` VALUES ('th01', '100 thủ thuật với Excel 2010', '100 thủ thuật ứng với 100 bài tập thực hành được hướng dẫn, giải thích theo bố cục chặt chẽ, cách trình bày rõ ràng, dễ sử dụng, bạn đọc có thể tự mình xử lý những vấn đề nảy sinh trong quá trình thực hành đồng thời giúp các bạn thao tác nhanh trên bảng tính.\r\n', '54000', 'th01.gif', 'hcm', 'th');
INSERT INTO `sach` VALUES ('th02', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu', 'Tiếp theo tập 1, tập 2 của cuốn sách \"Lập trình Web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1\" bao gồm 10 chương và ứng dụng đính kèm lần lượt giới thiệu cùng bạn đọc các kiến thức liên quan đến Session, Cookie, giỏ hàng trực tuyến, tìm kiếm và phân trang dữ liệu, lập trình hướng đối tượng và sử dụng Zend Framework.\r\n\r\nChương 8 trình bày kiến thức cơ bản của kịch bản trình chủ PHP và cơ sở dữ liệu MySQL.\r\n\r\nSang chương 9, bạn tiếp tục tìm hiểu cách thiết kế trang Web cho phép người sử dụng tìm kiếm và phân trang dữ liệu trình bày với nhiều hình thức khác nhau.\r\n\r\nĐể xây dựng ứng dụng thương mại điện tử hoàn chỉnh và mang tính chuyên nghiệp cao, bạn tiếp tục tìm hiểu cách sử dụng hàm Session và Cookie trong chương 10 để lưu trữ thông tin của người sử dụng nhằm vào mục đích quản lý tài nguyên của Website.\r\n\r\nMọi ứng dụng thương mại điện tử đều cung cấp chương giỏ hàng trực tuyến, bạn cũng được tìm hiểu cách xây dựng giỏ hàng bằng cách sử dụng Session lẫn Cookie trong chương 11.\r\n\r\nKhi có nhu cầu trình bày hình ảnh, đồ thị và âm thanh lẫn phim ảnh, bạn tìm hiểu cách sử dụng mã PHP trong chương 12.\r\n\r\nTiếp theo, bạn có thể tìm hiểu cú pháp của kịch bản PHP trong chương 13 và học cách lập trình hướng đối tượng và sử dụng lớp này vào ứng dụng trong chương 14.\r\n\r\nChương 15 giúp bạn sử dụng kịch bản trình khách Java Script để thay đổi góc nhìn và ứng xử của thẻ HTML trong trang Web.\r\n\r\nSang chương 16, bạn khám phá thư viện mã nguồn mở Zend viết bằng PHP dùng cho các loại cơ sở dữ liệu và học cách sử dụng các lớp trong thư viện này vào ứng dụng bán hàng trực tuyến trong chương 17.', '76000', 'th02.jpg', 'hcm', 'th');
INSERT INTO `sach` VALUES ('th03', 'Lập trình web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1 (Tập1)', 'Tập 1 của cuốn sách \"Lập trình Web bằng PHP 5.3 và cơ sở dữ liệu MySQL 5.1\" bao gồm 7 chương và ứng dụng đính kèm. Chương 1 cung cấp cho bạn kiến thức từ chức năng của Website, cài đặt gói WamSever 2.0 và cấu hình để có thể vận hành ứng dụng Web bằng PHP, MySQL và Apache Web Sever.\r\n\r\nSang chương 2, bạn tiếp tục tìm hiểu cách tạo Website và thiết kế cấu trúc dùng cho doanh nghiệp bằng hệ quản trị nội dung mã nguồn mở Joomla. Nhằm thỏa mãn nội dung trình bày, bạn tiếp tục tìm hiểu cách thiết kế trang Web tĩnh hay động bằng mã tự sinh PHP với phần mềm Dreamweaver CS trong chương 3 và thẻ HTML trong chương 4.\r\n\r\nTiếp theo, bạn có thể tìm hiểu cú pháp của kịch bản PHP trong chương 5 và học cách sử dụng ứng dụng PhpMyAdmin để quản trị cơ sở dữ liệu MySQL trong chương 6. Sang chương 7 bạn tìm hiểu phát biểu SQL của cơ sở dữ liệu MySQL dùng để xây dựng ứng dụng bán hàng trực tuyến.', '76000', 'th03.jpg', 'hcm', 'th');
INSERT INTO `sach` VALUES ('th04', 'Làm Quen Với Internet', 'Ngày nay với sự phát triển không ngừng của kinh tế nói chung và ngành công nghệ thông tin nói riêng, chúng ta có thể dễ dàng tiếp xúc và làm quen với máy vi tính. Tuy nhiên đây là một lĩnh vực mới lại chưa được phổ cập ở mọi cấp học nên các em sẽ có cảm giác bỡ ngỡ, thiếu tự tin khi lần đầu làm quen với chiếc máy tính đa năng. Mỗi bài học trong cuốn sách là một bài thực hành, được thực hiện qua từng bước cơ bản với hình ảnh minh họa trực quan và những lời giải thích chi tiết.', '31000', 'th04.jpg', 'hcm', 'th');
INSERT INTO `sach` VALUES ('th05', 'Từng Bước Làm Quen Với Máy Tính', 'Mục Lục:\r\n\r\nBài 1: Máy tính điện tử và hệ điều hành\r\n\r\nBài 2: Hệ điều hành Window XP\r\n\r\nBài 3: Làm việc với máy tính qua desktop\r\n\r\nBài 4: Tệp tin và thư mục\r\n\r\nBài 5: Sử dụng Window Explorer\r\n\r\nBài 6: Một số thao tác cần biết\r\n\r\nPhụ lục – Những tổ hợp phím tắt', '31000', 'th05.jpg', 'vhtt', 'th');
INSERT INTO `sach` VALUES ('th06', 'Quản Trị Windows Server 2008 - Tập 2', 'Kế thừa những ưu điểm vượt trội và sự thành công của Windows Server 2003 cùng những phiên bản Windows trước đó, hãng Microsoft tiếp tục cho ra đời một phiên bản hệ điều hành dành cho máy chủ mới, Windows Server 2008. Phiên bản này đem đến cho người dùng sự nhanh chóng trong cài đặt; sự tiện lợi trong quản trị hệ thống, tương tác với các thành phần và dịch vụ vì được tập trung vài một công cụ duy nhất – Server Manager, những cải tiến đáng chú ý trên Windows Firewall; công nghệ ảo hoá…\r\n\r\nWindows Server 2008 còn cung cấp cho người sử dụng cách thức cài đặt Server Core, bao gồm những thành phần cơ bản nhất của Windows Server và giao diện dòng lệnh. Với kiểu cài đặt này, giao diện đồ hoạ quen thuộc của Windows cùng những dịch vụ không cần thiết sẽ không được cài đặt lên hệ thống. Nhờ đó nâng cao độ bảo mật và nâng cấp hệ thống.', '62000', 'th06.jpg', 'hcm', 'th');
INSERT INTO `sach` VALUES ('th07', 'Kỹ Thuật Lập Trình C - Cơ Sở Và Nâng Cao', ' Cuốn sách này gồm những nội dung chính sau:\r\n# Chương 1: Các khái niệm cơ bản\r\n# Chương 2: Hằng biến và mảng\r\n# Chương 3: Biểu thức\r\n# Chương 4: Vào ra\r\n# Chương 5: Các toán tử điều khiển\r\n# Chương 6: Hàm và cấu trúc chương trình\r\n# Chương 7: Cấu trúc\r\n# Chương 8: Quản lý màn hình và cửa sổ\r\n# Chương 9: Đồ họa\r\n# Chương 10: Thao tác trên các tập tin\r\n# Chương 11: Lưu trữ dữ liệu và tổ chức bộ nhớ chương trình\r\n# Chương 12: Các chỉ thị tiền xử lý\r\n# Chương 13: Sử dụng ngắt trong C\r\n# Chương 14: Truy nhập trực tiếp vào bộ nhớ\r\n# Chương 15: Hàm xử ngắt và chương trình thường trú\r\n# Chương 16: Âm thanh, âm nhạc\r\n# Chương 17: Lập trình theo thời gian, theo sự kiện và trò chơi\r\n# Chương 18: Giao diện giữa C và Assembler\r\n# Phụ lục 1: Quy tắc xuống dòng và sử dụng các khoảng trống khi viết chương trình\r\n# Phụ lục 2: Tóm tắt các hàm chuẩn của Turbo C\r\n# Phụ lục 3: Bảng mã ASCII\r\n# Phụ lục 4: Cài đặt Turbo C vào đĩa cứng\r\n# Phụ lục 5: Hướng dẫn sử dụng môi trường kết hợp Turbo C\r\n# Phụ lục 6: Hệ soạn thảo của Turbo C\r\n# Phụ lục 7: Dùng menu project dịch chương trình trên nhiều tệp\r\n# Phụ lục 8: Dịch chương trình theo chế độ dòng lệnh TCC\r\n# Phụ lục 9: Sửa đổi cú pháp và gỡ rối chương trình\r\n# Phụ lục 10: Các mô hình bộ nhớ\r\n# Phụ lục 11: Danh sách các hàm của Turbo C theo thứ tự ABC\r\n# Phụ lục 12: Hàm với đối số bất định trong C\r\n# Phụ lục 13: Một số chương trình hữu ích', '72000', 'th07.jpg', 'tn', 'th');
INSERT INTO `sach` VALUES ('th08', 'Giáo Trình Học Nhanh SQL Server 2008 - Tập 2', 'Bộ sách “Giáo trình học nhanh SQL Server 2008” được biên soạn dành cho các nhà phát triển và các nhà quản trị cơ sở dữ liệu, những người đang công tác trong lĩnh vực quản lý dữ liệu doanh nghiệp và cho tất cả những ai quan tâm đến SQL Server 2008.\r\n\r\n\r\nVới cách thiết kế và bố cục rõ ràng theo từng chủ điểm cụ thể, bộ sách tập trung trình bày những tính năng chính của SQL Server 2008 nhằm mục đích giúp bạn đọc tăng cường kiến thức đồng thời nâng cao kỹ năng sử dụng sản phẩm mới rất tuyệt vời này. Bộ sách được chia thành 2 tập với bốn phần chính sau đây:', '81000', 'th08.jpg', 'hcm', 'th');
INSERT INTO `sach` VALUES ('th09', '160 Vấn Đề Cần Nên Biết Khi Sử Dụng Đồ Họa Máy Vi Tính', '\"160 Vấn Đề Cần Nên Biết Khi Sử Dụng Đồ Họa Máy Vi Tính\" bao gồm những vấn đề cơ bản và thiết yếu mà những người đang học hay làm đồ họa máy vi tính thường quan tâm tìm hiểu nhằm làm việc hiệu quả hơn với các chương trình phần mềm như Photoshop, CorelDRAW và Illustrator.\r\n\r\n\r\nSách gồm 3 phần, được thiết kế và bố cục theo từng vấn đề cụ thể từ cơ bản đến chuyên nghiệp như tùy biến Photoshop cho các dự án mà bạn thực hiện, chỉnh sửa các bức ảnh chân dung, tạo nên điều kỳ diệu với những hiệu ứng số đặc biệt, trình bày hình ảnh một cách chuyên nghiệp, tạo các thiết kế và viết lời truyện tranh trong CorelDRAW, và áp dụng các hiệu ứng với Illustrator.\r\n\r\n\r\nSách được trình bày ngắn gọn, rõ ràng kèm hình ảnh minh họa. Ngoài ra sách còn bao gồm nhiều thủ thuật và lưu ý hữu ích.', '85000', 'th09.jpg', 'tn', 'th');
INSERT INTO `sach` VALUES ('th10', 'Giáo Trình Học Nhanh SQL Server 2008 - Tập 1', 'Bộ sách “Giáo trình học nhanh SQL Server 2008” được biên soạn dành cho các nhà phát triển và các nhà quản trị cơ sở dữ liệu, những người đang công tác trong lĩnh vực quản lý dữ liệu doanh nghiệp và cho tất cả những ai quan tâm đến SQL Server 2008.\r\n\r\n\r\nVới cách thiết kế và bố cục rõ ràng theo từng chủ điểm cụ thể, bộ sách tập trung trình bày những tính năng chính của SQL Server 2008 nhằm mục đích giúp bạn đọc tăng cường kiến thức đồng thời nâng cao kỹ năng sử dụng sản phẩm mới rất tuyệt vời này.', '69000', 'th10.jpg', 'tn', 'th');
INSERT INTO `sach` VALUES ('th11', 'Microsoft Word 2007 - Căn Bản Và Thủ Thuật', 'Microsoft Word 2007 nói riêng và Microsoft Office 2007 nói chung có nhiều đổi mới. Microsoft chẳng những cung cấp cho người dùng giao diện đẹp mắt mà còn có nhiều tiện ích và trực quan hơn so với các phiên bản trước đây. Thay cho thanh menu và các thanh dụng cụ là một hệ thống Ribbon bao gồm các thẻ, các nhóm, trong từng menu lại có các menu phụ và các lệnh. Khi bạn trỏ chuột vào biểu tượng nào của hệ thống này sẽ hiển thị ScreenTip cho biết chức năng và công dụng của chúng. Chẳng những thế, Word còn thể hiện tức thời hiệu quả của từng lệnh để bạn xem, trước khi chọn chúng.\r\n\r\n\r\nTrong quyển sách này, chúng tôi trình bày tóm tắt lý thuyết căn bản về soạn thảo, chỉnh sửa, định dạng văn bản và một số thủ thuật mà bất cứ ai làm công tác văn phòng đều phải sử dụng. Nội dung sách gồm 6 bài: 1-Thủ thuật tổng quát, 2-Soạn thảo và chỉnh sửa văn bản, 3-Định dạng văn bản, 4-WordArt và xử lý hình ảnh, 5-Liên kết và Web, 6-Bảo mật & in ấn văn bản,. Từ bài 2 đến bài 4, trước khi trình bày thủ thuật, chúng tôi tóm tắt lý thuyết giống như giáo trình Word 2007 để bạn thực hành', '69000', 'th11.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th12', 'Kế Toán Doanh Nghiệp Với ACCESS', 'Sách mới...', '76000', 'th12.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th13', 'Giáo Trình C++ & Lập Trình Hướng Đối Tượng', 'Cuốn sách gồm 12 chương và 7 phụ lục:\r\n\r\nChương 1 hướng dẫn cách làm việc với phần mềm TC++ 3.0 để thử nghiệm các chương trình, trình bày sơ lược về các phương pháp lập trình và giới thiệu một số mở rộng đơn giản của C.\r\n\r\nChương 2 trình bày các khả năng mới trong việc xây dựng và sử dụng hàm trong C++ như biến tham chiếu, đối có kiểu tham chiếu, đối có giá trị mặc định, hàm trực tuyến, hàm trùng tên, hàm toán tử.\r\n\r\nChương 3 nói về một khái niệm trung tâm của lập trình hướng đối tượng là lớp gồm: Định nghĩa lớp, khai báo các biến, mảng đối tượng ( kiểu lớp ), phương pháp, dùng con trỏ this trong phương thức, phạm vi truy xuất của các thành phần, các phương thức toán tử.\r\n\r\nChương 4 trình bày các vấn đề tạo dựng, sao chép, huỷ bỏ các đối tượng và các vấn đề khác có liên quan như: Hàm tạo, hàm tạo sao chép, hàm huỷ, toán tử gán, cấp phát bộ nhớ cho đối tượng, hàm bạn, lớp bạn.\r\n\r\nChương 5 trình bày một khái niệm quan trong tạo nên khả năng mạnh của lập trình hướng đối tượng trong việc phát triển, mở rộng phầm mềm, đó là khả năng thừa kế củaw các lớp.\r\n\r\nChương 6 trình bày một khái niệm quan trọng khác cho phép xử lý các vấn đề khác nhau, các thực thể khác nhau, các thuật toán khác nhau theo cùng một lược đồ thống nhất, đó là tính tướng ứng bội và phương thức ảo. Các công cụ này cho phép dễ dàng tổ chức chương trình quản lý nhiều dạng đối tượng khác nhau.\r\n\r\nChương 7 trình bày các thao tác trên tệp như: tạo một tệp mới, ghi dữ liệu từ bộ nhớ lên tệp, đọc dữ liệu từ tệp vào bộ nhớ...\r\n\r\nChương 8 nói về việc tổ chức vào/ ra trong C++.C++ đưa vào một khái niệm mới gọi là các dòng tin ( Stream ), Các thao tác vào/ra sẽ thực hiện trao đổi dữ liệu giữa các bộ nhớ với dòng tin: Vào là chuyển dữ liệu từ dòng nhập vào bộ nhớ, ra là chuyển dữ liệu từ bộ nhớ lên dòng xuất. Để nhập xuất dữ liệu trên một thiết bị cụ thể nào, ta chỉ cần gắn dòng nhập xuất với thiết bị đó. Việc tổ chức vào ra theo cách như vậy là rất khoa học và tiện lợi vì nó có tính độc lập thiết bị.\r\n\r\nChương 9 trình bày các hàm đồ hoạ sử dụng trong C và C++. Các hàm này được sử dụng rải rác trong toàn bộ cuốn sách để xây dựng các đối tượng đồ hoạ.\r\n\r\nChương 10 trình bày các hàm truy xuất trực tiếp vào bộ nhớ của máy tính, trong đó có bộ nhớ màn hình. Các hàm này sẽ được sử dụng trong chương 11 để xây dựng các lớp menu và cửa sổ.\r\n\r\nChương 11 giới thiệu 5 chương trình tương đối hoàn chỉnh nhằm minh hoạ thêm khả năng và kỹ thuật lập trình hướng đối tượng trên C++.\r\n\r\nChương 12 trình bày thêm một số chương trình đối tượng trên C++. Đây là các chương trình tương đối phức tạp, hữu ích và sử dụng các công cụ mạnh của C++.', '78000', 'th13.gif', 'gd', 'th');
INSERT INTO `sach` VALUES ('th14', 'Các Thủ Thuật Trong HTML Và Thiết Kế Web', 'Cuốn sách này sẽ cung cấp các thông tin cần thiết để đẩy nhanh tốc độ thiết kế Web thông qua việc thực hành với các mẫu của nhiều chuyên gia thiết kế Web.\r\nCuốn sách tập trung vào các chi tiết để tạo ra các Web site tốt thông qua nhiều cách tiếp cận hiện đại để giải quyết các thách thức liên quan đến việc tạo Web site. Thay vì đi vào từng ngôn ngữ và công nghệ cụ thể, các bài học trong cuốn sách này được phân chia thành các \"thủ thuật\" nhằm giúp bạn:\r\n# Ngay lập tức cải thiện được Web site của mình\r\n# Xây dựng Web site mới thật sinh động, tương thích với nhiều môi trường khác nhau\r\n# Quản lý việc thiết kế lại\r\n# Đưa Web site từ khởi đầu đến thành công', '89000', 'th14.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th15', 'Tạo Website Hấp Dẫn Với HTML, XHTML Và CSS', 'Ngày nay, việc ứng dụng phát triển Website hấp dẫn không còn gói gọn bằng HTLM, cho dù bạn đang xây dựng một Website thương mại phức tạp hoặc chỉ đơn thuần là tạo ra một Website nhỏ cho bản thân. Với cuốn sách \"Tạo Website Hấp Dẫn Với HTML, XHTML Và CSS\"  này sẽ cùng bạn khám phá các sắc thái của XHTML và CSS theo cách giúp bạn nắm được các vấn đề. Sách bao gồm nhiều thông tin mới cập nhật về XHTML, CSS, JavaScript...\r\nCuốn sách này không những giúp bạn tiết kiệm được thời gian học tập mà còn thích hợp với những ai muốn tò mò tạo một Website, vì sách cung cấp nhiều gợi ý, hướng dẫn rõ ràng trong việc chuẩn bị xuất bản những trang Web đầu tiên ngay sau khi bạn đọc qua vài chương.', '79000', 'th15.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th16', 'Tuyển Tập Thủ Thuật Javascript - Tập 1', '\"Tuyển Tập Thủ Thuật Javascript\" gồm 2 tập, là một tuyển tập các giải pháp cho những vấn đề phổ biến nhất trong JavaScript. Nó chứa đựng các thủ thuật, gợi ý và kỹ thuật tương thích chuẩn, đã được thử nghiệm và bạn có thể tùy biến để sử dụng trong các trình duyệt khác nhau.', '66000', 'th16.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th17', 'Thiết Kế Web Với CSS', '\r\nTừ khi được giới thiệu năm 1996, bảng kiểu xếp tầng (CSS) đã làm thay đổi đáng kể thiết kế Web. Hiện nay, phần lớn trang Web đều sử dụng CSS và nhiều nhà thiết kế đã xây dựng các bố cục trang hoàn toàn dựa trên CSS. Để thực hiện điều này một cách thành công, đòi hỏi chúng ta phải hiểu biết kỹ về nội dung hoạt động của CSS. Sách Thiết Kế Web Với CSS cung cấp cho bạn những vấn đề cần thiết để sử dụng CSS. ', '82000', 'th17.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th18', 'Thiết Kế Web Với JavaScript Và Dom', 'Nội dung cuốn sách \"Thiết Kế Web Với JavaScript Và Dom\" giới thiệu về ngôn ngữ lập trình, nhưng nó không chỉ dành riêng cho các lập trình viên, mà còn rất có ích cho các nhà thiết kế Web.', '79000', 'th18.jpg', 'gd', 'th');
INSERT INTO `sach` VALUES ('th88', 'sach giao khoa lop 10', '?', '30000', 'th18.jpg', 'gd', 'gk');
