import Link from 'next/link';

export default function HomePage() {
  return (
    <div className="space-y-6">
      <h1 className="text-4xl font-bold">doitay.vn</h1>
      <p className="text-lg text-gray-600">
        Tìm nhà thầu, dịch vụ uy tín tại Việt Nam.
      </p>
      <div className="flex gap-3">
        <Link
          href="/cong-ty"
          className="rounded-lg bg-blue-600 px-5 py-2.5 text-white hover:bg-blue-700"
        >
          Xem danh sách công ty
        </Link>
        <Link
          href="/login"
          className="rounded-lg border px-5 py-2.5 hover:bg-gray-50"
        >
          Đăng nhập
        </Link>
      </div>
    </div>
  );
}
