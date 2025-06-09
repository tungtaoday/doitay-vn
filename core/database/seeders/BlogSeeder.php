<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Frontend;

class BlogSeeder extends Seeder
{
    public function run()
    {
        $blogs = [
            [
                'data_keys' => 'blog.element',
                'data_values' => [
                    'title' => 'Top 10 Thợ Điện Uy Tín Tại TP.HCM',
                    'description' => 'Khám phá danh sách các thợ điện chuyên nghiệp và uy tín nhất tại TP.HCM. Với nhiều năm kinh nghiệm và kỹ thuật cao, họ sẽ đảm bảo an toàn cho ngôi nhà của bạn.',
                    'image' => 'blog1.jpg'
                ],
                'slug' => 'top-10-tho-dien-uy-tin-tai-tp-hcm',
                'tempname' => 'basic',
            ],
            [
                'data_keys' => 'blog.element',
                'data_values' => [
                    'title' => 'Hướng Dẫn Chọn Thợ Sửa Chữa Nhà Chuyên Nghiệp',
                    'description' => 'Những điều cần lưu ý khi chọn thợ sửa chữa nhà để đảm bảo chất lượng công việc và tiết kiệm chi phí. Từ kinh nghiệm đến báo giá hợp lý.',
                    'image' => 'blog2.jpg'
                ],
                'slug' => 'huong-dan-chon-tho-sua-chua-nha-chuyen-nghiep',
                'tempname' => 'basic',
            ],
            [
                'data_keys' => 'blog.element',
                'data_values' => [
                    'title' => '5 Lưu Ý Quan Trọng Khi Thuê Thợ Sơn Nhà',
                    'description' => 'Sơn nhà không chỉ đơn giản là tô màu mà còn cần kỹ thuật và kinh nghiệm. Cùng tìm hiểu 5 lưu ý quan trọng khi thuê thợ sơn nhà chuyên nghiệp.',
                    'image' => 'blog3.jpg'
                ],
                'slug' => '5-luu-y-quan-trong-khi-thue-tho-son-nha',
                'tempname' => 'basic',
            ],
            [
                'data_keys' => 'blog.element',
                'data_values' => [
                    'title' => 'Bảng Giá Dịch Vụ Thợ Điện Nước 2025',
                    'description' => 'Cập nhật bảng giá mới nhất cho các dịch vụ thợ điện nước tại TP.HCM và Hà Nội năm 2025. Giúp bạn có cái nhìn tổng quan về chi phí dịch vụ.',
                    'image' => 'blog4.jpg'
                ],
                'slug' => 'bang-gia-dich-vu-tho-dien-nuoc-2025',
                'tempname' => 'basic',
            ],
            [
                'data_keys' => 'blog.element',
                'data_values' => [
                    'title' => 'Kinh Nghiệm Tìm Thợ Mộc Uy Tín Làm Tủ Bếp',
                    'description' => 'Làm tủ bếp đòi hỏi kỹ thuật cao và tay nghề chuyên môn. Chia sẻ kinh nghiệm tìm thợ mộc uy tín để có bộ tủ bếp đẹp và bền.',
                    'image' => 'blog5.jpg'
                ],
                'slug' => 'kinh-nghiem-tim-tho-moc-uy-tin-lam-tu-bep',
                'tempname' => 'basic',
            ]
        ];

        foreach($blogs as $blogData) {
            Frontend::create($blogData);
        }

        echo "Created 5 blog entries successfully!\n";
    }
} 