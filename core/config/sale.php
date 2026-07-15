<?php

/**
 * Sale CTV onboarding config. Ref: BREQ-SALE-CTV-ONBOARDING, DUC-COMMISSION-RECORD.
 */
return [
    // Hoa hồng cơ bản cho mỗi hồ sơ hợp lệ (VND).
    'commission_base' => (int) env('SALE_COMMISSION_BASE', 30000),

    // Thưởng khi thợ chia sẻ hồ sơ cho khách (VND).
    'commission_share_bonus' => (int) env('SALE_COMMISSION_SHARE_BONUS', 10000),

    // Category mặc định khi không match được nghề của submission.
    'default_category_id' => (int) env('SALE_DEFAULT_CATEGORY_ID', 1),
];
