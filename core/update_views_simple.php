<?php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Cập nhật view count...\n";

try {
    // Cập nhật view count ngẫu nhiên cho tất cả công ty
    $companies = DB::table('companies')->pluck('id');
    
    foreach ($companies as $companyId) {
        $views = rand(10, 200);
        
        // Cập nhật company_statistics
        DB::table('company_statistics')
            ->updateOrInsert(
                ['company_id' => $companyId],
                ['views' => $views, 'hires' => 0, 'updated_at' => now()]
            );
        
        // Cập nhật companies
        DB::table('companies')
            ->where('id', $companyId)
            ->update(['total_click' => $views]);
    }
    
    echo "✅ Đã cập nhật " . count($companies) . " công ty\n";
    
    // Hiển thị mẫu
    $sample = DB::table('companies')
        ->select('id', 'name', 'total_click')
        ->limit(5)
        ->get();
    
    foreach ($sample as $company) {
        echo "- {$company->name}: {$company->total_click} lượt xem\n";
    }
    
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage() . "\n";
}

