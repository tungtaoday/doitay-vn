import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Câu hỏi thường gặp — doitay.vn',
  description: 'Giải đáp các thắc mắc thường gặp về đặt lịch thợ, thanh toán, bảo hành và đăng ký tài khoản tại doitay.vn.',
  alternates: { canonical: '/faq' },
};

const FAQS: { category: string; icon: string; items: { q: string; a: string }[] }[] = [
  {
    category: 'Đặt lịch & dịch vụ',
    icon: 'calendar_month',
    items: [
      {
        q: 'Làm thế nào để đặt lịch với thợ?',
        a: 'Tìm thợ phù hợp tại trang "Tìm thợ", xem hồ sơ chi tiết và nhấn "Đặt lịch". Điền thông tin yêu cầu — thợ sẽ xác nhận trong tối đa 15 phút.',
      },
      {
        q: 'Tôi có thể huỷ lịch hẹn không?',
        a: 'Có. Bạn có thể huỷ lịch trước 2 giờ so với giờ hẹn mà không mất phí. Huỷ trong vòng 2 giờ có thể áp dụng phí huỷ tuỳ theo thợ.',
      },
      {
        q: 'Thợ không đến đúng giờ thì xử lý thế nào?',
        a: 'Liên hệ hotline 0972 585 990 ngay. Chúng tôi sẽ liên hệ thợ và nếu cần sẽ điều phối thợ thay thế trong thời gian sớm nhất.',
      },
      {
        q: 'Tôi có thể xem trước báo giá không?',
        a: 'Có. Mỗi hồ sơ thợ đều có bảng giá dịch vụ tham khảo. Sau khi đặt lịch, thợ sẽ xác nhận giá chính xác trước khi thi công.',
      },
    ],
  },
  {
    category: 'Thanh toán & ví',
    icon: 'account_balance_wallet',
    items: [
      {
        q: 'Tôi thanh toán bằng cách nào?',
        a: 'Hiện tại hỗ trợ thanh toán trực tiếp cho thợ (tiền mặt hoặc chuyển khoản) sau khi hoàn thành dịch vụ. Tính năng thanh toán qua app đang được phát triển.',
      },
      {
        q: 'Ví điện tử trên doitay.vn dùng để làm gì?',
        a: 'Ví dành cho thợ để quản lý leads và lịch hẹn. Thợ nạp tiền vào ví để tiếp cận thông tin khách hàng và nhận leads mới.',
      },
      {
        q: 'Nạp tiền vào ví bằng hình thức nào?',
        a: 'Hỗ trợ chuyển khoản ngân hàng và ví điện tử. Sau khi chuyển khoản và upload ảnh chứng minh, admin sẽ xác nhận trong vòng 30 phút (giờ hành chính).',
      },
    ],
  },
  {
    category: 'Chất lượng & bảo hành',
    icon: 'shield',
    items: [
      {
        q: 'doitay.vn có đảm bảo chất lượng không?',
        a: 'Có. Tất cả thợ trên nền tảng đều được xác minh danh tính và thông tin liên hệ. Mọi dịch vụ từ thợ đã xác minh được bảo hành hỗ trợ lên đến 12 tháng.',
      },
      {
        q: 'Nếu không hài lòng với kết quả thi công?',
        a: 'Liên hệ chúng tôi trong vòng 7 ngày. Đội ngũ sẽ làm việc với thợ để xử lý, sửa chữa hoặc hoàn tiền tuỳ tình huống cụ thể.',
      },
      {
        q: 'Đánh giá thợ có thật không?',
        a: 'Chỉ khách hàng đã hoàn thành lịch hẹn mới có thể để lại đánh giá. Điểm số không thể mua bán hay can thiệp — đây là cam kết minh bạch của chúng tôi.',
      },
    ],
  },
  {
    category: 'Tài khoản & đăng ký thợ',
    icon: 'manage_accounts',
    items: [
      {
        q: 'Đăng ký tài khoản có mất phí không?',
        a: 'Hoàn toàn miễn phí cho cả khách hàng lẫn thợ. Tạo tài khoản và đặt lịch ngay không cần trả bất kỳ chi phí đăng ký nào.',
      },
      {
        q: 'Làm thế nào để trở thành thợ trên doitay.vn?',
        a: 'Đăng ký tài khoản → vào mục "Trở thành thợ" → điền hồ sơ và upload giấy tờ → chờ admin xét duyệt trong 1–3 ngày làm việc.',
      },
      {
        q: 'Hồ sơ thợ cần những gì để được duyệt?',
        a: 'CMND/CCCD, ảnh đại diện rõ mặt, mô tả kinh nghiệm và ít nhất 1 ảnh công trình đã làm. Thông tin càng đầy đủ thì xét duyệt càng nhanh.',
      },
      {
        q: 'Tôi quên mật khẩu thì làm thế nào?',
        a: 'Nhấn "Quên mật khẩu" ở trang đăng nhập, nhập số điện thoại đăng ký. Mã xác nhận sẽ được gửi qua SMS để đặt lại mật khẩu mới.',
      },
    ],
  },
];

