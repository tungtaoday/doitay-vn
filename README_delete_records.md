# 🗑️ Hướng dẫn xóa dữ liệu Users và Companies trên Production

## ⚠️ CẢNH BÁO QUAN TRỌNG

**TRƯỚC KHI CHẠY BẤT KỲ SCRIPT NÀO, BẠN PHẢI:**

1. ✅ **BACKUP DATABASE** - Tạo backup hoàn chỉnh
2. ✅ **KIỂM TRA KỸ** - Xem xét dữ liệu sẽ bị xóa
3. ✅ **THÔNG BÁO TEAM** - Báo cho team biết
4. ✅ **CHẠY TRONG MAINTENANCE MODE** - Để tránh ảnh hưởng users

## 📁 Files có sẵn

### 1. **`delete_today_records.sql`** - Script đầy đủ
- **Mục đích**: Xóa tất cả records tạo hôm nay
- **Sử dụng**: Khi muốn xóa dữ liệu theo ngày
- **Đặc điểm**: Có kiểm tra trước và sau khi xóa

### 2. **`delete_today_records_simple.sql`** - Script đơn giản
- **Mục đích**: Xóa nhanh dữ liệu hôm nay
- **Sử dụng**: Khi đã kiểm tra kỹ và muốn xóa nhanh
- **Đặc điểm**: Ngắn gọn, dễ chạy

### 3. **`delete_by_id_range.sql`** - Script theo ID range
- **Mục đích**: Xóa dữ liệu từ ID cụ thể trở đi
- **Sử dụng**: Khi muốn kiểm soát chính xác dữ liệu bị xóa
- **Đặc điểm**: An toàn nhất, có thể kiểm soát từng bước

## 🔧 Cách sử dụng trên Production

### **Bước 1: Backup Database**
```bash
# Backup toàn bộ database
mysqldump -u username -p database_name > backup_before_delete_$(date +%Y%m%d_%H%M%S).sql

# Hoặc backup từng bảng quan trọng
mysqldump -u username -p database_name users companies company_followers > backup_tables_$(date +%Y%m%d_%H%M%S).sql
```

### **Bước 2: Kiểm tra dữ liệu sẽ bị xóa**
```sql
-- Chạy phần kiểm tra trước
-- KHÔNG chạy phần DELETE
```

### **Bước 3: Chạy script xóa**
```sql
-- Chỉ chạy khi đã kiểm tra kỹ
-- Chạy từng bước một để kiểm soát
```

### **Bước 4: Kiểm tra kết quả**
```sql
-- Chạy phần kiểm tra sau khi xóa
-- Đảm bảo dữ liệu đã được xóa đúng
```

## 📊 Dữ liệu sẽ bị xóa

### **Theo ngày (CURDATE()):**
- Users tạo hôm nay
- Companies tạo hôm nay  
- Company_followers tạo hôm nay

### **Theo ID range:**
- Users từ ID 16 trở đi
- Companies từ ID 7 trở đi
- Company_followers liên quan

## 🛡️ Bảo mật và an toàn

### **Trước khi xóa:**
- ✅ Backup database
- ✅ Kiểm tra dữ liệu sẽ bị xóa
- ✅ Thông báo team
- ✅ Chạy trong maintenance mode

### **Trong khi xóa:**
- ✅ Chạy từng bước một
- ✅ Kiểm tra kết quả sau mỗi bước
- ✅ Có sẵn script rollback

### **Sau khi xóa:**
- ✅ Kiểm tra dữ liệu còn lại
- ✅ Test website hoạt động bình thường
- ✅ Xóa file script để bảo mật

## ❌ Xử lý lỗi và Rollback

### **Nếu có lỗi:**
```bash
# Restore từ backup
mysql -u username -p database_name < backup_before_delete_YYYYMMDD_HHMMSS.sql
```

### **Nếu cần rollback một phần:**
```sql
-- Kiểm tra dữ liệu đã bị xóa
-- Restore từng bảng nếu cần
```

## 🎯 Kết quả mong đợi

### **Sau khi xóa thành công:**
- ✅ Dữ liệu mẫu đã được xóa
- ✅ Database sạch sẽ hơn
- ✅ Website hoạt động bình thường
- ✅ Không có lỗi foreign key constraint

### **Dữ liệu còn lại:**
- Users gốc (ID 1-15)
- Companies gốc (ID 1-6)
- Dữ liệu thật của website

## 📝 Ghi chú quan trọng

- **Chỉ chạy một lần**: Không chạy lại script đã chạy
- **Kiểm tra kỹ**: Đảm bảo không xóa nhầm dữ liệu quan trọng
- **Backup bắt buộc**: Không có backup = không được chạy
- **Thời gian chạy**: Nên chạy trong giờ ít traffic

---

**⚠️ Lưu ý cuối cùng**: Script này sẽ xóa dữ liệu vĩnh viễn. Hãy cẩn thận và chỉ chạy khi thực sự cần thiết! 