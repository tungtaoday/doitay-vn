# Chạy PHPUnit cho Sale onboarding (và cách vòng qua schema legacy)

App có bảng legacy ngoài migration (`languages`, `categories`, `users` cột cũ...) → `RefreshDatabase` không dựng lại được, HTTP test fail (CLAUDE.md §6). Cách đã dùng để chạy được **8/8 test xanh**:

## 1. Tạo DB test riêng (an toàn — không đụng DB thật)
`core/phpunit.xml` đã trỏ `DB_DATABASE=doitay_test`.
```php
// tinker: tạo DB
DB::statement('CREATE DATABASE IF NOT EXISTS doitay_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
```

## 2. Sinh schema snapshot (nếu mysqldump lỗi thì qua PHP)
Trên máy `mysqldump` chạy được: `php artisan schema:dump`.
Nếu lỗi (XAMPP thiếu `caching_sha2_password.dll`): loop `SHOW CREATE TABLE` + dump rows `migrations` → `core/database/schema/mysql-schema.sql`.

## 3. Nạp schema vào doitay_test qua PDO (vòng qua mysql binary lỗi)
```php
// tinker:
config(['database.connections.mysql.database' => 'doitay_test']);
DB::purge('mysql'); DB::reconnect('mysql');
DB::unprepared(file_get_contents(database_path('schema/mysql-schema.sql')));
Artisan::call('migrate', ['--force' => true]);   // chạy tho_submissions + commissions lên trên
```

## 4. Chạy test
```bash
php artisan config:clear   # để phpunit override DB_DATABASE có hiệu lực (tránh cache trỏ DB thật)
php artisan test --filter=Sale
```

## Ghi chú
- Sale tests (`tests/Feature/Api/V1/Sale/*`) dùng `DatabaseTransactions` (chạy được trên máy binary lỗi). Máy chuẩn có thể đổi về `RefreshDatabase` (tự nạp `database/schema/mysql-schema.sql`).
- `User` model đã thêm `HasFactory` (trước thiếu → mọi test dùng `User::factory()` fail).
- `mysql-schema.sql` bị `core/.gitignore` (`*.sql`) — mỗi máy tự sinh lại theo bước 2.
- Test auth: app legacy trả HTTP 200 + `{"remark":"unauthenticated"}` (không phải 401) — guest vẫn bị chặn. TODO Phase 3: JSON auth guard trả 401/403.
