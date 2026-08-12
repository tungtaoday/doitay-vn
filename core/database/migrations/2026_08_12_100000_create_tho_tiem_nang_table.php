<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * THỢ TIỀM NĂNG — lead gom từ seeding group.
 *
 * Nhiều bài thợ đăng trong group kèm sẵn SĐT ("alo em 09xx..."). Bot seeding
 * đọc bài để soạn câu bình luận thì tiện tay bắt luôn tên + số, lưu vào đây;
 * comment công khai xin phép trước ("em xin phép nhắn Zalo...") rồi người vận
 * hành nhắn Zalo — cuộc chào ấm, không phải làm phiền người lạ.
 *
 * Vòng đời `trang_thai`: moi → da_nhan (đã nhắn Zalo) → da_tao_ho_so | bo_qua.
 * `da_co_ho_so` = số này đã có hồ sơ trên chợ từ trước (đừng chào lại).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tho_tiem_nang', function (Blueprint $table) {
            $table->id();
            $table->string('ten', 120)->nullable();
            $table->string('sdt', 20)->index();          // đã chuẩn hoá 0xxxxxxxxx
            $table->string('link_bai', 500)->nullable(); // bài gốc trong group
            $table->string('trich', 500)->nullable();    // trích nội dung bài
            $table->string('loai', 30)->nullable();      // tho_tim_viec | tho_khoe_viec...
            $table->string('trang_thai', 20)->default('moi')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tho_tiem_nang');
    }
};
