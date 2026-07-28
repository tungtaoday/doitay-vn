import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Chính sách bảo mật — Ground Truth · Hồ Sơ Thợ',
  description:
    'Chính sách bảo mật của ứng dụng Zalo Mini App “Ground Truth - Hồ Sơ Thợ” — cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu của thợ.',
  alternates: { canonical: '/bao-mat' },
};

export default function GroundTruthPrivacyPage() {
  return (
    <div className="bg-surface">
      <div className="mx-auto max-w-3xl px-6 py-20 lg:px-8">
        <header className="mb-16 text-center">
          <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
            <span className="material-symbols-outlined text-sm">lock</span>
            Ground Truth · Hồ Sơ Thợ
          </span>
          <h1 className="mt-6 font-headline text-4xl font-bold text-on-surface">Chính sách bảo mật</h1>
          <p className="mt-4 text-on-surface-variant">
            Áp dụng cho ứng dụng Zalo Mini App “Ground Truth - Hồ Sơ Thợ”. Cập nhật lần cuối: 28/07/2026
          </p>
        </header>

        <div className="prose-custom space-y-10 text-on-surface-variant">
          <Section title="1. Chúng tôi là ai">
            <p>
              Chính sách này mô tả cách <strong className="text-on-surface">Hộ kinh doanh Ground Truth</strong> (đơn vị vận hành ứng dụng “Ground Truth - Hồ Sơ Thợ” và nền tảng doitay.vn) thu thập, sử dụng và bảo vệ thông tin của bạn.
            </p>
          </Section>

          <Section title="2. Thông tin chúng tôi thu thập">
            <p><strong className="text-on-surface">Từ tài khoản Zalo</strong> (chỉ khi bạn đồng ý cấp quyền):</p>
            <ul>
              <li>Tên hiển thị và ảnh đại diện Zalo — dùng để điền sẵn vào hồ sơ, giúp bạn đỡ phải nhập tay.</li>
            </ul>
            <p className="mt-3"><strong className="text-on-surface">Thông tin bạn tự nhập</strong> để tạo hồ sơ nghề:</p>
            <ul>
              <li>Họ tên, số điện thoại;</li>
              <li>Nghề, khu vực nhận việc, số năm kinh nghiệm;</li>
              <li>Giới thiệu bản thân, kỹ năng, bảng giá dịch vụ;</li>
              <li>Ảnh công việc bạn đã làm.</li>
            </ul>
          </Section>

          <Section title="3. Mục đích sử dụng">
            <p>Thông tin của bạn chỉ được dùng để:</p>
            <ul>
              <li>Tạo thẻ hồ sơ nghề và mã QR để bạn gửi cho khách qua Zalo;</li>
              <li>Hiển thị hồ sơ cho khách hàng khi bạn chủ động chia sẻ;</li>
              <li>Tuỳ chọn: đưa hồ sơ lên chợ thợ doitay.vn để khách trên mạng tìm thấy bạn.</li>
            </ul>
          </Section>

          <Section title="4. Lưu trữ & chia sẻ dữ liệu">
            <p>
              Mặc định, hồ sơ của bạn được lưu ngay trên thiết bị của bạn. Ứng dụng <strong className="text-on-surface">chỉ</strong> gửi thông tin lên máy chủ doitay.vn <strong className="text-on-surface">khi bạn chủ động</strong> bật tuỳ chọn “đồng bộ với doitay.vn” hoặc bấm chia sẻ hồ sơ — khi đó hồ sơ được tạo ở trạng thái chờ duyệt để hiển thị cho khách.
            </p>
            <p className="mt-3">
              Chúng tôi <strong className="text-on-surface">không bán</strong>, không cho thuê và không trao đổi thông tin cá nhân của bạn cho bên thứ ba vì mục đích quảng cáo. Thông tin bạn công bố trên hồ sơ (tên, nghề, khu vực, số điện thoại, ảnh việc) sẽ hiển thị công khai cho khách xem — đây là mục đích chính của việc tạo hồ sơ. Dữ liệu truyền đi được bảo vệ qua kết nối mã hoá HTTPS.
            </p>
          </Section>

          <Section title="5. Quyền của bạn">
            <p>Bạn có quyền:</p>
            <ul>
              <li>Xem, chỉnh sửa hoặc xoá thông tin trong hồ sơ bất cứ lúc nào;</li>
              <li>Đăng xuất để xoá dữ liệu lưu trên thiết bị;</li>
              <li>Yêu cầu gỡ hồ sơ đã công khai trên doitay.vn bằng cách liên hệ với chúng tôi.</li>
            </ul>
            <p className="mt-3">
              Bạn có thể từ chối cấp quyền tên/ảnh Zalo; khi đó bạn vẫn dùng ứng dụng bình thường bằng cách tự nhập thông tin.
            </p>
          </Section>

          <Section title="6. Bảo mật & thay đổi chính sách">
            <p>
              Chúng tôi áp dụng các biện pháp hợp lý để bảo vệ thông tin của bạn và chỉ thu thập dữ liệu cần thiết cho mục đích tạo hồ sơ nghề. Chúng tôi có thể cập nhật Chính sách bảo mật; bản mới nhất luôn hiển thị trong ứng dụng và tại trang này.
            </p>
          </Section>

          <Section title="7. Liên hệ">
            <p>
              Hộ kinh doanh Ground Truth — vận hành nền tảng doitay.vn.{' '}
              Mọi thắc mắc về bảo mật, vui lòng liên hệ{' '}
              <a href="mailto:admin@doitay.vn" className="font-semibold text-primary hover:underline">admin@doitay.vn</a>
              {' '}hoặc Hotline / Zalo{' '}
              <a href="tel:0972585990" className="font-semibold text-primary hover:underline">0972 585 990</a>.
            </p>
          </Section>
        </div>

        <div className="mt-16 text-center text-sm text-outline">
          Đây là chính sách cho ứng dụng <strong className="text-on-surface">Ground Truth - Hồ Sơ Thợ</strong>. Chính sách bảo mật của nền tảng doitay.vn xem tại{' '}
          <Link href="/doitay/bao-mat" className="text-primary underline">doitay.vn/doitay/bao-mat</Link>.
        </div>
      </div>
    </div>
  );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
  return (
    <section>
      <h2 className="mb-4 font-headline text-xl font-bold text-on-surface">{title}</h2>
      <div className="space-y-3 text-sm leading-relaxed">{children}</div>
    </section>
  );
}
