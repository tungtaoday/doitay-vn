-- Fix Vietnamese charset encoding issues
-- Step 1: Set database charset to UTF-8
ALTER DATABASE t_review_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 2: Fix companies table charset
ALTER TABLE companies CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 3: Fix users table charset  
ALTER TABLE users CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 4: Fix other tables that might have Vietnamese text
ALTER TABLE categories CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE ratings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE leads CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE notifications CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 5: Check current charset
SHOW VARIABLES LIKE 'character_set%';
SHOW VARIABLES LIKE 'collation%';

-- Step 6: Sample data check
SELECT id, name, details FROM companies WHERE id IN (11, 12, 17) LIMIT 3; 