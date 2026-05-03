import { test, expect } from '@playwright/test';

/**
 * Production smoke + feature tests for https://doitay.vn
 * Run: BASE_URL=https://doitay.vn npx playwright test tests/e2e/production.spec.ts --reporter=list
 */

// ─── Homepage ──────────────────────────────────────────────────────────────

test.describe('Homepage', () => {
  test.beforeEach(async ({ page }) => { await page.goto('/'); });

  test('renders hero heading', async ({ page }) => {
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
  });

  test('shows live platform stats (no dash)', async ({ page }) => {
    const stats = page.locator('text=Thợ xác thực').first();
    await expect(stats).toBeVisible();
    // stat number should NOT be "—"
    const statValue = page.locator('.font-headline.text-3xl').first();
    await expect(statValue).not.toHaveText('—');
  });

  test('shows 3-step how-it-works section', async ({ page }) => {
    // Use role=heading to avoid strict-mode violations with nav links / stat labels
    await expect(page.getByRole('heading', { name: 'Tạo yêu cầu' })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'Chọn thợ' })).toBeVisible();
    await expect(page.getByRole('heading', { name: 'Hoàn thành' })).toBeVisible();
  });

  test('shows featured contractors section', async ({ page }) => {
    await expect(page.getByText('Thợ giỏi tiêu biểu')).toBeVisible();
  });

  test('search form navigates to /cong-ty', async ({ page }) => {
    await page.getByPlaceholder('Bạn cần thợ gì hôm nay?').fill('điện');
    await page.getByRole('button', { name: /tìm ngay/i }).click();
    // Vietnamese chars are URL-encoded; just verify we land on /cong-ty with a query param
    await expect(page).toHaveURL(/\/cong-ty.*q=/);
  });

  test('no noindex meta tag', async ({ page }) => {
    const noindex = page.locator('meta[name="robots"][content*="noindex"]');
    await expect(noindex).toHaveCount(0);
  });
});

// ─── SEO / Crawlability ─────────────────────────────────────────────────────

test.describe('SEO', () => {
  test('robots.txt allows crawling', async ({ request }) => {
    const res = await request.get('/robots.txt');
    expect(res.status()).toBe(200);
    const body = await res.text();
    // Next.js generates "User-agent: *" (lowercase a)
    expect(body.toLowerCase()).toContain('user-agent: *');
    expect(body).toContain('Allow: /');
    expect(body).toContain('sitemap.xml');
    expect(body).not.toContain('Disallow: /');
  });

  test('sitemap.xml is valid and contains key URLs', async ({ request }) => {
    const res = await request.get('/sitemap.xml');
    expect(res.status()).toBe(200);
    const body = await res.text();
    expect(body).toContain('<urlset');
    expect(body).toContain('https://doitay.vn/');
    expect(body).toContain('https://doitay.vn/tho');
  });

  test('homepage has og:title meta tag', async ({ page }) => {
    await page.goto('/');
    // openGraph was added to layout.tsx generateMetadata() — requires production rebuild
    const ogTitle = page.locator('meta[property="og:title"]');
    await expect(ogTitle).toHaveCount(1);
  });
});

// ─── /tho listing ──────────────────────────────────────────────────────────

