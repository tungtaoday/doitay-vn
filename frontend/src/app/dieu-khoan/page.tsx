import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Điều khoản sử dụng — Ground Truth · Hồ Sơ Thợ',
  description:
    'Điều khoản sử dụng ứng dụng Zalo Mini App “Ground Truth - Hồ Sơ Thợ” do Hộ kinh doanh Ground Truth phát hành.',
  alternates: { canonical: '/dieu-khoan' },
};

export default function GroundTruthTermsPage() {
  return (
    <div className="mx-auto max-w-3xl px-6 pb-32 pt-16 lg:px-8">
      {/* Breadcrumb */}
      <nav className="mb-8 flex items-center gap-2 text-sm text-outline">
        <Link href="/" className="transition-colors hover:text-primary">Trang chủ</Link>
        <span className="material-symbols-outlined text-xs">chevron_right</span>
        <span className="font-medium text-on-surface">Điều khoản sử dụng (Hồ Sơ Thợ)</span>
      </nav>

      <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
        <span className="material-symbols-outlined text-sm">verified_user</span>
        Ground Truth · Hồ Sơ Thợ
      </span>
      <h1 className="mt-6 font-headline text-4xl font-bold tracking-tight text-on-surface">
        Điều khoản sử dụng
      </h1>
      <p className="mt-3 text-sm text-outline">
        Áp dụng cho ứng dụng Zalo Mini App “Ground Truth - Hồ Sơ Thợ”. Cập nhật lần cuối: 28/07/2026
      </p>

      <div className="mt-12 space-y-16 leading-relaxed text-on-surface-variant">
        <Section id="gioi-thieu" title="1. Giới thiệu & chấp nhận điều khoản">
          <p>
            Ứng dụng <strong className="text-on-surface">“Ground Truth - Hồ Sơ Thợ”</strong> (dưới đây gọi là “Ứng dụng”) là một tiện ích phần mềm do <strong className="text-on-surface">Hộ kinh doanh Ground Truth</strong> phát hành và vận hành cùng nền tảng doitay.vn. Ứng dụng giúp thợ (điện, nước, điều hoà, xây dựng, sơn, mộc…) tự tạo một thẻ hồ sơ nghề điện tử để giới thiệu tay nghề với khách hàng.
          </p>
          <p className="mt-3">
            Khi mở và sử dụng Ứng dụng, bạn xác nhận đã đọc, hiểu và đồng ý với Điều khoản sử dụng này cùng{' '}
            <Link href="/bao-mat" className="text-primary underline">Chính sách bảo mật</Link> đi kèm. Nếu không đồng ý, vui lòng ngừng sử dụng Ứng dụng.
          </p>
        </Section>

        <Section id="ban-chat-dich-vu" title="2. Bản chất dịch vụ">
          <ul className="list-disc space-y-2 pl-6">
            <li>Ứng dụng là công cụ tạo hồ sơ/danh thiếp nghề. Ground Truth cung cấp <strong className="text-on-surface">nền tảng phần mềm</strong>, không trực tiếp cung cấp dịch vụ sửa chữa và không phải là bên trung gian giao dịch giữa thợ và khách hàng.</li>
            <li>Ứng dụng <strong className="text-on-surface">miễn phí hoàn toàn</strong> với thợ. Chúng tôi không thu phí, không bán “lead” (thông tin khách) và không thu hoa hồng trên giao dịch của thợ.</li>
            <li>Ứng dụng không có chức năng đăng nhập hay tài khoản riêng: mở là dùng ngay; tên và ảnh đại diện (nếu bạn cho phép) do Zalo cung cấp.</li>
          </ul>
        </Section>

        <Section id="trach-nhiem" title="3. Trách nhiệm của người dùng">
          <p>Khi tạo hồ sơ, bạn cam kết:</p>
          <ul className="mt-3 list-disc space-y-2 pl-6">
            <li>Cung cấp thông tin trung thực, chính xác về bản thân và tay nghề của mình;</li>
            <li>Chỉ đăng ảnh công việc do chính bạn thực hiện, không dùng ảnh của người khác, ảnh sai sự thật hay ảnh vi phạm bản quyền;</li>
            <li>Không mạo danh người khác, không đăng nội dung trái pháp luật, lừa đảo, xúc phạm hoặc vi phạm thuần phong mỹ tục;</li>
            <li>Tự chịu trách nhiệm về nội dung, hình ảnh, bảng giá và cam kết dịch vụ mà bạn công bố với khách.</li>
          </ul>
        </Section>

        <Section id="noi-dung" title="4. Nội dung do người dùng tạo">
          <p>
            Bạn giữ toàn quyền với nội dung mình đăng (thông tin, ảnh việc). Khi bạn chủ động chọn đưa hồ sơ lên chợ thợ doitay.vn, bạn cho phép chúng tôi lưu trữ và hiển thị nội dung đó nhằm mục đích giúp khách hàng tìm thấy và liên hệ với bạn.
          </p>
          <p className="mt-3">
            Chúng tôi có quyền gỡ bỏ nội dung hoặc tạm khóa hồ sơ nếu phát hiện vi phạm Điều khoản này hoặc quy định pháp luật.
          </p>
        </Section>

        <Section id="gioi-han-trach-nhiem" title="5. Giới hạn trách nhiệm">
          <p>
            Ứng dụng cung cấp công cụ tạo hồ sơ, không bảo đảm bạn sẽ có khách hay có việc. Mọi giao dịch, thoả thuận giá cả, chất lượng và tranh chấp (nếu có) giữa thợ và khách hàng là quan hệ trực tiếp giữa hai bên; Ground Truth không chịu trách nhiệm cho các giao dịch này.
          </p>
          <p className="mt-3">
            Chúng tôi nỗ lực duy trì Ứng dụng hoạt động ổn định nhưng không cam kết dịch vụ luôn liên tục, không lỗi hoặc không gián đoạn.
          </p>
        </Section>

        <Section id="thay-doi-lien-he" title="6. Thay đổi & liên hệ">
          <p>
            Chúng tôi có thể cập nhật Điều khoản sử dụng theo thời gian. Bản mới nhất luôn hiển thị trong Ứng dụng và tại trang này. Việc bạn tiếp tục sử dụng sau khi cập nhật được xem là chấp nhận điều khoản mới. Điều khoản này được điều chỉnh bởi pháp luật Việt Nam.
          </p>
          <div className="mt-4 space-y-2">
            <p>
              <span className="material-symbols-outlined mr-2 align-middle text-base text-primary">business</span>
              Hộ kinh doanh Ground Truth — vận hành nền tảng doitay.vn
            </p>
            <p>
              <span className="material-symbols-outlined mr-2 align-middle text-base text-primary">mail</span>
              <a href="mailto:admin@doitay.vn" className="text-primary underline">admin@doitay.vn</a>
            </p>
            <p>
              <span className="material-symbols-outlined mr-2 align-middle text-base text-primary">call</span>
              Hotline / Zalo: <a href="tel:0972585990" className="text-primary underline">0972 585 990</a>
            </p>
          </div>
        </Section>
      </div>

      {/* Liên kết chéo + về trang chủ */}
      <div className="mt-16 space-y-4 text-center">
        <p className="text-sm text-outline">
          Đây là điều khoản cho ứng dụng <strong className="text-on-surface">Ground Truth - Hồ Sơ Thợ</strong>. Điều khoản của nền tảng doitay.vn xem tại{' '}
          <Link href="/doitay/dieu-khoan" className="text-primary underline">doitay.vn/doitay/dieu-khoan</Link>.
        </p>
        <Link
          href="/"
          className="inline-flex items-center gap-2 rounded-xl bg-surface-container-low px-6 py-3 text-sm font-semibold text-on-surface transition-colors hover:bg-surface-container"
        >
          <span className="material-symbols-outlined text-sm">arrow_back</span>
          Về trang chủ
        </Link>
      </div>
    </div>
  );
}

function Section({
  id,
  title,
  children,
}: {
  id: string;
  title: string;
  children: React.ReactNode;
}) {
  return (
    <section id={id}>
      <h2 className="font-headline text-2xl font-bold text-on-surface">{title}</h2>
      <div className="mt-4">{children}</div>
    </section>
  );
}
