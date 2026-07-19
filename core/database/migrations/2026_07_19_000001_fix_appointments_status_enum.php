<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Sửa bug enum status lịch hẹn: enum cũ có 'cancelled' (2L) nhưng TOÀN BỘ code
 * (legacy + API mới) ghi 'canceled' (1L) → MySQL non-strict chèn '' → mọi lịch
 * bị hủy từ 2025 tới nay đều mất trạng thái (45 dòng rỗng trên prod).
 * Chuẩn hóa: enum dùng 'canceled'; dữ liệu '' được quy về 'canceled'.
 */
return new class extends Migration
{
    public function up(): void
    {
        // B1: thêm 'canceled' (giữ tạm 'cancelled' để không mất dữ liệu nếu có)
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending','confirmed','completed','cancelled','canceled') NOT NULL DEFAULT 'pending'");

        // B2: dữ liệu hỏng ('' do enum mismatch) + 'cancelled' cũ (nếu có) → 'canceled'
        DB::table('appointments')->where('status', '')->update(['status' => 'canceled']);
        DB::table('appointments')->where('status', 'cancelled')->update(['status' => 'canceled']);

        // B3: chốt enum chuẩn
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending','confirmed','completed','canceled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending','confirmed','completed','cancelled','canceled') NOT NULL DEFAULT 'pending'");
        DB::table('appointments')->where('status', 'canceled')->update(['status' => 'cancelled']);
        DB::statement("ALTER TABLE appointments MODIFY status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};
