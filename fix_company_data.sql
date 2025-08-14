-- SQL để sửa dữ liệu company ID 61
-- Tách riêng services và tags

-- 1. Xem dữ liệu hiện tại
SELECT id, name, tags, services, business_hours FROM companies WHERE id = 61;

-- 2. Làm sạch tags field - chỉ giữ lại tags thực sự
UPDATE companies 
SET tags = '["sửa chữa", "thi công", "bảo trì"]'
WHERE id = 61;

-- 3. Cập nhật services field với dữ liệu từ tags cũ
UPDATE companies 
SET services = '[
    {
        "name": "Sửa chữa điện",
        "description": "Dịch vụ sửa chữa điện dân dụng và công nghiệp",
        "price": "Liên hệ"
    },
    {
        "name": "Thi công xây dựng",
        "description": "Thi công các công trình xây dựng dân dụng",
        "price": "Liên hệ"
    }
]'
WHERE id = 61;

-- 4. Cập nhật business_hours field
UPDATE companies 
SET business_hours = '{
    "weekdays": {"start": "08:00", "end": "18:00"},
    "saturday": {"start": "08:00", "end": "16:00"},
    "sunday": {"status": "closed"},
    "24_7": false
}'
WHERE id = 61;

-- 5. Kiểm tra kết quả
SELECT id, name, 
       JSON_LENGTH(tags) as tags_count,
       JSON_LENGTH(services) as services_count,
       business_hours
FROM companies WHERE id = 61; 