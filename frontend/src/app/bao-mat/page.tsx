import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Chính sách bảo mật — doitay.vn',
  description: 'Chính sách bảo mật thông tin cá nhân của doitay.vn — cách chúng tôi thu thập, sử dụng và bảo vệ dữ liệu của bạn.',
  alternates: { canonical: '/bao-mat' },
};

export default function PrivacyPage() {
  return (
    <div className="bg-surface">
      <div className="mx-auto max-w-3xl px-6 py-20 lg:px-8">

        <header className="mb-16 text-center">
          <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
            <span className="material-symbols-outlined text-sm">lock</span>
            Bảo mật thông tin
          </span>
          <h1 className="mt-6 font-headline text-4xl font-bold text-on-surface">Chính sách bảo mật</h1>
          <p className="mt-4 text-on-surface-variant">Cập nhật lần cuối: 14/07/2026</p>
        </header>

        <div className="prose-custom space-y-10 text-on-surface-variant">

          <Section title="1. Thông tin chúng tôi thu thập">
            <p>Khi bạn sử dụng doitay.vn hoặc ứng dụng Zalo Mini App &ldquo;Doitay - Hồ Sơ Thợ&rdquo;, chúng tôi có thể thu thập các thông tin sau:</p>
            <ul>
              <li><strong className="text-on-surface">Thông tin tài khoản:</strong> Họ tên, số điện thoại, địa chỉ email, ảnh đại diện.</li>
              <li><strong className="text-on-surface">Số điện thoại qua Zalo:</strong> Khi bạn sử dụng ứng dụng Zalo Mini App và đồng ý cấp quyền, chúng tôi có thể nhận số điện thoại từ Zalo để điền sẵn thông tin liên hệ trên hồ sơ. Bạn có thể từ chối và nhập số thủ công.</li>
              <li><strong className="text-on-surface">Thông tin sử dụng:</strong> Lịch sử đặt lịch, đánh giá, giao dịch ví.</li>
              <li><strong className="text-on-surface">Thông tin thiết bị:</strong> Địa chỉ IP, loại trình duyệt, hệ điều hành (cho mục đích bảo mật và tối ưu dịch vụ).</li>
              <li><strong className="text-on-surface">Thông tin xác minh thợ:</strong> CMND/CCCD, ảnh công trình, thông tin nghề nghiệp.</li>
            </ul>
          </Section>

          <Section title="2. Mục đích sử dụng thông tin">
            <p>Thông tin thu thập được sử dụng để:</p>
            <ul>
              <li>Cung cấp và cải thiện dịch vụ kết nối thợ – khách hàng.</li>
              <li>Xác minh danh tính thợ và đảm bảo an toàn cho cộng đồng.</li>
              <li>Gửi thông báo lịch hẹn, xác nhận thanh toán và cập nhật tài khoản.</li>
              <li>Phòng chống gian lận và bảo vệ quyền lợi người dùng.</li>
              <li>Phân tích và cải thiện trải nghiệm người dùng (dữ liệu ẩn danh).</li>
            </ul>
          </Section>

          <Section title="3. Chia sẻ thông tin">
            <p>Chúng tôi <strong className="text-on-surface">không bán</strong> thông tin cá nhân của bạn cho bên thứ ba. Thông tin chỉ được chia sẻ trong các trường hợp:</p>
            <ul>
              <li><strong className="text-on-surface">Giữa thợ và khách hàng:</strong> Sau khi đặt lịch được xác nhận, thông tin liên hệ cơ bản được chia sẻ để tổ chức công việc.</li>
              <li><strong className="text-on-surface">Đối tác dịch vụ:</strong> Nhà cung cấp thanh toán, dịch vụ SMS, cloud hosting — theo hợp đồng bảo mật nghiêm ngặt.</li>
              <li><strong className="text-on-surface">Yêu cầu pháp lý:</strong> Khi có lệnh của cơ quan nhà nước có thẩm quyền.</li>
            </ul>
          </Section>

          <Section title="4. Bảo vệ dữ liệu">
            <p>Chúng tôi áp dụng các biện pháp kỹ thuật và tổ chức để bảo vệ dữ liệu:</p>
            <ul>
              <li>Mã hoá HTTPS/TLS cho toàn bộ kết nối.</li>
              <li>Mật khẩu được hash bằng bcrypt — không ai có thể đọc mật khẩu gốc.</li>
              <li>Token xác thực httpOnly cookie, không lưu trên localStorage.</li>
              <li>Kiểm soát truy cập dữ liệu theo vai trò (admin, thợ, khách hàng).</li>
            </ul>
          </Section>

          <Section title="5. Quyền của bạn">
            <p>Bạn có các quyền sau đối với dữ liệu cá nhân:</p>
            <ul>
              <li><strong className="text-on-surface">Truy cập:</strong> Xem thông tin chúng tôi đang lưu về bạn qua trang hồ sơ.</li>
              <li><strong className="text-on-surface">Chỉnh sửa:</strong> Cập nhật thông tin bất kỳ lúc nào trong cài đặt tài khoản.</li>
              <li><strong className="text-on-surface">Xoá tài khoản:</strong> Gửi yêu cầu xoá tài khoản qua email admin@doitay.vn. Dữ liệu sẽ được xoá trong 30 ngày.</li>
              <li><strong className="text-on-surface">Phản đối:</strong> Từ chối nhận email marketing qua link huỷ trong mỗi email.</li>
            </ul>
          </Section>

          <Section title="6. Cookie">
            <p>
              doitay.vn sử dụng cookie thiết yếu để duy trì phiên đăng nhập và bảo mật. Chúng tôi không sử dụng cookie theo dõi của bên thứ ba cho mục đích quảng cáo.
            </p>
          </Section>

          <Section title="7. Thay đổi chính sách">
            <p>
              Khi có thay đổi quan trọng, chúng tôi sẽ thông báo qua email đã đăng ký ít nhất 7 ngày trước khi áp dụng. Việc tiếp tục sử dụng dịch vụ sau thông báo được xem là đồng ý với chính sách mới.
            </p>
          </Section>

          <Section title="8. Liên hệ">
            <p>
              Mọi thắc mắc về chính sách bảo mật, vui lòng liên hệ:{' '}
              <a href="mailto:admin@doitay.vn" className="font-semibold text-primary hover:underline">admin@doitay.vn</a>
              {' '}hoặc hotline{' '}
              <a href="tel:0972585990" className="font-semibold text-primary hover:underline">0972 585 990</a>.
            </p>
          </Section>

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
