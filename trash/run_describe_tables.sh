#!/bin/bash

# Script để chạy describe tables trên local và production
# Hướng dẫn sử dụng:
# chmod +x run_describe_tables.sh
# ./run_describe_tables.sh

echo "========================================="
echo "KIỂM TRA CẤU TRÚC BẢNG LOCAL VÀ PRODUCTION"
echo "========================================="

# Chạy describe trên LOCAL database
echo ""
echo "1. CHẠY DESCRIBE TRÊN LOCAL DATABASE (t_review_db)..."
echo "-----------------------------------------------------"
mysql -u root -pVuivui@123 t_review_db < describe_all_tables.sql > local_tables_output.txt 2>&1

if [ $? -eq 0 ]; then
    echo "✅ LOCAL: Thành công - Kết quả lưu trong local_tables_output.txt"
else
    echo "❌ LOCAL: Lỗi khi chạy script"
    cat local_tables_output.txt
fi

# Chạy describe trên PRODUCTION database
echo ""
echo "2. CHẠY DESCRIBE TRÊN PRODUCTION DATABASE (t_review_production)..."
echo "-------------------------------------------------------------------"
mysql -u treview_user -pStrongPassword123! t_review_production < describe_production_tables.sql > production_tables_output.txt 2>&1

if [ $? -eq 0 ]; then
    echo "✅ PRODUCTION: Thành công - Kết quả lưu trong production_tables_output.txt"
else
    echo "❌ PRODUCTION: Lỗi khi chạy script"
    cat production_tables_output.txt
fi

# So sánh và tạo SQL để fix
echo ""
echo "3. TẠO CÁC BẢNG BỊ THIẾU TRÊN PRODUCTION..."
echo "--------------------------------------------"
mysql -u treview_user -pStrongPassword123! t_review_production < compare_table_structures.sql > fix_tables_output.txt 2>&1

if [ $? -eq 0 ]; then
    echo "✅ FIX: Thành công - Các bảng thiếu đã được tạo"
    cat fix_tables_output.txt
else
    echo "❌ FIX: Lỗi khi tạo bảng"
    cat fix_tables_output.txt
fi

echo ""
echo "========================================="
echo "HOÀN THÀNH - KIỂM TRA CÁC FILE OUTPUT:"
echo "- local_tables_output.txt"
echo "- production_tables_output.txt" 
echo "- fix_tables_output.txt"
echo "=========================================" 