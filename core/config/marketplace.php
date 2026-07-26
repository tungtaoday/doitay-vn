<?php

/*
|--------------------------------------------------------------------------
| Marketplace — tham số vận hành (đổi qua .env, KHÔNG hardcode trong code)
|--------------------------------------------------------------------------
*/

return [
    // SEO: so tho toi thieu de mot trang nghe x khu vuc duoc index (chong thin content).
    'seo_min_tho' => (int) env('SEO_MIN_THO', 3),
    // Hồ sơ thợ tạo từ Zalo Mini App: false = vào hàng đợi duyệt (chống spam),
    // true = lên chợ ngay. Đổi bằng env MINIAPP_AUTOPUBLISH.
    'miniapp_autopublish' => filter_var(env('MINIAPP_AUTOPUBLISH', false), FILTER_VALIDATE_BOOL),


    // Phí mở khóa thông tin khách khi thợ xác nhận lịch hẹn (VND).
    'lead_fee' => (int) env('LEAD_FEE', 10000),

    // Tín dụng chào mừng tặng vào ví khi hồ sơ thợ được duyệt (VND).
    'welcome_credit' => (int) env('WELCOME_CREDIT', 200000),

    // Hiện SĐT/email thợ công khai trên trang hồ sơ?
    // false = che số, buộc khách đi qua luồng đặt lịch (bảo vệ phí lead).
    'show_contact' => filter_var(env('SHOW_CONTACT_PUBLIC', false), FILTER_VALIDATE_BOOL),
];
