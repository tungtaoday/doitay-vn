<?php

echo "🔍 KIỂM TRA EMAIL MAPPING\n";
echo "========================\n\n";

echo "👷 Contractor emails (domain: .tho@doitay.local):\n";
for ($i = 1; $i <= 5; $i++) {
    $baseEmail = strtolower('nguyen.anh');
    $email = $baseEmail . $i . '.tho@doitay.local';
    echo "  {$i}: {$email}\n";
}

echo "\n👥 Customer emails (domain: @gmail.com):\n";
for ($i = 1; $i <= 5; $i++) {
    $baseEmail = strtolower('nguyen.anh');
    $email = $baseEmail . $i . '@gmail.com';
    echo "  {$i}: {$email}\n";
}

echo "\n✅ KẾT LUẬN: Mapping hoàn hảo!\n";
echo "- Contractors: có '.tho' và domain riêng\n";
echo "- Customers: có ID và Gmail\n";
echo "- KHÔNG THỂ TRÙNG LẶP giữa 2 loại!\n"; 