test.describe('/tho — Danh sách thợ', () => {
  test.beforeEach(async ({ page }) => { await page.goto('/tho'); });

  test('page loads with contractor cards', async ({ page }) => {
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    // Should show at least one contractor card
    await expect(page.locator('text=CHUYÊN GIA').first()).toBeVisible();
  });

  test('shows Hanoi district filter', async ({ page }) => {
    await expect(page.getByText('Khu vực — Hà Nội')).toBeVisible();
    await expect(page.getByText('Hoàn Kiếm').first()).toBeVisible();
  });

  test('shows category filter from DB', async ({ page }) => {
    // Use exact:true to avoid matching "Tất cả ngành nghề" which contains "Ngành nghề"
    await expect(page.getByText('Ngành nghề', { exact: true })).toBeVisible();
    await expect(page.getByText('Tất cả ngành nghề')).toBeVisible();
  });

  test('shows rating filter', async ({ page }) => {
    // Scope to aside to avoid matching "Đánh giá" labels in contractor cards
    const sidebar = page.locator('aside');
    await expect(sidebar.getByText('Đánh giá', { exact: true })).toBeVisible();
    await expect(sidebar.getByText('4★ trở lên')).toBeVisible();
    await expect(sidebar.getByText('3★ trở lên')).toBeVisible();
  });

  test('district filter updates URL and result count', async ({ page }) => {
    // Scope to aside to avoid matching district names in contractor cards
    await page.locator('aside').getByText('Thanh Xuân', { exact: true }).click();
    // Vietnamese chars get URL-encoded; match loosely on key segment
    await expect(page).toHaveURL(/district=.*Thanh/);
    await expect(page.getByText(/Tìm thấy/)).toBeVisible();
  });

  test('rating filter 4+ works', async ({ page }) => {
    await page.getByText('4★ trở lên').click();
    await expect(page).toHaveURL(/min_rating=4/);
    await expect(page.getByText(/Tìm thấy/)).toBeVisible();
  });

  test('sort tabs work', async ({ page }) => {
    await page.getByText('Đánh giá cao').click();
    await expect(page).toHaveURL(/sort=rating/);
  });

  test('search by keyword works', async ({ page }) => {
    await page.getByPlaceholder(/Tìm tên thợ/).fill('điện');
    await page.getByRole('button', { name: /Tìm kiếm/ }).click();
    // Vietnamese chars get URL-encoded; just verify q param exists
    await expect(page).toHaveURL(/tho.*q=/);
  });

  test('contractor card links to detail page', async ({ page }) => {
    const firstCard = page.locator('a[href^="/tho/"]').first();
    const href = await firstCard.getAttribute('href');
    expect(href).toMatch(/^\/tho\/\d+\//);
    await firstCard.click();
    await expect(page).toHaveURL(/\/tho\/\d+/);
  });

  test('placeholder images visible (no broken img)', async ({ page }) => {
    const firstImg = page.locator('.aspect-\\[16\\/10\\] img').first();
    await expect(firstImg).toBeVisible();
    const src = await firstImg.getAttribute('src');
    expect(src).toBeTruthy();
    expect(src).not.toBe('');
  });
});

// ─── Contractor detail ──────────────────────────────────────────────────────

test.describe('/tho/:id — Chi tiết thợ', () => {
  test('loads contractor detail page', async ({ page }) => {
    // Navigate via listing first to get a valid ID
    await page.goto('/tho');
    const firstCard = page.locator('a[href^="/tho/"]').first();
    await firstCard.click();
    // Wait for URL change then content
    await page.waitForURL(/\/tho\/\d+\//, { timeout: 15000 });

    // Should have company name as heading
    await expect(page.getByRole('heading', { level: 1 })).toBeVisible({ timeout: 10000 });
    // Should have appointment booking section
    await expect(page.locator('h3').filter({ hasText: /Đặt lịch/i })).toBeVisible({ timeout: 10000 });
  });

  test('canonical redirect works for vanity slug', async ({ page }) => {
    // Direct access without slug should redirect to canonical URL
    await page.goto('/tho');
    const firstCard = page.locator('a[href^="/tho/"]').first();
    const href = await firstCard.getAttribute('href');
    const idMatch = href?.match(/\/tho\/(\d+)\//);
    if (idMatch) {
      await page.goto(`/tho/${idMatch[1]}`);
      // Should redirect to canonical with slug
      await expect(page).toHaveURL(new RegExp(`/tho/${idMatch[1]}/`));
    }
  });
});

// ─── Auth pages ─────────────────────────────────────────────────────────────

test.describe('Auth — Đăng nhập', () => {
  test.beforeEach(async ({ page }) => { await page.goto('/login'); });

  test('login page renders form', async ({ page }) => {
    await expect(page.getByLabel(/Số điện thoại/i)).toBeVisible();
    await expect(page.getByLabel(/Mật khẩu/i)).toBeVisible();
    await expect(page.getByRole('button', { name: /Đăng nhập/i })).toBeVisible();
  });

  test('rejects invalid credentials', async ({ page }) => {
    await page.getByLabel(/Số điện thoại/i).fill('0900000000');
    await page.getByLabel(/Mật khẩu/i).fill('wrong-password-123');
    await page.getByRole('button', { name: /Đăng nhập/i }).click();
    await expect(page).toHaveURL(/\/login/);
    // Error message from actions.ts: "không đúng" (401), "hợp lệ" (422), "lần" (429), "Lỗi" (server)
    await expect(
      page.getByText(/không đúng|không hợp lệ|quá nhiều lần|Lỗi|lỗi/i)
    ).toBeVisible({ timeout: 10000 });
  });

  test('has register link', async ({ page }) => {
    // Two "Đăng ký" links exist (header + footer); use first
    await expect(page.getByRole('link', { name: /Đăng ký/i }).first()).toBeVisible();
  });
});

test.describe('Auth — Đăng ký', () => {
  test('register page renders form', async ({ page }) => {
    await page.goto('/dang-ky');
    await expect(page.getByLabel('Họ')).toBeVisible();
    await expect(page.getByLabel(/Số điện thoại/i)).toBeVisible();
    await expect(page.getByRole('button', { name: /Đăng ký/i })).toBeVisible();
  });
});

// ─── Static pages ───────────────────────────────────────────────────────────

test.describe('Trang tĩnh', () => {
  const pages = [
    { path: '/gioi-thieu', text: /Giới thiệu|doitay/i },
    { path: '/faq',        text: /Câu hỏi|FAQ/i },
    { path: '/lien-he',    text: /Liên hệ/i },
    { path: '/dieu-khoan', text: /Điều khoản/i },
    { path: '/bao-mat',    text: /Bảo mật|riêng tư/i },
  ];

  for (const { path, text } of pages) {
    test(`${path} loads`, async ({ page }) => {
      await page.goto(path);
      await expect(page.getByRole('heading', { level: 1 })).toBeVisible();
    });
  }
});

// ─── Navigation ─────────────────────────────────────────────────────────────

test.describe('Navigation', () => {
  test('header logo links to homepage', async ({ page }) => {
    await page.goto('/tho');
    // SiteHeader uses <nav>, not <header>; first link in nav is the logo → href="/"
    await page.locator('nav a').first().click();
    await expect(page).toHaveURL('/');
  });

  test('header has login link', async ({ page }) => {
    await page.goto('/');
    await expect(page.getByRole('link', { name: /Đăng nhập/i })).toBeVisible();
  });

  test('footer shows copyright', async ({ page }) => {
    await page.goto('/');
    await expect(page.getByText(/doitay\.vn/i).last()).toBeVisible();
  });
});

// ─── API health ──────────────────────────────────────────────────────────────

test.describe('API public endpoints', () => {
  test('GET /api/v1/public/stats returns real counts', async ({ request }) => {
    const res = await request.get('/api/v1/public/stats');
    expect(res.status()).toBe(200);
    const json = await res.json();
    expect(json.data.approved_contractors).toBeGreaterThan(0);
    expect(json.data.completed_appointments).toBeGreaterThan(0);
  });

  test('GET /api/v1/public/categories returns list', async ({ request }) => {
    const res = await request.get('/api/v1/public/categories');
    expect(res.status()).toBe(200);
    const json = await res.json();
    expect(json.data.length).toBeGreaterThan(0);
  });

  test('GET /api/v1/public/companies returns paginated list', async ({ request }) => {
    const res = await request.get('/api/v1/public/companies?per_page=5');
    expect(res.status()).toBe(200);
    const json = await res.json();
    expect(json.data.length).toBeGreaterThan(0);
    expect(json.meta.total).toBeGreaterThan(0);
  });
});
