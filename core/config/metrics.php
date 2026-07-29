<?php

return [
    /*
    | Token bảo vệ endpoint thống kê Bắc Đẩu (GET /api/v1/public/metrics/bac-dau).
    | Đặt METRICS_TOKEN trong .env rồi chạy `php artisan config:cache`.
    | Bỏ trống = endpoint trả 403 (an toàn mặc định).
    */
    'token' => env('METRICS_TOKEN', ''),
];
