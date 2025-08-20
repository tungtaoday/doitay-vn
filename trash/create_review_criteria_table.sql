-- Tạo bảng review_criteria (Tiêu chí đánh giá)
CREATE TABLE `review_criteria` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL COMMENT 'ID danh mục dịch vụ',
  `name` varchar(255) NOT NULL COMMENT 'Tên tiêu chí đánh giá',
  `description` text DEFAULT NULL COMMENT 'Mô tả chi tiết tiêu chí',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Active, 0: Inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng tiêu chí đánh giá theo danh mục dịch vụ';

-- Insert dữ liệu mẫu
INSERT INTO `review_criteria` (`id`, `category_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
-- Điện (category_id = 1)
(1, 1, 'Chất lượng công việc', 'Đánh giá chất lượng thi công điện, độ bền và an toàn', 1, NOW(), NOW()),
(2, 1, 'Tính chuyên nghiệp', 'Thái độ phục vụ, trang phục và cách thức làm việc', 1, NOW(), NOW()),
(3, 1, 'Tốc độ hoàn thành', 'Thời gian hoàn thành công việc so với cam kết', 1, NOW(), NOW()),
(4, 1, 'Giá cả hợp lý', 'Mức giá dịch vụ có phù hợp với chất lượng không', 1, NOW(), NOW()),
(5, 1, 'Kỹ năng chuyên môn', 'Trình độ kỹ thuật và kiến thức chuyên ngành điện', 1, NOW(), NOW()),
(6, 1, 'Tư vấn nhiệt tình', 'Khả năng tư vấn và giải thích kỹ thuật cho khách hàng', 1, NOW(), NOW()),

-- Nước (category_id = 2)
(7, 2, 'Chất lượng công việc', 'Đánh giá chất lượng sửa chữa/lắp đặt hệ thống nước', 1, NOW(), NOW()),
(8, 2, 'Tính chuyên nghiệp', 'Thái độ phục vụ và cách thức làm việc chuyên nghiệp', 1, NOW(), NOW()),
(9, 2, 'Tốc độ hoàn thành', 'Thời gian xử lý sự cố và hoàn thành công việc', 1, NOW(), NOW()),
(10, 2, 'Giá cả hợp lý', 'Mức giá dịch vụ có cạnh tranh và phù hợp không', 1, NOW(), NOW()),
(11, 2, 'Kỹ năng kỹ thuật', 'Khả năng xử lý các vấn đề về đường ống nước', 1, NOW(), NOW()),
(12, 2, 'Vệ sinh sau thi công', 'Mức độ dọn dẹp và vệ sinh khu vực làm việc', 1, NOW(), NOW()),

-- Xây dựng (category_id = 3)
(13, 3, 'Chất lượng thi công', 'Đánh giá chất lượng xây dựng, độ chắc chắn và thẩm mỹ', 1, NOW(), NOW()),
(14, 3, 'Tính chuyên nghiệp', 'Thái độ làm việc và tương tác với khách hàng', 1, NOW(), NOW()),
(15, 3, 'Đúng tiến độ', 'Khả năng hoàn thành công việc đúng thời gian cam kết', 1, NOW(), NOW()),
(16, 3, 'Giá cả cạnh tranh', 'Mức giá dịch vụ có hợp lý so với thị trường không', 1, NOW(), NOW()),
(17, 3, 'Kinh nghiệm thực tế', 'Trình độ và kinh nghiệm trong lĩnh vực xây dựng', 1, NOW(), NOW()),
(18, 3, 'An toàn lao động', 'Tuân thủ các quy định an toàn trong quá trình thi công', 1, NOW(), NOW()),

-- Sơn (category_id = 4)
(19, 4, 'Chất lượng sơn', 'Đánh giá độ mịn, độ đều màu và độ bền của lớp sơn', 1, NOW(), NOW()),
(20, 4, 'Tính chuyên nghiệp', 'Thái độ phục vụ và chuẩn bị công việc kỹ lưỡng', 1, NOW(), NOW()),
(21, 4, 'Tốc độ hoàn thành', 'Thời gian hoàn thành so với dự kiến ban đầu', 1, NOW(), NOW()),
(22, 4, 'Giá cả phù hợp', 'Mức giá dịch vụ có hợp lý và minh bạch không', 1, NOW(), NOW()),
(23, 4, 'Kỹ thuật sơn', 'Kỹ năng và phương pháp sơn chuyên nghiệp', 1, NOW(), NOW()),
(24, 4, 'Tư vấn màu sắc', 'Khả năng tư vấn màu sắc và phối hợp không gian', 1, NOW(), NOW()),

-- Điều hòa (category_id = 6)
(25, 6, 'Chất lượng lắp đặt', 'Đánh giá chất lượng lắp đặt/sửa chữa điều hòa', 1, NOW(), NOW()),
(26, 6, 'Tính chuyên nghiệp', 'Thái độ phục vụ và trang thiết bị chuyên dụng', 1, NOW(), NOW()),
(27, 6, 'Tốc độ xử lý', 'Thời gian xử lý sự cố và hoàn thành công việc', 1, NOW(), NOW()),
(28, 6, 'Giá cả hợp lý', 'Mức giá dịch vụ có cạnh tranh trên thị trường không', 1, NOW(), NOW()),
(29, 6, 'Kiến thức chuyên môn', 'Hiểu biết về các loại điều hòa và công nghệ mới', 1, NOW(), NOW()),
(30, 6, 'Bảo hành dịch vụ', 'Chế độ bảo hành và hỗ trợ sau khi hoàn thành', 1, NOW(), NOW()),

-- Ốp lát (category_id = 7)
(31, 7, 'Chất lượng ốp lát', 'Đánh giá độ phẳng, thẳng và thẩm mỹ của công trình', 1, NOW(), NOW()),
(32, 7, 'Tính chuyên nghiệp', 'Thái độ làm việc và chuẩn bị dụng cụ đầy đủ', 1, NOW(), NOW()),
(33, 7, 'Đúng tiến độ', 'Khả năng hoàn thành công việc theo kế hoạch', 1, NOW(), NOW()),
(34, 7, 'Giá cả cạnh tranh', 'Mức giá dịch vụ có phù hợp với chất lượng không', 1, NOW(), NOW()),
(35, 7, 'Kỹ thuật thi công', 'Kỹ năng ốp lát và xử lý các góc khó', 1, NOW(), NOW()),
(36, 7, 'Tư vấn vật liệu', 'Khả năng tư vấn lựa chọn gạch và vật liệu phù hợp', 1, NOW(), NOW()),

-- Hàn (category_id = 8)
(37, 8, 'Chất lượng hàn', 'Đánh giá độ chắc chắn và thẩm mỹ của mối hàn', 1, NOW(), NOW()),
(38, 8, 'Tính chuyên nghiệp', 'Thái độ làm việc và tuân thủ an toàn lao động', 1, NOW(), NOW()),
(39, 8, 'Tốc độ hoàn thành', 'Thời gian hoàn thành công việc hàn', 1, NOW(), NOW()),
(40, 8, 'Giá cả hợp lý', 'Mức giá dịch vụ có cạnh tranh không', 1, NOW(), NOW()),
(41, 8, 'Kỹ thuật hàn', 'Trình độ kỹ thuật hàn và sử dụng thiết bị', 1, NOW(), NOW()),
(42, 8, 'An toàn lao động', 'Tuân thủ quy trình an toàn khi hàn', 1, NOW(), NOW()),

-- Vệ sinh (category_id = 9)
(43, 9, 'Chất lượng vệ sinh', 'Đánh giá độ sạch sẽ và kỹ lưỡng trong công việc', 1, NOW(), NOW()),
(44, 9, 'Tính chuyên nghiệp', 'Thái độ phục vụ và trang phục gọn gàng', 1, NOW(), NOW()),
(45, 9, 'Tốc độ làm việc', 'Thời gian hoàn thành công việc vệ sinh', 1, NOW(), NOW()),
(46, 9, 'Giá cả phù hợp', 'Mức giá dịch vụ có hợp lý không', 1, NOW(), NOW()),
(47, 9, 'Sử dụng hóa chất', 'Khả năng sử dụng đúng hóa chất vệ sinh', 1, NOW(), NOW()),
(48, 9, 'Chu đáo tỉ mỉ', 'Mức độ tỉ mỉ và chu đáo trong từng chi tiết', 1, NOW(), NOW()),

-- Sửa chữa tổng hợp (category_id = 10)
(49, 10, 'Chất lượng sửa chữa', 'Đánh giá chất lượng sửa chữa các hạng mục', 1, NOW(), NOW()),
(50, 10, 'Tính chuyên nghiệp', 'Thái độ phục vụ và cách tiếp cận công việc', 1, NOW(), NOW()),
(51, 10, 'Tốc độ xử lý', 'Thời gian xử lý và hoàn thành các công việc', 1, NOW(), NOW()),
(52, 10, 'Giá cả hợp lý', 'Mức giá dịch vụ có cạnh tranh không', 1, NOW(), NOW()),
(53, 10, 'Đa năng kỹ thuật', 'Khả năng xử lý nhiều loại công việc khác nhau', 1, NOW(), NOW()),
(54, 10, 'Tư vấn giải pháp', 'Khả năng tư vấn giải pháp tối ưu cho khách hàng', 1, NOW(), NOW());

-- Tạo foreign key nếu bảng categories tồn tại
-- ALTER TABLE `review_criteria` ADD CONSTRAINT `fk_review_criteria_category` 
-- FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 