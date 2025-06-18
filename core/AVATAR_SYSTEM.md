# Hệ Thống Avatar Mặc Định

## Tổng quan

Hệ thống avatar mặc định tự động tạo avatar đẹp cho người dùng và công ty khi họ chưa upload ảnh đại diện.

## Tính năng

### 1. Avatar SVG Mặc định
- **User avatars**: 10 avatar mặc định với màu sắc và biểu tượng khác nhau
- **Company avatars**: 10 avatar mặc định với biểu tượng tòa nhà/công ty

### 2. Avatar từ Tên/Chữ cái đầu
- Tự động tạo avatar từ chữ cái đầu của tên
- Màu nền ngẫu nhiên nhưng nhất quán theo ID
- Hỗ trợ cả tên đầy đủ và tên đơn

### 3. Hệ thống Fallback Thông minh
```
Ưu tiên avatar:
1. Ảnh đã upload (nếu có)
2. Avatar từ tên/chữ cái đầu (nếu có tên)
3. Avatar SVG mặc định (fallback cuối)
```

## Cách sử dụng

### Trong Blade Templates

```php
<!-- Avatar người dùng -->
<img src="{{ getUserAvatar($user) }}" alt="User Avatar">

<!-- Avatar công ty -->
<img src="{{ getCompanyAvatar($company) }}" alt="Company Avatar">

<!-- Tạo avatar từ tên -->
<img src="{{ generateAvatar('Nguyen Van A', 1) }}" alt="Generated Avatar">

<!-- Avatar ngẫu nhiên -->
<img src="{{ getRandomUserAvatar($userId) }}" alt="Random Avatar">
<img src="{{ getRandomCompanyAvatar($companyId) }}" alt="Random Company Avatar">
```

### Trong PHP Controller

```php
use App\Helpers\AvatarHelper;

// Lấy avatar người dùng
$avatar = AvatarHelper::getUserAvatar($user);

// Lấy avatar công ty
$avatar = AvatarHelper::getCompanyAvatar($company);

// Tạo avatar từ tên
$avatar = AvatarHelper::generateInitialsAvatar('Tran Thi B', 2);
```

## Cấu trúc File

```
core/
├── app/
│   └── Helpers/
│       └── AvatarHelper.php          # Class chính xử lý avatar
├── app/Http/Helpers/
│   └── helpers.php                   # Global helper functions
└── public/assets/images/avatars/     # Thư mục chứa avatar SVG
    ├── user-1.svg                    # Avatar người dùng 1-10
    ├── user-2.svg
    ├── ...
    ├── company-1.svg                 # Avatar công ty 1-10
    ├── company-2.svg
    └── ...
```

## Tính năng nâng cao

### 1. Consistency (Nhất quán)
- Cùng một ID sẽ luôn có cùng avatar
- Đảm bảo trải nghiệm người dùng nhất quán

### 2. Performance
- Avatar SVG nhẹ và tải nhanh
- Base64 encoded cho initials avatar
- Không cần database query

### 3. Customizable
- Dễ dàng thêm/sửa màu sắc
- Có thể thay đổi số lượng avatar mặc định
- Tùy chỉnh logic fallback

## Ví dụ Output

### Avatar từ chữ cái đầu:
```
Nguyen Van A → "NV" với nền màu #FF6B6B
Tran Thi B   → "TT" với nền màu #4ECDC4
Admin        → "AD" với nền màu #45B7D1
```

### Avatar mặc định:
```
User ID 1   → user-2.svg (màu #4ECDC4)
User ID 2   → user-3.svg (màu #45B7D1)
Company ID 1 → company-2.svg (màu #4ECDC4)
```

## Lợi ích

1. **UX tốt hơn**: Không còn icon mặc định nhàm chán
2. **Professional**: Avatar đẹp và nhất quán
3. **Performance**: Tải nhanh, không cần database
4. **Maintainable**: Code sạch và dễ bảo trì
5. **Scalable**: Dễ mở rộng và tùy chỉnh

## Troubleshooting

### Avatar không hiển thị:
1. Kiểm tra thư mục `public/assets/images/avatars/` có tồn tại
2. Đảm bảo file SVG có quyền đọc
3. Kiểm tra helper functions đã được load

### Avatar không nhất quán:
1. Đảm bảo truyền đúng ID
2. Kiểm tra logic modulo trong AvatarHelper

---

**Phát triển bởi**: Hệ thống quản lý dự án
**Phiên bản**: 1.0
**Cập nhật**: {{ date('Y-m-d') }} 