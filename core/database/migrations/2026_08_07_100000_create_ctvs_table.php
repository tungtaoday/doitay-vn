<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DANH SÁCH CTV.
 *
 * Trước migration này "CTV" không tồn tại như một thực thể: bất kỳ tài khoản nào
 * đăng nhập cũng nộp được hồ sơ, và trung tâm điều hành chỉ thấy CTV sau khi họ
 * đã nhập ít nhất một hồ sơ (join tho_submissions). Hệ quả: tuyển CTV xong không
 * theo dõi được ai chưa làm gì, và không có cách nào ngưng quyền của một người.
 *
 * Bảng này là danh sách chính thức. Backfill ngay từ tho_submissions.ctv_id để
 * không ai đang làm bị mất quyền khi cổng nhập hồ sơ bắt đầu kiểm tra danh sách.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctvs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->unsignedTinyInteger('trang_thai')->default(1); // 1 hoạt động · 0 ngưng
            $table->string('khu_vuc', 120)->nullable();
            $table->string('ghi_chu', 500)->nullable();
            $table->unsignedBigInteger('nguoi_them')->nullable();  // user_id quản lý đã thêm
            $table->timestamps();
        });

        // Backfill: mọi người đã từng nộp hồ sơ đều là CTV đang hoạt động.
        $daNop = DB::table('tho_submissions')
            ->whereNotNull('ctv_id')
            ->distinct()
            ->pluck('ctv_id');

        $coThat = DB::table('users')->whereIn('id', $daNop)->pluck('id');

        $now = now();
        $rows = $coThat->map(fn ($id) => [
            'user_id'    => $id,
            'trang_thai' => 1,
            'ghi_chu'    => 'Tự thêm khi lập danh sách CTV (đã có hồ sơ trước đó)',
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        if (! empty($rows)) {
            DB::table('ctvs')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ctvs');
    }
};
