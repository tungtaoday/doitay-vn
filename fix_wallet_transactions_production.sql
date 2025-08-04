-- ========================================
-- FIX WALLET_TRANSACTIONS TABLE PRODUCTION
-- ========================================
-- Thêm column company_wallet_id và cấu trúc đầy đủ

USE t_review_production;

-- Kiểm tra hiện trạng
SELECT 'BEFORE: Current wallet_transactions structure' as status;
DESCRIBE wallet_transactions;

-- Backup dữ liệu hiện tại nếu có
CREATE TABLE wallet_transactions_backup AS SELECT * FROM wallet_transactions;

-- Drop và tạo lại bảng với cấu trúc đúng
DROP TABLE IF EXISTS wallet_transactions;

CREATE TABLE wallet_transactions (
    id bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
    company_wallet_id bigint unsigned NOT NULL,
    type enum('credit','debit') NOT NULL,
    amount decimal(15,2) NOT NULL,
    balance_before decimal(15,2) NOT NULL,
    balance_after decimal(15,2) NOT NULL,
    transaction_type enum('welcome_bonus','referral_bonus','lead_purchase','admin_adjustment','refund','deposit','customer_info_access') NOT NULL,
    description varchar(255) NOT NULL,
    metadata json NULL,
    reference_id varchar(255) NULL,
    status enum('pending','completed','failed','cancelled') NOT NULL DEFAULT 'completed',
    processed_by bigint unsigned NULL,
    admin_notes text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    
    INDEX idx_company_wallet_type (company_wallet_id, type),
    INDEX idx_transaction_type_created (transaction_type, created_at),
    INDEX idx_reference_id (reference_id),
    INDEX idx_processed_by (processed_by),
    
    FOREIGN KEY (company_wallet_id) REFERENCES company_wallets(id) ON DELETE CASCADE,
    FOREIGN KEY (processed_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- Restore dữ liệu nếu có (chỉ những column tương thích)
INSERT INTO wallet_transactions (
    id, status, processed_by, admin_notes, created_at, updated_at,
    company_wallet_id, type, amount, balance_before, balance_after, 
    transaction_type, description
)
SELECT 
    id, status, processed_by, admin_notes, created_at, updated_at,
    1 as company_wallet_id,  -- Default company wallet
    'credit' as type,
    0.00 as amount,
    0.00 as balance_before, 
    0.00 as balance_after,
    'admin_adjustment' as transaction_type,
    'Migrated from old structure' as description
FROM wallet_transactions_backup
WHERE EXISTS (SELECT 1 FROM wallet_transactions_backup);

-- Kiểm tra kết quả
SELECT 'AFTER: New wallet_transactions structure' as status;
DESCRIBE wallet_transactions;

SELECT 'Rows count' as info, COUNT(*) as count FROM wallet_transactions;

-- Drop backup table
DROP TABLE IF EXISTS wallet_transactions_backup;

SELECT 'WALLET_TRANSACTIONS TABLE FIXED SUCCESSFULLY!' as final_status; 