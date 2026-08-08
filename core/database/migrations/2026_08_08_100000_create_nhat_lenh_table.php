<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * NHẬT LỆNH — việc trong ngày theo blueprint 30 ngày.
 *
 * Trước đây nội dung này chỉ tồn tại dưới dạng 3 tin nhắn Telegram dài mỗi
 * sáng: đọc xong là trôi, không tra lại được, và đọc trên điện thoại thì đoạn
 * trích sách rất khó theo. Nay bot `agent-system/quan-tri/telegram_push.py`
 * đẩy bản có cấu trúc vào đây, trung tâm quản trị hiển thị, Telegram chỉ còn
 * một tin ngắn dẫn link.
 *
 * Mỗi ngày một bản ghi (`ngay` unique) — chạy lại bot trong ngày thì ghi đè,
 * không sinh bản trùng.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhat_lenh', function (Blueprint $table) {
            $table->id();
            $table->date('ngay')->unique();
            $table->unsignedSmallInteger('so_ngay')->nullable();  // ngày thứ mấy trong blueprint
            $table->string('tieu_de', 200)->nullable();
            $table->json('noi_dung');                             // việc · chi tiết · số liệu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhat_lenh');
    }
};
