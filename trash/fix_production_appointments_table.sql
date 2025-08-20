-- Fix appointments table trên production
-- Thêm các cột còn thiếu

USE t_review_production;

-- 1. Kiểm tra bảng hiện tại
SELECT 'Current table structure:' as info;
DESCRIBE appointments;

-- 2. Thêm các cột còn thiếu
ALTER TABLE appointments 
ADD COLUMN customer_info_unlocked TINYINT(1) NOT NULL DEFAULT 0 AFTER updated_at,
ADD COLUMN unlock_fee_paid DECIMAL(10,2) NULL AFTER customer_info_unlocked,
ADD COLUMN info_unlocked_at TIMESTAMP NULL AFTER unlock_fee_paid,
ADD COLUMN company_id BIGINT UNSIGNED NULL AFTER user_id,
ADD COLUMN recipient_name VARCHAR(255) NOT NULL AFTER company_id,
ADD COLUMN recipient_phone VARCHAR(255) NOT NULL AFTER recipient_name,
ADD COLUMN recipient_address TEXT NOT NULL AFTER recipient_phone,
ADD COLUMN appointment_date DATE NOT NULL AFTER recipient_address,
ADD COLUMN appointment_time TIME NOT NULL AFTER appointment_date,
ADD COLUMN notes TEXT NULL AFTER appointment_time,
ADD COLUMN status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending' AFTER notes;

-- 3. Kiểm tra bảng sau khi update
SELECT 'Updated table structure:' as info;
DESCRIBE appointments;

-- 4. Kiểm tra dữ liệu hiện tại
SELECT 'Current data count:' as info;
SELECT COUNT(*) as total_appointments FROM appointments;

-- 5. Kiểm tra các cột đã được thêm
SELECT 'New columns added:' as info;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 't_review_production' 
AND TABLE_NAME = 'appointments' 
AND COLUMN_NAME IN ('customer_info_unlocked', 'unlock_fee_paid', 'info_unlocked_at', 'company_id', 'recipient_name', 'recipient_phone', 'recipient_address', 'appointment_date', 'appointment_time', 'notes', 'status');

-- 6. Test insert với đầy đủ fields
SELECT 'Testing insert with full fields:' as info;
INSERT INTO appointments (
    user_id, 
    company_id, 
    recipient_name, 
    recipient_phone, 
    recipient_address, 
    appointment_date, 
    appointment_time, 
    status, 
    notes, 
    created_at, 
    updated_at
) VALUES (
    1, 
    58, 
    'Tung Test', 
    '0123456789', 
    'Test Address, Ho Chi Minh City', 
    CURDATE(), 
    '10:00:00', 
    'pending', 
    'Test appointment after table fix', 
    NOW(), 
    NOW()
);

-- 7. Kiểm tra appointment vừa tạo
SELECT 'Test appointment created:' as info;
SELECT * FROM appointments WHERE notes LIKE '%Test appointment after table fix%';

-- 8. Clean up test data
DELETE FROM appointments WHERE notes LIKE '%Test appointment after table fix%';

SELECT 'Test data cleaned up' as info; 
 