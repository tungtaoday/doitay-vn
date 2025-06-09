# Cấu hình kết nối MySQL
MYSQL_CONFIG = {
    'host': 'localhost',        # Thay đổi nếu MySQL không chạy trên localhost
    'database': 'your_database_name',  # Thay đổi tên database của bạn
    'user': 'root',             # Thay đổi username MySQL
    'password': '',             # Thay đổi password MySQL (để trống nếu không có password)
    'port': 3306                # Port MySQL (mặc định là 3306)
}

# Đường dẫn file CSV
CSV_FILE_PATH = r"C:\Bussiness_2024\T-rating\Danhsach\locations.csv"

# Mapping cột từ CSV vào database
# Điều chỉnh theo cấu trúc thực tế của file CSV
COLUMN_MAPPING = {
    'csv_column_0': 'district_name',    # Cột đầu tiên trong CSV -> district_name
    'csv_column_1': 'province_name',    # Cột thứ hai trong CSV -> province_name  
    'csv_column_2': 'district_code',    # Cột thứ ba trong CSV -> district_code
    'csv_column_3': 'province_code'     # Cột thứ tư trong CSV -> province_code
} 