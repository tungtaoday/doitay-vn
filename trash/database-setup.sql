-- ========================================
-- T-REVIEW DATABASE SETUP FOR ALL ENVIRONMENTS
-- ========================================

-- 1. CREATE DEVELOPMENT DATABASE
DROP DATABASE IF EXISTS t_review_dev;
CREATE DATABASE t_review_dev 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 2. CREATE STAGING DATABASE  
DROP DATABASE IF EXISTS t_review_staging;
CREATE DATABASE t_review_staging 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 3. CREATE PRODUCTION DATABASE
DROP DATABASE IF EXISTS t_review_production;
CREATE DATABASE t_review_production 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

-- 4. CREATE PRODUCTION USER (Security best practice)
DROP USER IF EXISTS 't_review_user'@'localhost';
CREATE USER 't_review_user'@'localhost' IDENTIFIED BY 'T_Review_Prod_2024!@#';

-- Grant privileges to production user
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES 
ON t_review_production.* TO 't_review_user'@'localhost';

-- 5. CREATE STAGING USER (Optional - for better security)
DROP USER IF EXISTS 't_review_staging'@'localhost';
CREATE USER 't_review_staging'@'localhost' IDENTIFIED BY 'T_Review_Staging_2024!';

-- Grant privileges to staging user
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES 
ON t_review_staging.* TO 't_review_staging'@'localhost';

-- 6. FLUSH PRIVILEGES
FLUSH PRIVILEGES;

-- 7. SHOW CREATED DATABASES
SHOW DATABASES LIKE 't_review%';

-- 8. SHOW CREATED USERS
SELECT User, Host FROM mysql.user WHERE User LIKE 't_review%';

-- ========================================
-- USAGE INSTRUCTIONS:
-- ========================================
-- 
-- Run this script as MySQL root user:
-- mysql -u root -p < database-setup.sql
--
-- Then update your .env files with:
--
-- DEVELOPMENT (.env.development):
-- DB_DATABASE=t_review_dev
-- DB_USERNAME=root
-- DB_PASSWORD=Vuivui@123
--
-- STAGING (.env.staging):
-- DB_DATABASE=t_review_staging  
-- DB_USERNAME=t_review_staging
-- DB_PASSWORD=T_Review_Staging_2024!
--
-- PRODUCTION (.env.production):
-- DB_DATABASE=t_review_production
-- DB_USERNAME=t_review_user
-- DB_PASSWORD=T_Review_Prod_2024!@#
-- ======================================== 