import chardet
import csv
import mysql.connector
from mysql.connector import Error
import os

def detect_encoding(file_path):
    """Detect file encoding"""
    with open(file_path, 'rb') as f:
        raw_data = f.read(10000)  # Read first 10KB
        result = chardet.detect(raw_data)
        return result

def convert_csv_to_utf8(input_file, output_file):
    """Convert CSV to UTF-8 encoding"""
    try:
        # Detect current encoding
        detection = detect_encoding(input_file)
        current_encoding = detection['encoding']
        confidence = detection['confidence']
        
        print(f"🔍 Detected encoding: {current_encoding} (confidence: {confidence:.2f})")
        
        # Try different encodings if detection is not confident
        encodings_to_try = [current_encoding, 'utf-8', 'utf-8-sig', 'cp1252', 'iso-8859-1', 'ascii']
        
        for encoding in encodings_to_try:
            if encoding is None:
                continue
                
            try:
                print(f"🔄 Trying encoding: {encoding}")
                
                with open(input_file, 'r', encoding=encoding, errors='ignore') as infile:
                    content = infile.read()
                
                # Write as UTF-8 with BOM
                with open(output_file, 'w', encoding='utf-8-sig', newline='') as outfile:
                    outfile.write(content)
                
                print(f"✅ Successfully converted to UTF-8: {output_file}")
                return True
                
            except Exception as e:
                print(f"❌ Failed with {encoding}: {str(e)}")
                continue
        
        return False
        
    except Exception as e:
        print(f"❌ Error converting file: {str(e)}")
        return False

def import_csv_to_mysql(csv_file):
    """Import CSV to MySQL with proper encoding"""
    try:
        # Database connection
        connection = mysql.connector.connect(
            host='localhost',
            database='t_review_db',
            user='root',
            password='vuivui@123',
            charset='utf8mb4',
            collation='utf8mb4_unicode_ci',
            use_unicode=True
        )
        
        if connection.is_connected():
            cursor = connection.cursor()
            print("✅ Connected to MySQL database")
            
            # Create table
            create_table_query = """
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            """
            
            cursor.execute(create_table_query)
            print("✅ Table created successfully")
            
            # Clear existing data
            cursor.execute("TRUNCATE TABLE locations")
            print("✅ Cleared existing data")
            
            # Read and insert CSV data
            insert_query = """
            INSERT INTO locations (city, city_code, district, district_code, ward, ward_code, level, english_name)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
            """
            
            row_count = 0
            success_count = 0
            
            with open(csv_file, 'r', encoding='utf-8-sig') as file:
                csv_reader = csv.reader(file)
                
                # Skip header
                next(csv_reader)
                
                for row in csv_reader:
                    row_count += 1
                    
                    try:
                        # Clean data
                        city = row[0].strip('"').strip()
                        city_code = row[1].strip('"').strip()
                        district = row[2].strip('"').strip()
                        district_code = row[3].strip('"').strip()
                        ward = row[4].strip('"').strip()
                        ward_code = row[5].strip('"').strip()
                        level = row[6].strip('"').strip()
                        english_name = row[7].strip('"').strip() if len(row) > 7 else ''
                        
                        # Insert data
                        cursor.execute(insert_query, (
                            city, city_code, district, district_code,
                            ward, ward_code, level, english_name
                        ))
                        
                        success_count += 1
                        
                        if row_count % 1000 == 0:
                            print(f"📊 Processed: {row_count} rows, Success: {success_count}")
                            
                    except Exception as e:
                        print(f"❌ Error at row {row_count}: {str(e)}")
                        print(f"Data: {row}")
            
            # Commit changes
            connection.commit()
            
            print(f"\n🎉 Import completed!")
            print(f"📊 Total rows processed: {row_count}")
            print(f"✅ Successfully imported: {success_count}")
            
            # Verify data
            cursor.execute("SELECT COUNT(*) FROM locations")
            total = cursor.fetchone()[0]
            print(f"🔍 Database verification: {total} rows in locations table")
            
            # Show sample data
            cursor.execute("SELECT city, district, ward FROM locations WHERE city LIKE '%Hà Nội%' LIMIT 5")
            samples = cursor.fetchall()
            
            print("\n📋 Sample Vietnamese data:")
            for sample in samples:
                print(f"- {sample[0]} > {sample[1]} > {sample[2]}")
            
            return True
            
    except Error as e:
        print(f"❌ MySQL Error: {str(e)}")
        return False
        
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("🔌 MySQL connection closed")

def main():
    """Main function"""
    input_file = 'core/locations.csv'
    utf8_file = 'core/locations_utf8.csv'
    
    print("🚀 Starting CSV encoding fix and import process...")
    
    # Step 1: Check if input file exists
    if not os.path.exists(input_file):
        print(f"❌ File not found: {input_file}")
        return
    
    # Step 2: Convert to UTF-8
    print(f"\n📝 Converting {input_file} to UTF-8...")
    if convert_csv_to_utf8(input_file, utf8_file):
        print("✅ File conversion successful")
    else:
        print("❌ File conversion failed, trying original file...")
        utf8_file = input_file
    
    # Step 3: Import to MySQL
    print(f"\n💾 Importing {utf8_file} to MySQL...")
    if import_csv_to_mysql(utf8_file):
        print("✅ Import successful!")
    else:
        print("❌ Import failed!")
    
    # Clean up
    if utf8_file != input_file and os.path.exists(utf8_file):
        os.remove(utf8_file)
        print(f"🧹 Cleaned up temporary file: {utf8_file}")

if __name__ == "__main__":
    main() 