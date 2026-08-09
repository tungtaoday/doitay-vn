<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * BÁO CÁO VIỆC ĐÃ LÀM.
 *
 * Vòng lặp đang hở một nửa: mỗi sáng bot đẩy việc (bảng `nhat_lenh`), nhưng làm
 * xong thì không có chỗ nào ghi lại. Cuối tuần review không có gì đối chiếu,
 * và không trả lời được câu đơn giản nhất: tuần này làm được bao nhiêu việc.
 *
 * Ghi bằng cách nhắn cho bot Telegram: "xong 1 3" (theo số thứ tự việc trong
 * nhật lệnh) hoặc "xong gọi được 5 thợ" (việc tự phát).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bao_cao_viec', function (Blueprint $table) {
            $table->id();
            $table->date('ngay')->index();
            $table->string('noi_dung', 500);
            // Số thứ tự việc trong nhật lệnh hôm đó; null = việc tự phát ngoài kế hoạch.
            $table->unsignedTinyInteger('so_viec')->nullable();
            $table->string('nguon', 20)->default('telegram');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bao_cao_viec');
    }
};
