<?php

/**
 * Sale CTV onboarding config. Ref: BREQ-SALE-CTV-ONBOARDING, DUC-COMMISSION-RECORD.
 */
return [
    // Hoa hồng cơ bản cho mỗi hồ sơ hợp lệ (VND).
    'commission_base' => (int) env('SALE_COMMISSION_BASE', 30000),

    // Thưởng khi thợ chia sẻ hồ sơ cho khách (VND).
    'commission_share_bonus' => (int) env('SALE_COMMISSION_SHARE_BONUS', 10000),

    // Thưởng KÍCH HOẠT: thợ do CTV tuyển xác nhận lịch hẹn ĐẦU TIÊN (VND).
    // Đặt 0 để TẮT theo giai đoạn. Ref: sale-service-model.md §3.2.
    'commission_activation' => (int) env('SALE_COMMISSION_ACTIVATION', 20000),

    // Category mặc định khi không match được nghề của submission.
    'default_category_id' => (int) env('SALE_DEFAULT_CATEGORY_ID', 1),

    // Danh sách user_id được quyền DUYỆT hồ sơ (Quản lý). Rỗng = khoá hết (an toàn mặc định).
    // Cấu hình: SALE_MANAGER_USER_IDS=12,34 trong .env
    'manager_user_ids' => array_values(array_filter(array_map(
        'intval',
        explode(',', (string) env('SALE_MANAGER_USER_IDS', ''))
    ))),
];
