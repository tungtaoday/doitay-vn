import mysql.connector
from mysql.connector import Error
import hashlib
import bcrypt
import re
from datetime import datetime
from config import MYSQL_CONFIG

def validate_email(email):
    """Kiểm tra format email hợp lệ"""
    pattern = r'^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$'
    return re.match(pattern, email) is not None

def hash_password(password):
    """Hash mật khẩu bằng bcrypt"""
    # Sử dụng bcrypt để hash mật khẩu (an toàn hơn)
    salt = bcrypt.gensalt()
    hashed = bcrypt.hashpw(password.encode('utf-8'), salt)
    return hashed.decode('utf-8')

def hash_password_md5(password):
    """Hash mật khẩu bằng MD5 (backup nếu không có bcrypt)"""
    return hashlib.md5(password.encode()).hexdigest()

def create_admin_table_if_not_exists(connection):
    """Tạo bảng admin nếu chưa tồn tại"""
    cursor = connection.cursor()
    
    create_table_query = """
    CREATE TABLE IF NOT EXISTS admin (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        full_name VARCHAR(100),
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        last_login TIMESTAMP NULL,
        INDEX idx_username (username),
        INDEX idx_email (email)
    )
    """
    
    try:
        cursor.execute(create_table_query)
        connection.commit()
        print("✅ Bảng admin đã được tạo/kiểm tra thành công!")
    except Error as e:
        print(f"❌ Lỗi tạo bảng admin: {e}")
    finally:
        cursor.close()

def check_user_exists(connection, username, email):
    """Kiểm tra user đã tồn tại chưa"""
    cursor = connection.cursor()
    
    try:
        # Kiểm tra username
        cursor.execute("SELECT id FROM admin WHERE username = %s", (username,))
        if cursor.fetchone():
            return "username"
        
        # Kiểm tra email
        cursor.execute("SELECT id FROM admin WHERE email = %s", (email,))
        if cursor.fetchone():
            return "email"
        
        return None
    except Error as e:
        print(f"❌ Lỗi kiểm tra user: {e}")
        return "error"
    finally:
        cursor.close()

def add_admin_user(username, email, password, full_name=None):
    """Thêm admin user mới"""
    
    # Validate email
    if not validate_email(email):
        print(f"❌ Email không hợp lệ: {email}")
        return False
    
    # Validate username (chỉ chứa chữ cái, số, underscore)
    if not re.match(r'^[a-zA-Z0-9_]+$', username):
        print(f"❌ Username chỉ được chứa chữ cái, số và dấu gạch dưới")
        return False
    
    try:
        connection = mysql.connector.connect(**MYSQL_CONFIG)
        
        # Tạo bảng nếu chưa tồn tại
        create_admin_table_if_not_exists(connection)
        
        # Kiểm tra user đã tồn tại
        exists = check_user_exists(connection, username, email)
        if exists == "username":
            print(f"❌ Username '{username}' đã tồn tại!")
            return False
        elif exists == "email":
            print(f"❌ Email '{email}' đã tồn tại!")
            return False
        elif exists == "error":
            return False
        
        # Hash mật khẩu
        try:
            hashed_password = hash_password(password)
            hash_method = "bcrypt"
        except:
            # Fallback to MD5 if bcrypt is not available
            hashed_password = hash_password_md5(password)
            hash_method = "md5"
            
        print(f"🔐 Đã hash mật khẩu bằng {hash_method}")
        
        # Insert user mới
        cursor = connection.cursor()
        insert_query = """
        INSERT INTO admin (username, email, password, full_name, status)
        VALUES (%s, %s, %s, %s, %s)
        """
        
        cursor.execute(insert_query, (username, email, hashed_password, full_name, 'active'))
        connection.commit()
        
        user_id = cursor.lastrowid
        print(f"✅ Đã thêm admin user thành công!")
        print(f"   - ID: {user_id}")
        print(f"   - Username: {username}")
        print(f"   - Email: {email}")
        print(f"   - Full name: {full_name or 'Chưa cập nhật'}")
        print(f"   - Status: active")
        
        return True
        
    except Error as e:
        print(f"❌ Lỗi thêm admin user: {e}")
        return False
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def list_admin_users():
    """Hiển thị danh sách admin users"""
    try:
        connection = mysql.connector.connect(**MYSQL_CONFIG)
        cursor = connection.cursor()
        
        cursor.execute("""
            SELECT id, username, email, full_name, status, created_at, last_login
            FROM admin 
            ORDER BY id
        """)
        
        users = cursor.fetchall()
        
        if users:
            print(f"\n👥 Danh sách Admin Users ({len(users)} users):")
            print(f"{'ID':<4} {'Username':<15} {'Email':<30} {'Full Name':<20} {'Status':<8} {'Created':<12}")
            print("-" * 95)
            
            for user in users:
                created_date = user[5].strftime('%Y-%m-%d') if user[5] else 'N/A'
                full_name = user[3][:19] if user[3] else 'N/A'
                print(f"{user[0]:<4} {user[1]:<15} {user[2]:<30} {full_name:<20} {user[4]:<8} {created_date:<12}")
        else:
            print("📭 Chưa có admin user nào trong hệ thống")
            
    except Error as e:
        print(f"❌ Lỗi lấy danh sách admin: {e}")
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

if __name__ == "__main__":
    print("👨‍💼 THÊM ADMIN USER MỚI")
    print("=" * 50)
    
    # Thông tin user cần thêm
    username = "tungtaoday"
    email = "nguyentung0910@gmail.com"
    password = "vuivui@123"
    full_name = "Nguyễn Tùng"  # Có thể thay đổi hoặc để None
    
    print(f"📝 Thông tin user sẽ được thêm:")
    print(f"   - Username: {username}")
    print(f"   - Email: {email}")
    print(f"   - Password: {password} (sẽ được hash)")
    print(f"   - Full name: {full_name}")
    
    confirm = input("\n❓ Xác nhận thêm user này? (y/n): ").lower().strip()
    
    if confirm in ['y', 'yes', 'có']:
        success = add_admin_user(username, email, password, full_name)
        
        if success:
            print(f"\n🎉 Hoàn tất! User '{username}' đã được thêm vào hệ thống.")
            
            # Hiển thị danh sách users
            list_admin_users()
        else:
            print(f"\n💥 Có lỗi xảy ra khi thêm user!")
    else:
        print("❌ Đã hủy thao tác thêm user.") 