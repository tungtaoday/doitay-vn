<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Portfolio;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * Ảnh công việc của thợ (Mini App) → lưu trên SERVER, không còn base64 trong máy.
 *
 * Ghi vào thư mục ảnh dùng chung của web (`assets/images/portfolio` ở gốc repo —
 * đây là docroot của vhost doitay.vn), đúng nơi `CompanyDetailResource` đọc ra
 * để khách xem hồ sơ nhìn thấy ảnh.
 */
class ThoPortfolioService
{
    public const MAX_PER_COMPANY = 8;

    /**
     * @param  UploadedFile[]  $files
     * @return array{saved: int, images: array<int, array{id:int,url:string,title:?string}>}
     */
    public function addImages(Company $company, array $files, array $titles = []): array
    {
        $dir = $this->storageDir();
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $already = Portfolio::where('company_id', $company->id)->count();
        $room = max(0, self::MAX_PER_COMPANY - $already);
        $saved = [];

        foreach (array_values($files) as $i => $file) {
            if (count($saved) >= $room) {
                break;
            }
            if (! $file instanceof UploadedFile || ! $file->isValid()) {
                continue;
            }

            $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                continue;
            }

            $name = time() . '_tho' . $company->id . '_' . Str::lower(Str::random(10)) . '.' . $ext;
            $file->move($dir, $name);

            $p = Portfolio::create([
                'company_id'  => $company->id,
                'title'       => trim((string) ($titles[$i] ?? '')) ?: 'Công việc đã làm',
                'description' => '',
                'image'       => $name,
            ]);

            $saved[] = ['id' => $p->id, 'url' => $this->url($name), 'title' => $p->title];
        }

        return ['saved' => count($saved), 'images' => $saved];
    }

    /** Xoá 1 ảnh của hồ sơ (thợ tự gỡ ảnh trong app). */
    public function removeImage(Company $company, int $portfolioId): bool
    {
        $p = Portfolio::where('company_id', $company->id)->where('id', $portfolioId)->first();
        if (! $p) {
            return false;
        }

        $path = $this->storageDir() . DIRECTORY_SEPARATOR . $p->image;
        if ($p->image && is_file($path)) {
            @unlink($path);
        }
        $p->delete();

        return true;
    }

    /** Danh sách ảnh để Mini App dựng lại hồ sơ. */
    public function listImages(Company $company): array
    {
        return Portfolio::where('company_id', $company->id)
            ->orderBy('id')
            ->get()
            ->map(fn ($p) => [
                'id'    => $p->id,
                'title' => $p->title,
                'url'   => $p->image ? $this->url($p->image) : null,
            ])
            ->filter(fn ($x) => $x['url'] !== null)
            ->values()
            ->all();
    }

    /**
     * Thư mục ảnh thật. Prod & local đều là `<gốc repo>/assets/images/portfolio`
     * (vhost doitay.vn phục vụ từ gốc repo, không phải core/public).
     * Ghi đè bằng config `marketplace.portfolio_dir` nếu hạ tầng đổi.
     */
    private function storageDir(): string
    {
        $configured = config('marketplace.portfolio_dir');
        if ($configured) {
            return rtrim($configured, '/\\');
        }

        return dirname(base_path()) . DIRECTORY_SEPARATOR . 'assets'
            . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'portfolio';
    }

    private function url(string $filename): string
    {
        $base = rtrim((string) config('app.frontend_url', config('app.url')), '/');

        return $base . '/assets/images/portfolio/' . $filename;
    }
}
