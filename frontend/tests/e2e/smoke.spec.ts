import { expect, test } from '@playwright/test';

/**
 * Smoke test for Phase 1 slice.
 *
 * Requires:
 *   - Laravel backend at http://localhost:8000 with /api/v1/* configured
 *   - At least one approved Company in DB (slug doesn't matter — first item is used)
 *   - A registered active user — credentials read from env or fall back to defaults
 *
 * Run:
 *   npm run test:e2e
 */
test.describe('doitay rebuild — Phase 1 smoke', () => {
  test('homepage renders', async ({ page }) => {
    await page.goto('/');
    await expect(page.getByRole('heading', { name: 'doitay.vn' })).toBeVisible();
    await expect(page.getByRole('link', { name: 'Xem danh sách công ty' })).toBeVisible();
  });

  test('public company list loads from API', async ({ page }) => {
    await page.goto('/cong-ty');
    await expect(page.getByRole('heading', { name: 'Danh sách công ty' })).toBeVisible();
    // Either we see at least one company OR an empty-state. Both are valid against
    // the API contract; the test fails only on a hard error banner.
    const errorBanner = page.locator('text=Lỗi:');
    await expect(errorBanner).toHaveCount(0);
  });

  test('login form rejects invalid credentials', async ({ page }) => {
    await page.goto('/login');
    await page.getByLabel('Email').fill('does-not-exist@nowhere.local');
    await page.getByLabel('Mật khẩu').fill('wrong-password-123');
    await page.getByRole('button', { name: 'Đăng nhập' }).click();
    await expect(page).toHaveURL(/\/login\?error=/);
  });
});
