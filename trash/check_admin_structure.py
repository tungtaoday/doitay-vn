import mysql.connector
from mysql.connector import Error
from config import MYSQL_CONFIG

def check_admin_table_structure():
    """Kiểm tra cấu trúc bảng admin"""
    try:
        connection = mysql.connector.connect(**MYSQL_CONFIG)
        cursor = connection.cursor()
        
        # Kiểm tra bảng admin có tồn tại không
        cursor.execute("SHOW TABLES LIKE 'admin%'")
        admin_tables = cursor.fetchall()
        
        print("🔍 CÁC BẢNG ADMIN TRONG DATABASE:")
        print("=" * 50)
        
        if admin_tables:
            for table in admin_tables:
                print(f"✅ Bảng: {table[0]}")
        else:
            print("❌ Không tìm thấy bảng admin nào")
            return
        
        # Kiểm tra cấu trúc bảng admins (số nhiều)
        for table_name in ['admin', 'admins']:
            try:
                cursor.execute(f"DESCRIBE {table_name}")
                columns = cursor.fetchall()
                
                print(f"\n📋 CẤU TRÚC BẢNG '{table_name.upper()}':")
                print("-" * 80)
                print(f"{'Field':<20} {'Type':<25} {'Null':<8} {'Key':<8} {'Default':<15} {'Extra':<15}")
                print("-" * 80)
                
                for column in columns:
                    field = column[0]
                    col_type = column[1]
                    null = column[2]
                    key = column[3]
                    default = str(column[4]) if column[4] is not None else 'NULL'
                    extra = column[5]
                    
                    print(f"{field:<20} {col_type:<25} {null:<8} {key:<8} {default:<15} {extra:<15}")
                
                # Đếm số bản ghi
                cursor.execute(f"SELECT COUNT(*) FROM {table_name}")
                count = cursor.fetchone()[0]
                print(f"\n📊 Tổng số admin: {count}")
                
                # Hiển thị danh sách admin (ẩn password)
                if count > 0:
                    cursor.execute(f"SELECT * FROM {table_name} LIMIT 5")
                    admins = cursor.fetchall()
                    
                    print(f"\n👥 DANH SÁCH ADMIN (tối đa 5 bản ghi):")
                    print("-" * 100)
                    
                    # Lấy tên cột
                    cursor.execute(f"SHOW COLUMNS FROM {table_name}")
                    column_names = [col[0] for col in cursor.fetchall()]
                    
                    # In header
                    header = ""
                    for col_name in column_names:
                        if col_name != 'password':  # Ẩn password
                            header += f"{col_name:<15} "
                    print(header)
                    print("-" * 100)
                    
                    # In data
                    for admin in admins:
                        row = ""
                        for i, col_name in enumerate(column_names):
                            if col_name != 'password':  # Ẩn password
                                value = str(admin[i]) if admin[i] is not None else 'NULL'
                                row += f"{value[:14]:<15} "
                        print(row)
                
                break  # Thoát loop khi tìm thấy bảng
                
            except Error as e:
                if "doesn't exist" in str(e):
                    continue  # Thử bảng tiếp theo
                else:
                    print(f"❌ Lỗi truy vấn bảng {table_name}: {e}")
        
        # Kiểm tra các bảng liên quan khác
        print(f"\n🔗 CÁC BẢNG LIÊN QUAN:")
        print("-" * 30)
        
        related_tables = ['admin_password_resets', 'admin_notifications', 'admin_logs']
        for table in related_tables:
            try:
                cursor.execute(f"SHOW TABLES LIKE '{table}'")
                result = cursor.fetchone()
                if result:
                    cursor.execute(f"SELECT COUNT(*) FROM {table}")
                    count = cursor.fetchone()[0]
                    print(f"✅ {table}: {count} bản ghi")
                else:
                    print(f"❌ {table}: Không tồn tại")
            except Error:
                print(f"❌ {table}: Lỗi truy vấn")
                
    except Error as e:
        print(f"❌ Lỗi kết nối database: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def check_auth_system():
    """Kiểm tra hệ thống authentication hiện tại"""
    print(f"\n🔐 THÔNG TIN HỆ THỐNG AUTHENTICATION:")
    print("=" * 50)
    print("📌 Từ phân tích code Laravel:")
    print("   - Guard: 'admin' (session-based)")
    print("   - Provider: 'admins' (Eloquent)")
    print("   - Model: App\\Models\\Admin")
    print("   - Login field: 'username' (không phải email)")
    print("   - Password reset table: 'admin_password_resets'")
    print("   - Routes: /admin/login, /admin/logout")
    print("   - Middleware: 'admin', 'admin.guest'")
    print("   - Captcha: Có sử dụng verifyCaptcha()")

if __name__ == "__main__":
    print("🔍 KIỂM TRA CẤU TRÚC ADMIN LOGIN SYSTEM")
    print("=" * 60)
    
    check_admin_table_structure()
    check_auth_system()
    
    print(f"\n💡 KHUYẾN NGHỊ:")
    print("   1. Cần cập nhật script add_admin_user.py để phù hợp với cấu trúc thực tế")
    print("   2. Kiểm tra xem login bằng 'username' hay 'email'")
    print("   3. Xác nhận phương thức hash password đang sử dụng")
    