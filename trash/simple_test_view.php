<?php
echo "=== SIMPLE VIEW TEST ===";
echo PHP_EOL;

// Simulate Lead data structure
$lead = new stdClass();
$lead->id = 30;
$lead->title = "sửa vui vẻ";
$lead->description = "asas asdasd asdadas asdasdas ádas";
$lead->ward = "Xã Đông La";
$lead->district = "Huyện Hoài Đức";
$lead->budget_min = 200000;
$lead->budget_max = 500000;
$lead->lead_price = 50000;
$lead->status = "active";

// Simulate array fields from database
$lead->address = json_decode('{"detail": "No 9, Đào Duy Anh áasasasas"}', true);
$lead->customer_info = json_decode('{"min_rating":null,"selected_contractors":[]}', true);
$lead->requirements = json_decode('[]', true);

echo "Testing problematic fields:" . PHP_EOL;

// Test address handling
echo "1. Address handling:" . PHP_EOL;
if ($lead->address && is_array($lead->address) && isset($lead->address['detail'])) {
    echo "   ✅ Array access: " . $lead->address['detail'] . PHP_EOL;
} elseif ($lead->address && is_string($lead->address)) {
    echo "   ✅ String access: " . $lead->address . PHP_EOL;
} else {
    echo "   ⚠️ No address data" . PHP_EOL;
}

// Test location
echo "2. Location string:" . PHP_EOL;
echo "   ✅ Result: " . $lead->ward . ", " . $lead->district . PHP_EOL;

// Test budget
echo "3. Budget formatting:" . PHP_EOL;
echo "   ✅ Result: " . number_format($lead->budget_min) . "₫ - " . number_format($lead->budget_max) . "₫" . PHP_EOL;

// Test price
echo "4. Lead price:" . PHP_EOL;
echo "   ✅ Result: " . number_format($lead->lead_price ?? 50000) . "₫" . PHP_EOL;

echo PHP_EOL;
echo "🎯 If this works, the view should work too!" . PHP_EOL; 