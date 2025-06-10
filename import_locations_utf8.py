import pandas as pd
import pymysql
import configparser
from sqlalchemy import create_engine
import warnings
warnings.filterwarnings('ignore')

def import_locations_to_mysql():
    """Import locations CSV to MySQL with proper UTF-8 encoding"""
    
    try:
        # Read CSV with explicit UTF-8 encoding
        print("Đọc file locations.csv...")
        df = pd.read_csv('core/locations.csv', encoding='utf-8-sig')
        
        # Display first few rows to verify
        print("Dữ liệu mẫu:")
        print(df.head())
        print(f"Tổng số dòng: {len(df)}")
        
        # Clean column names
        df.columns = df.columns.str.strip().str.replace('"', '')
        
        # Rename columns to match database convention
        column_mapping = {
            'City': 'city',
            'City_code': 'city_code', 
            'District': 'district',
            'District_code': 'district_code',
            'Ward': 'ward', 
            'Ward_code': 'ward_code',
            'Level': 'level',
            'English_name': 'english_name'
        }
        
        df = df.rename(columns=column_mapping)
        
        # Clean data - remove quotes and handle nulls
        for col in df.columns:
            if df[col].dtype == 'object':
                df[col] = df[col].astype(str).str.replace('"', '').str.strip()
                df[col] = df[col].replace('nan', None)
                df[col] = df[col].replace('', None)
        
        # Database connection
        print("Kết nối database...")
        engine = create_engine(
            'mysql+pymysql://root:@localhost/business_directory',
            charset='utf8mb4',
            collation='utf8mb4_unicode_ci'
        )
        
        # Create table if not exists
        create_table_sql = """
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
        """
        
        with engine.connect() as conn:
            conn.execute(create_table_sql)
            conn.commit()
        
        print("Tạo bảng locations thành công!")
        
        # Import data in chunks to avoid memory issues
        chunk_size = 1000
        total_rows = 0
        
        print("Bắt đầu import dữ liệu...")
        for i in range(0, len(df), chunk_size):
            chunk_df = df.iloc[i:i+chunk_size].copy()
            
            # Import chunk
            chunk_df.to_sql(
                'locations', 
                engine, 
                if_exists='append', 
                index=False,
                method='multi'
            )
            
            total_rows += len(chunk_df)
            print(f"Đã import {total_rows}/{len(df)} dòng...")
        
        print(f"Hoàn thành! Đã import {total_rows} địa điểm vào database.")
        
        # Verify data
        with engine.connect() as conn:
            result = conn.execute("SELECT COUNT(*) as count FROM locations").fetchone()
            print(f"Kiểm tra: Có {result[0]} dòng trong bảng locations")
            
            # Show sample Vietnamese data
            sample = conn.execute("""
                SELECT city, district, ward 
                FROM locations 
                WHERE city LIKE '%Hà Nội%' 
                LIMIT 5
            """).fetchall()
            
            print("\nDữ liệu mẫu (tiếng Việt):")
            for row in sample:
                print(f"- {row[0]} > {row[1]} > {row[2]}")
        
        return True
        
    except Exception as e:
        print(f"Lỗi: {str(e)}")
        return False

if __name__ == "__main__":
    success = import_locations_to_mysql()
    if success:
        print("\n✅ Import thành công!")
    else:
        print("\n❌ Import thất bại!") 