import pandas as pd
import os
from config import CSV_FILE_PATH

def preview_csv():
    """Xem trước nội dung file CSV"""
    
    # Kiểm tra file tồn tại
    if not os.path.exists(CSV_FILE_PATH):
        print(f"❌ File không tồn tại: {CSV_FILE_PATH}")
        return
    
    try:
        # Thử đọc với encoding khác nhau
        df = None
        encodings = ['utf-8', 'utf-8-sig', 'cp1252', 'latin1']
        
        for encoding in encodings:
            try:
                df = pd.read_csv(CSV_FILE_PATH, encoding=encoding)
                print(f"✅ Đọc file thành công với encoding: {encoding}")
                break
            except:
                continue
        
        if df is None:
            print("❌ Không thể đọc file CSV với các encoding thông thường")
            return
        
        print(f"\n📊 THÔNG TIN FILE CSV:")
        print(f"   - Số dòng: {len(df)}")
        print(f"   - Số cột: {len(df.columns)}")
        print(f"   - Kích thước: {os.path.getsize(CSV_FILE_PATH)} bytes")
        
        print(f"\n📋 TÊN CỘT:")
        for i, col in enumerate(df.columns):
            print(f"   {i}: {col}")
        
        print(f"\n📄 MẪU DỮ LIỆU (10 dòng đầu):")
        print(df.head(10).to_string(index=False))
        
        print(f"\n📈 THỐNG KÊ DỮ LIỆU:")
        print(df.info())
        
        # Kiểm tra dữ liệu trống
        null_counts = df.isnull().sum()
        if null_counts.sum() > 0:
            print(f"\n⚠️  CÁC CỘT CÓ DỮ LIỆU TRỐNG:")
            for col, count in null_counts.items():
                if count > 0:
                    print(f"   - {col}: {count} dòng trống")
        else:
            print(f"\n✅ Không có dữ liệu trống")
            
        # Đếm số dòng duy nhất
        print(f"\n🔢 THỐNG KÊ UNIQUE:")
        for col in df.columns:
            unique_count = df[col].nunique()
            print(f"   - {col}: {unique_count} giá trị duy nhất")
            
    except Exception as e:
        print(f"❌ Lỗi đọc file CSV: {e}")

if __name__ == "__main__":
    preview_csv() 