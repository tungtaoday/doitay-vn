import mysql.connector
from mysql.connector import Error
from config import MYSQL_CONFIG

def search_location(search_term):
    """Tìm kiếm địa điểm theo tên"""
    try:
        connection = mysql.connector.connect(**MYSQL_CONFIG)
        cursor = connection.cursor()
        
        # Tìm kiếm theo tên quận/huyện hoặc tỉnh/thành
        search_query = """
            SELECT id, district_name, province_name, district_code, province_code 
            FROM vietnam_districts 
            WHERE district_name LIKE %s OR province_name LIKE %s
            ORDER BY province_name, district_name
        """
        
        search_pattern = f"%{search_term}%"
        cursor.execute(search_query, (search_pattern, search_pattern))
        results = cursor.fetchall()
        
        if results:
            print(f"🔍 Tìm thấy {len(results)} kết quả cho '{search_term}':")
            print(f"{'ID':<5} {'Quận/Huyện':<30} {'Tỉnh/TP':<25} {'Mã QH':<10} {'Mã Tỉnh':<10}")
            print("-" * 85)
            
            for result in results:
                print(f"{result[0]:<5} {result[1][:29]:<30} {result[2][:24]:<25} {result[3]:<10} {result[4]:<10}")
        else:
            print(f"❌ Không tìm thấy kết quả nào cho '{search_term}'")
            
    except Error as e:
        print(f"❌ Lỗi kết nối MySQL: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def search_by_province(province_name):
    """Tìm tất cả quận/huyện thuộc một tỉnh"""
    try:
        connection = mysql.connector.connect(**MYSQL_CONFIG)
        cursor = connection.cursor()
        
        search_query = """
            SELECT district_name, district_code 
            FROM vietnam_districts 
            WHERE province_name LIKE %s
            ORDER BY district_name
        """
        
        search_pattern = f"%{province_name}%"
        cursor.execute(search_query, (search_pattern,))
        results = cursor.fetchall()
        
        if results:
            print(f"🏙️ Danh sách quận/huyện thuộc '{province_name}' ({len(results)} quận/huyện):")
            print(f"{'Tên quận/huyện':<40} {'Mã':<10}")
            print("-" * 50)
            
            for result in results:
                print(f"{result[0][:39]:<40} {result[1]:<10}")
        else:
            print(f"❌ Không tìm thấy tỉnh/thành nào có tên '{province_name}'")
            
    except Error as e:
        print(f"❌ Lỗi kết nối MySQL: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

if __name__ == "__main__":
    while True:
        print("\n" + "="*50)
        print("🔍 TÌM KIẾM ĐỊA ĐIỂM VIỆT NAM")
        print("="*50)
        print("1. Tìm kiếm theo tên")
        print("2. Tìm theo tỉnh/thành phố")
        print("3. Thoát")
        
        choice = input("\nChọn chức năng (1-3): ").strip()
        
        if choice == "1":
            search_term = input("Nhập tên cần tìm: ").strip()
            if search_term:
                search_location(search_term)
        elif choice == "2":
            province = input("Nhập tên tỉnh/thành phố: ").strip()
            if province:
                search_by_province(province)
        elif choice == "3":
            print("👋 Tạm biệt!")
            break
        else:
            print("❌ Lựa chọn không hợp lệ!")
        
        input("\nNhấn Enter để tiếp tục...") 