import type { Metadata } from 'next';
import Link from 'next/link';

export const metadata: Metadata = {
  title: 'Về chúng tôi — doitay.vn',
  description:
    'doitay.vn — nền tảng kết nối chủ nhà với thợ lành nghề đã xác minh tại Việt Nam. Câu chuyện, sứ mệnh và cam kết của chúng tôi.',
  alternates: { canonical: '/gioi-thieu' },
};

export default function AboutPage() {
  return (
    <div className="overflow-hidden">

      {/* ── Hero ─────────────────────────────────────────────────── */}
      <section className="relative bg-surface py-28">
        <div className="mx-auto max-w-5xl px-6 text-center lg:px-8">
          <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
            <span className="material-symbols-outlined text-sm">handyman</span>
            Câu chuyện doitay.vn
          </span>
          <h1 className="mt-6 font-headline text-5xl font-bold tracking-tight text-on-surface md:text-6xl">
            Đôi tay lành nghề —<br />
            <span className="text-primary">niềm tin được xây dựng</span>
          </h1>
          <p className="mx-auto mt-6 max-w-2xl text-xl leading-relaxed text-on-surface-variant">
            doitay.vn ra đời từ một câu hỏi đơn giản: <em>Tại sao tìm được một người thợ tốt lại khó đến vậy?</em>
          </p>
        </div>
      </section>

      {/* ── Câu chuyện ───────────────────────────────────────────── */}
      <section className="bg-surface-container-low py-24">
        <div className="mx-auto grid max-w-6xl grid-cols-1 gap-16 px-6 lg:grid-cols-2 lg:px-8">
          <div className="space-y-6 text-lg leading-relaxed text-on-surface-variant">
            <p>
              Năm 2019, anh Minh — chủ nhà tại Quận 7, TP.HCM — cần sửa lại hệ thống điện sau khi chập cầu dao phòng khách. Anh hỏi hàng xóm, đăng lên Facebook, gọi qua mấy số điện thoại dán ở cột điện. Cuối cùng đón được một thợ tới. Người thợ đến muộn 3 tiếng, báo giá xong tăng thêm 40% mà không giải thích rõ. Và không hề có đánh giá nào để anh Minh biết trước điều đó.
            </p>
            <p>
              Câu chuyện của anh Minh không phải ngoại lệ — đó là trải nghiệm của hàng triệu gia đình Việt Nam mỗi năm.
            </p>
            <p>
              Ngược lại, anh Hùng — thợ sửa điện 12 năm kinh nghiệm tại Bình Dương — luôn làm việc uy tín, đúng giờ, báo giá minh bạch. Nhưng khách của anh chủ yếu chỉ đến từ giới thiệu miệng. Không có cách nào để anh tiếp cận rộng hơn, không có hồ sơ chuyên nghiệp, không có nơi để khách hàng tìm thấy anh.
            </p>
          </div>
          <div className="space-y-6 text-lg leading-relaxed text-on-surface-variant">
            <p>
              <strong className="font-headline font-bold text-on-surface">doitay.vn</strong> được tạo ra để khép lại khoảng cách đó.
            </p>
            <p>
              Tên gọi <strong className="text-primary">Đôi Tay</strong> — đôi bàn tay người thợ — là tất cả những gì chúng tôi tin vào: giá trị được tạo ra từ kỹ năng thực sự, từ mồ hôi và tâm huyết của những người thợ lành nghề đã dành cả đời mài giũa nghề nghiệp của mình.
            </p>
            <p>
              Chúng tôi xây dựng một nền tảng nơi mỗi thợ được xác minh danh tính, được đánh giá minh bạch sau mỗi công trình. Nơi chủ nhà có thể đặt lịch tức thì, nhận phản hồi trong 15 phút, và yên tâm với cam kết bảo hành 12 tháng.
            </p>
          </div>
        </div>
      </section>

      {/* ── Sứ mệnh & Giá trị ────────────────────────────────────── */}
      <section className="bg-surface py-24">
        <div className="mx-auto max-w-6xl px-6 lg:px-8">
          <div className="mb-16 text-center">
            <h2 className="font-headline text-4xl font-bold text-on-surface">Sứ mệnh &amp; Giá trị cốt lõi</h2>
            <p className="mt-4 text-lg text-on-surface-variant">
              Không chỉ là marketplace — chúng tôi đang xây dựng hệ sinh thái tin cậy cho nghề thủ công Việt Nam.
            </p>
          </div>
          <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
            {[
              {
                icon: 'verified_user',
                title: 'Xác minh thực sự',
                body: 'Mỗi thợ được xác minh danh tính và thông tin liên hệ trước khi xuất hiện trên nền tảng. Không có tài khoản ảo, không có hồ sơ giả.',
              },
              {
                icon: 'star',
                title: 'Đánh giá minh bạch',
                body: 'Chỉ khách hàng đã đặt lịch và hoàn thành dịch vụ mới được để lại đánh giá. Điểm số phản ánh thực chất, không thể mua bán.',
              },
              {
                icon: 'shield',
                title: 'Bảo hành 12 tháng',
                body: 'Mọi dịch vụ từ thợ xác minh đều được doitay.vn đảm bảo hỗ trợ bảo hành. Nếu có sự cố, chúng tôi sẽ hỗ trợ giải quyết trực tiếp.',
              },
              {
                icon: 'bolt',
                title: 'Phản hồi 15 phút',
                body: 'Sau khi đặt lịch, thợ cam kết phản hồi trong tối đa 15 phút. Không còn chờ đợi mù quáng như trước.',
              },
              {
                icon: 'local_atm',
                title: 'Báo giá minh bạch',
                body: 'Bảng giá dịch vụ được công khai trên hồ sơ thợ. Không có phí ẩn, không tăng giá sau khi đến nhà.',
              },
              {
                icon: 'handshake',
                title: 'Trao quyền cho thợ',
                body: 'Chúng tôi tin rằng người thợ giỏi xứng đáng được tìm thấy. doitay.vn là hồ sơ chuyên nghiệp miễn phí cho mọi thợ lành nghề Việt Nam.',
              },
            ].map((item) => (
              <div
                key={item.title}
                className="rounded-2xl bg-surface-container-lowest p-8 transition-all hover:-translate-y-1 hover:shadow-ambient"
              >
                <span className="material-symbols-outlined mb-4 text-3xl text-primary">{item.icon}</span>
                <h3 className="mb-3 font-headline text-xl font-bold text-on-surface">{item.title}</h3>
                <p className="text-sm leading-relaxed text-on-surface-variant">{item.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Cách hoạt động ────────────────────────────────────────── */}
      <section className="bg-surface-container-low py-24">
        <div className="mx-auto max-w-5xl px-6 lg:px-8">
          <h2 className="mb-16 text-center font-headline text-4xl font-bold text-on-surface">
            Đặt lịch chỉ trong 3 bước
          </h2>
          <div className="grid grid-cols-1 gap-10 md:grid-cols-3">
            {[
              { step: '01', icon: 'search', title: 'Tìm thợ phù hợp', body: 'Tìm theo ngành nghề, khu vực, đánh giá. Xem hồ sơ chi tiết, bảng giá, và dự án đã thực hiện của từng thợ.' },
              { step: '02', icon: 'calendar_month', title: 'Đặt lịch tức thì', body: 'Chọn ngày giờ phù hợp, điền thông tin yêu cầu. Thợ sẽ phản hồi xác nhận trong tối đa 15 phút.' },
              { step: '03', icon: 'thumb_up', title: 'Thi công & đánh giá', body: 'Thợ đến đúng hẹn, thi công đúng báo giá. Sau dịch vụ, để lại đánh giá thực tế để giúp cộng đồng.' },
            ].map((item) => (
              <div key={item.step} className="text-center">
                <div className="relative mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-primary/10">
                  <span className="material-symbols-outlined text-3xl text-primary">{item.icon}</span>
                  <span className="absolute -right-1 -top-1 flex h-7 w-7 items-center justify-center rounded-full bg-primary text-xs font-bold text-on-primary">
                    {item.step}
                  </span>
                </div>
                <h3 className="mb-3 font-headline text-xl font-bold text-on-surface">{item.title}</h3>
                <p className="text-sm leading-relaxed text-on-surface-variant">{item.body}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Con số ───────────────────────────────────────────────── */}
      <section className="bg-secondary py-20">
        <div className="mx-auto grid max-w-5xl grid-cols-2 gap-8 px-6 md:grid-cols-4 lg:px-8">
          {[
            { value: '500+', label: 'Thợ đã xác minh' },
            { value: '2.000+', label: 'Lịch hẹn thành công' },
            { value: '4,8 / 5', label: 'Điểm hài lòng trung bình' },
            { value: '15 phút', label: 'Thời gian phản hồi trung bình' },
          ].map((stat) => (
            <div key={stat.label} className="text-center">
              <div className="font-headline text-4xl font-bold text-white">{stat.value}</div>
              <div className="mt-2 text-sm font-medium text-white/60">{stat.label}</div>
            </div>
          ))}
        </div>
      </section>

      {/* ── Liên hệ ─────────────────────────────────────────────── */}
      <section className="bg-surface py-24">
        <div className="mx-auto max-w-3xl px-6 text-center lg:px-8">
          <h2 className="font-headline text-4xl font-bold text-on-surface">Liên hệ với chúng tôi</h2>
          <p className="mt-4 text-lg text-on-surface-variant">
            Có câu hỏi? Muốn hợp tác? Chúng tôi luôn lắng nghe.
          </p>
          <div className="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
            <a
              href="tel:0972585990"
              className="flex items-center gap-3 rounded-xl bg-surface-container-low px-8 py-4 font-semibold text-on-surface transition-colors hover:bg-surface-container"
            >
              <span className="material-symbols-outlined text-primary">call</span>
              0972 585 990
            </a>
            <a
              href="mailto:admin@doitay.vn"
              className="flex items-center gap-3 rounded-xl bg-primary px-8 py-4 font-semibold text-on-primary shadow-ambient transition-all hover:brightness-105"
            >
              <span className="material-symbols-outlined">mail</span>
              admin@doitay.vn
            </a>
          </div>
        </div>
      </section>

      {/* ── CTA ─────────────────────────────────────────────────── */}
      <section className="bg-primary py-20">
        <div className="mx-auto max-w-3xl px-6 text-center lg:px-8">
          <h2 className="font-headline text-4xl font-bold text-on-primary">
            Bắt đầu ngay hôm nay
          </h2>
          <p className="mt-4 text-lg text-on-primary/80">
            Tìm thợ lành nghề cho ngôi nhà của bạn — hoặc đăng ký hồ sơ thợ miễn phí.
          </p>
          <div className="mt-10 flex flex-col items-center gap-4 sm:flex-row sm:justify-center">
            <Link
              href="/tho"
              className="rounded-xl bg-on-primary px-8 py-4 font-headline font-bold text-primary transition-all hover:opacity-90 active:scale-[0.98]"
            >
              Tìm thợ ngay
            </Link>
            <Link
              href="/dang-ky"
              className="rounded-xl border-2 border-on-primary/40 px-8 py-4 font-headline font-bold text-on-primary transition-all hover:border-on-primary hover:bg-on-primary/10"
            >
              Đăng ký làm thợ
            </Link>
          </div>
        </div>
      </section>

    </div>
  );
}