export default function FaqPage() {
  return (
    <div className="overflow-hidden">

      {/* ── Hero ── */}
      <section className="bg-surface py-24">
        <div className="mx-auto max-w-4xl px-6 text-center lg:px-8">
          <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
            <span className="material-symbols-outlined text-sm">help</span>
            Giải đáp thắc mắc
          </span>
          <h1 className="mt-6 font-headline text-5xl font-bold tracking-tight text-on-surface">
            Câu hỏi<br />
            <span className="text-primary">thường gặp</span>
          </h1>
          <p className="mx-auto mt-6 max-w-xl text-xl leading-relaxed text-on-surface-variant">
            Không tìm thấy câu trả lời? Liên hệ hotline <a href="tel:0972585990" className="font-semibold text-primary hover:underline">0972 585 990</a> hoặc email <a href="mailto:admin@doitay.vn" className="font-semibold text-primary hover:underline">admin@doitay.vn</a>.
          </p>
        </div>
      </section>

      {/* ── FAQ sections ── */}
      <section className="bg-surface-container-low py-20">
        <div className="mx-auto max-w-4xl space-y-16 px-6 lg:px-8">
          {FAQS.map((section) => (
            <div key={section.category}>
              <div className="mb-8 flex items-center gap-3">
                <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10">
                  <span className="material-symbols-outlined text-xl text-primary">{section.icon}</span>
                </div>
                <h2 className="font-headline text-2xl font-bold text-on-surface">{section.category}</h2>
              </div>
              <div className="space-y-4">
                {section.items.map((item) => (
                  <details
                    key={item.q}
                    className="group rounded-2xl bg-surface-container-lowest px-6 py-5 transition-all open:shadow-ambient"
                  >
                    <summary className="flex cursor-pointer list-none items-center justify-between gap-4 font-headline font-bold text-on-surface">
                      {item.q}
                      <span className="material-symbols-outlined shrink-0 text-primary transition-transform group-open:rotate-180">
                        expand_more
                      </span>
                    </summary>
                    <p className="mt-4 border-t border-outline-variant/15 pt-4 text-sm leading-relaxed text-on-surface-variant">
                      {item.a}
                    </p>
                  </details>
                ))}
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* ── CTA ── */}
      <section className="bg-primary py-16">
        <div className="mx-auto max-w-3xl px-6 text-center lg:px-8">
          <h2 className="font-headline text-3xl font-bold text-on-primary">Vẫn còn thắc mắc?</h2>
          <p className="mt-3 text-on-primary/80">Đội ngũ hỗ trợ của chúng tôi luôn sẵn sàng giúp đỡ bạn.</p>
          <div className="mt-8 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
            <a
              href="tel:0972585990"
              className="flex items-center gap-2 rounded-xl bg-on-primary px-8 py-3.5 font-headline font-bold text-primary transition-all hover:opacity-90"
            >
              <span className="material-symbols-outlined">call</span>
              Gọi ngay
            </a>
            <Link
              href="/lien-he"
              className="flex items-center gap-2 rounded-xl border-2 border-on-primary/40 px-8 py-3.5 font-headline font-bold text-on-primary transition-all hover:border-on-primary hover:bg-on-primary/10"
            >
              <span className="material-symbols-outlined">mail</span>
              Gửi tin nhắn
            </Link>
          </div>
        </div>
      </section>

    </div>
  );
}
