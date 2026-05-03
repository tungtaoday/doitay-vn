import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Điều khoản & Chính sách bảo mật — doitay.vn',
  description:
    'Điều khoản sử dụng và chính sách bảo mật dữ liệu cá nhân của nền tảng doitay.vn.',
  alternates: { canonical: '/dieu-khoan' },
};

export default function TermsPage() {
  return (
    <div className="mx-auto max-w-3xl px-6 pb-32 pt-16 lg:px-8">
      {/* Header */}
      <nav className="mb-8 flex items-center gap-2 text-sm text-outline">
        <Link href="/" className="transition-colors hover:text-primary">Trang chủ</Link>
        <span className="material-symbols-outlined text-xs">chevron_right</span>
        <span className="font-medium text-on-surface">Điều khoản &amp; Bảo mật</span>
      </nav>

      <h1 className="font-headline text-4xl font-bold tracking-tight text-on-surface">
        Điều khoản &amp; Chính sách bảo mật
      </h1>
      <p className="mt-3 text-sm text-outline">Cập nhật lần cuối: 01/05/2026</p>

      <div className="mt-12 space-y-16 text-on-surface-variant leading-relaxed">

        {/* 1. Điều khoản sử dụng */}
        <Section id="dieu-khoan-su-dung" title="1. Điều khoản sử dụng dịch vụ">
          <p>
            Bằng cách truy cập hoặc sử dụng nền tảng <strong className="text-on-surface">doitay.vn</strong>, bạn đồng ý bị ràng buộc bởi các điều khoản sau. Nếu bạn không đồng ý, vui lòng không sử dụng dịch vụ.
          </p>
          <ul className="mt-4 list-disc space-y-2 pl-6">
            <li>doitay.vn là nền tảng trung gian kết nối chủ nhà với thợ lành nghề. Chúng tôi không trực tiếp cung cấp dịch vụ thi công.</li>
            <li>Người dùng chịu trách nhiệm về tính chính xác của thông tin cá nhân cung cấp khi đăng ký.</li>
            <li>Nghiêm cấm sử dụng nền tảng để gian lận, quấy rối, hoặc vi phạm pháp luật Việt Nam.</li>
            <li>doitay.vn có quyền đình chỉ tài khoản vi phạm điều khoản mà không cần thông báo trước.</li>
            <li>Các giao dịch tài chính qua ví doitay không thể hoàn tác sau khi đã xác nhận.</li>
          </ul>
        </Section>

        {/* 2. Chính sách bảo mật */}
        <Section id="chinh-sach-bao-mat" title="2. Chính sách bảo mật dữ liệu">
          <p>
            Chúng tôi cam kết bảo vệ dữ liệu cá nhân của bạn theo quy định của pháp luật Việt Nam về bảo vệ thông tin cá nhân (Nghị định 13/2023/NĐ-CP).
          </p>

          <h3 className="mt-6 font-headline text-lg font-bold text-on-surface">2.1 Dữ liệu chúng tôi thu thập</h3>
          <ul className="mt-3 list-disc space-y-2 pl-6">
            <li><strong className="text-on-surface">Thông tin nhận dạng:</strong> Họ tên, số điện thoại, địa chỉ email.</li>
            <li><strong className="text-on-surface">Thông tin địa lý:</strong> Tỉnh/thành, quận/huyện, địa chỉ (để kết nối với thợ gần nhất).</li>
            <li><strong className="text-on-surface">Lịch sử giao dịch:</strong> Lịch hẹn, đánh giá, giao dịch ví điện tử.</li>
            <li><strong className="text-on-surface">Dữ liệu sử dụng:</strong> Thời gian truy cập, thiết bị, IP (cho bảo mật tài khoản).</li>
          </ul>

          <h3 className="mt-6 font-headline text-lg font-bold text-on-surface">2.2 Mục đích sử dụng dữ liệu</h3>
          <ul className="mt-3 list-disc space-y-2 pl-6">
            <li>Xác thực tài khoản và ngăn chặn gian lận.</li>
            <li>Kết nối chủ nhà với thợ phù hợp theo khu vực và ngành nghề.</li>
            <li>Gửi thông báo liên quan đến lịch hẹn, thanh toán, và cập nhật dịch vụ.</li>
            <li>Cải thiện trải nghiệm sản phẩm dựa trên dữ liệu sử dụng ẩn danh.</li>
          </ul>

          <h3 className="mt-6 font-headline text-lg font-bold text-on-surface">2.3 Chia sẻ dữ liệu</h3>
          <p className="mt-3">
            Chúng tôi <strong className="text-on-surface">không bán</strong> dữ liệu cá nhân của bạn. Dữ liệu chỉ được chia sẻ trong các trường hợp:
          </p>
          <ul className="mt-3 list-disc space-y-2 pl-6">
            <li>Giữa chủ nhà và thợ được kết nối (chỉ thông tin cần thiết cho lịch hẹn).</li>
            <li>Với cơ quan nhà nước khi có yêu cầu hợp pháp bằng văn bản.</li>
            <li>Với đối tác kỹ thuật (hosting, email, SMS) theo hợp đồng bảo mật.</li>
          </ul>

          <h3 className="mt-6 font-headline text-lg font-bold text-on-surface">2.4 Thời gian lưu trữ</h3>
          <p className="mt-3">
            Dữ liệu tài khoản được lưu trữ trong suốt thời gian tài khoản hoạt động. Sau khi xóa tài khoản, dữ liệu nhận dạng cá nhân sẽ bị xóa trong vòng <strong className="text-on-surface">30 ngày</strong>, trừ dữ liệu giao dịch tài chính cần lưu trữ theo quy định pháp luật (tối đa 5 năm).
          </p>
        </Section>

        {/* 3. Quyền của người dùng */}
        <Section id="quyen-nguoi-dung" title="3. Quyền của người dùng">
          <p>Bạn có đầy đủ các quyền sau đối với dữ liệu của mình:</p>
          <ul className="mt-4 list-disc space-y-3 pl-6">
            <li>
              <strong className="text-on-surface">Quyền truy cập:</strong> Yêu cầu xem toàn bộ dữ liệu cá nhân chúng tôi đang lưu trữ.
            </li>
            <li>
              <strong className="text-on-surface">Quyền chỉnh sửa:</strong> Cập nhật thông tin cá nhân trực tiếp trong tài khoản.
            </li>
            <li>
              <strong className="text-on-surface">Quyền xóa dữ liệu:</strong> Yêu cầu xóa toàn bộ dữ liệu cá nhân. Gửi yêu cầu qua email{' '}
              <a href="mailto:admin@doitay.vn" className="text-primary underline">admin@doitay.vn</a>{' '}
              hoặc gọi <a href="tel:0972585990" className="text-primary underline">0972 585 990</a>.
            </li>
            <li>
              <strong className="text-on-surface">Quyền phản đối:</strong> Phản đối việc xử lý dữ liệu cho mục đích tiếp thị.
            </li>
            <li>
              <strong className="text-on-surface">Quyền rút sự đồng ý:</strong> Thu hồi sự đồng ý bất kỳ lúc nào mà không ảnh hưởng đến các xử lý đã thực hiện trước đó.
            </li>
          </ul>
        </Section>

        {/* 4. Cookie */}
        <Section id="cookie" title="4. Cookie và theo dõi">
          <p>
            doitay.vn sử dụng cookie cần thiết để duy trì phiên đăng nhập an toàn. Chúng tôi không sử dụng cookie theo dõi quảng cáo bên thứ ba. Bạn có thể xóa cookie bất kỳ lúc nào qua cài đặt trình duyệt mà không ảnh hưởng đến tính năng cơ bản của trang web.
          </p>
        </Section>

        {/* 5. Bảo mật */}
        <Section id="bao-mat" title="5. Bảo mật hệ thống">
          <p>
            Chúng tôi áp dụng các biện pháp kỹ thuật để bảo vệ dữ liệu, bao gồm mã hóa HTTPS, xác thực token Sanctum, và giới hạn tốc độ API. Mật khẩu được băm một chiều và nhân viên doitay.vn không thể đọc mật khẩu của bạn. Tuy nhiên, không có hệ thống nào đảm bảo an toàn tuyệt đối — vui lòng bảo mật thông tin đăng nhập của bạn.
          </p>
        </Section>

        {/* 6. Liên hệ */}
        <Section id="lien-he" title="6. Liên hệ về bảo mật">
          <p>
            Mọi yêu cầu liên quan đến dữ liệu cá nhân, vui lòng liên hệ:
          </p>
          <div className="mt-4 space-y-2">
            <p>
              <span className="material-symbols-outlined mr-2 text-base align-middle text-primary">mail</span>
              <a href="mailto:admin@doitay.vn" className="text-primary underline">admin@doitay.vn</a>
            </p>
            <p>
              <span className="material-symbols-outlined mr-2 text-base align-middle text-primary">call</span>
              <a href="tel:0972585990" className="text-primary underline">0972 585 990</a>
            </p>
            <p className="text-sm text-outline">Thời gian xử lý: trong vòng 5–7 ngày làm việc.</p>
          </div>
        </Section>

      </div>

      {/* Back to top */}
      <div className="mt-16 text-center">
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
