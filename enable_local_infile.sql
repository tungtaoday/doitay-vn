-- Enable local_infile and import CSV
-- Chạy lệnh: mysql -u root -p --local-infile=1 t_review_db < enable_local_infile.sql

-- Set UTF-8 encoding
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Enable local infile
SET GLOBAL local_infile = 'ON';

-- Create locations table if not exists
CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    city VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    city_code VARCHAR(10),
    district VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    district_code VARCHAR(10),
    ward VARCHAR(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    ward_code VARCHAR(10),
    level VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
    english_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_city_code (city_code),
    INDEX idx_district_code (district_code),
    INDEX idx_ward_code (ward_code),
    INDEX idx_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Clear existing data
TRUNCATE TABLE locations;

-- Load data from CSV file
LOAD DATA LOCAL INFILE 'C:/xampp/htdocs/core/locations.csv'
INTO TABLE locations
CHARACTER SET utf8mb4
FIELDS TERMINATED BY ',' 
ENCLOSED BY '"'
LINES TERMINATED BY '\n'
IGNORE 1 ROWS
(city, city_code, district, district_code, ward, ward_code, level, english_name);

-- Show results
SELECT 'Import completed successfully!' as message;
SELECT COUNT(*) as total_rows FROM locations;
SELECT 'Sample Vietnamese data:' as message;
SELECT city, district, ward FROM locations WHERE city LIKE '%Hà Nội%' LIMIT 5; 