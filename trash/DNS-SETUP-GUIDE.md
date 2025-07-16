# 🌐 HƯỚNG DẪN CẤU HÌNH DNS CHO DOITAY.VN

## 📍 THÔNG TIN SERVER
- **Domain**: doitay.vn
- **Server IP**: 165.22.252.188
- **Registrar**: PA Vietnam

## 🔧 CÁC BƯỚC CẤU HÌNH DNS

### BƯỚC 1: Truy cập PA Vietnam Control Panel
1. Đăng nhập vào tài khoản PA Vietnam
2. Vào mục quản lý domain doitay.vn
3. Tìm phần "DNS Management" hoặc "Name Servers"

### BƯỚC 2: Cấu hình DNS Records
Thêm các DNS records sau:

```
Type    Name    Value           TTL
A       @       165.22.252.188  3600
A       www     165.22.252.188  3600
CNAME   *       doitay.vn       3600
```

**Giải thích:**
- `A @` - Trỏ doitay.vn về server
- `A www` - Trỏ www.doitay.vn về server  
- `CNAME *` - Trỏ tất cả subdomain về doitay.vn

### BƯỚC 3: Kiểm tra DNS Propagation
Sau khi cập nhật DNS, chờ 15-30 phút rồi kiểm tra:

```bash
# Kiểm tra DNS đã trỏ đúng chưa
nslookup doitay.vn
# Kết quả phải là: 165.22.252.188

nslookup www.doitay.vn
# Kết quả phải là: 165.22.252.188

# Hoặc dùng online tool
# https://dnschecker.org/
```

### BƯỚC 4: Test Domain
```bash
# Test trên server
curl -I http://doitay.vn
curl -I http://www.doitay.vn
```

## ⚡ LƯU Ý QUAN TRỌNG
- DNS có thể mất 24-48h để propagate hoàn toàn
- Kiểm tra tại nhiều location khác nhau
- Đảm bảo cả www và non-www đều hoạt động

## 🔄 BƯỚC TIẾP THEO
Sau khi DNS hoạt động → SSH vào server và chạy script setup:

```bash
# SSH vào server
ssh root@165.22.252.188

# Chạy script setup
bash /root/production-setup.sh 