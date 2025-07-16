import mysql.connector
from mysql.connector import Error
from config import MYSQL_CONFIG

def check_imported_data():
    """Kiểm tra dữ liệu đã được import vào bảng vietnam_districts"""
    try:
        connection = mysql.connector.connect(**MYSQL_CONFIG)
        cursor = connection.cursor()
        
        # Đếm tổng số bản ghi
        cursor.execute("SELECT COUNT(*) FROM vietnam_districts")
        total_count = cursor.fetchone()[0]
        print(f"📊 Tổng số bản ghi trong bảng vietnam_districts: {total_count}")
        
        # Hiển thị 10 bản ghi đầu tiên
        cursor.execute("SELECT * FROM vietnam_districts ORDER BY id LIMIT 10")
        records = cursor.fetchall()
        
        print(f"\n📄 10 bản ghi đầu tiên:")
        print(f"{'ID':<5} {'Quận/Huyện':<30} {'Tỉnh/TP':<25} {'Mã QH':<10} {'Mã Tỉnh':<10}")
        print("-" * 85)
        
        for record in records:
            print(f"{record[0]:<5} {record[1][:29]:<30} {record[2][:24]:<25} {record[3]:<10} {record[4]:<10}")
        
        # Thống kê theo tỉnh
        cursor.execute("""
            SELECT province_name, COUNT(*) as district_count 
            FROM vietnam_districts 
            GROUP BY province_name 
            ORDER BY district_count DESC 
            LIMIT 10
        """)
        province_stats = cursor.fetchall()
        
        print(f"\n🏙️ Top 10 tỉnh/thành có nhiều quận/huyện nhất:")
        print(f"{'Tỉnh/Thành phố':<30} {'Số quận/huyện':<15}")
        print("-" * 45)
        for province, count in province_stats:
            print(f"{province[:29]:<30} {count:<15}")
            
        # Kiểm tra dữ liệu trống
        cursor.execute("""
            SELECT 
                SUM(CASE WHEN district_name = '' OR district_name IS NULL THEN 1 ELSE 0 END) as empty_district,
                SUM(CASE WHEN province_name = '' OR province_name IS NULL THEN 1 ELSE 0 END) as empty_province,
                SUM(CASE WHEN district_code = '' OR district_code IS NULL THEN 1 ELSE 0 END) as empty_district_code,
                SUM(CASE WHEN province_code = '' OR province_code IS NULL THEN 1 ELSE 0 END) as empty_province_code
            FROM vietnam_districts
        """)
        empty_stats = cursor.fetchone()
        
        print(f"\n⚠️  Thống kê dữ liệu trống:")
        print(f"   - Tên quận/huyện trống: {empty_stats[0]}")
        print(f"   - Tên tỉnh/thành trống: {empty_stats[1]}")
        print(f"   - Mã quận/huyện trống: {empty_stats[2]}")
        print(f"   - Mã tỉnh/thành trống: {empty_stats[3]}")
        
    except Error as e:
        print(f"❌ Lỗi kết nối MySQL: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print(f"\n✅ Đã đóng kết nối database")

if __name__ == "__main__":
    check_imported_data() 