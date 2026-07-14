import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Liên hệ — doitay.vn',
  description: 'Liên hệ với đội ngũ doitay.vn — hỗ trợ khách hàng, hợp tác kinh doanh và tuyển dụng thợ.',
  alternates: { canonical: '/lien-he' },
};

const CONTACT_ITEMS = [
  {
    icon: 'call',
    label: 'Hotline hỗ trợ',
    value: '0972 585 990',
    sub: 'Thứ 2 – Thứ 7, 8:00 – 20:00',
    href: 'tel:0972585990',
  },
  {
    icon: 'mail',
    label: 'Email hỗ trợ',
    value: 'admin@doitay.vn',
    sub: 'Phản hồi trong vòng 2 giờ',
    href: 'mailto:admin@doitay.vn',
  },
  {
    icon: 'mail',
    label: 'Hợp tác kinh doanh',
    value: 'admin@doitay.vn',
    sub: 'Partnership & press',
    href: 'mailto:admin@doitay.vn',
  },
  {
    icon: 'location_on',
    label: 'Văn phòng',
    value: 'TP. Hồ Chí Minh, Việt Nam',
    sub: 'Quận 7, HCM',
    href: null,
  },
];

const TOPICS = [
  { icon: 'help', title: 'Hỗ trợ đặt lịch', desc: 'Có vấn đề khi đặt lịch hẹn hoặc thanh toán? Hotline và email support luôn sẵn sàng.' },
  { icon: 'engineering', title: 'Đăng ký làm thợ', desc: 'Muốn trở thành thợ trên doitay.vn? Xem hướng dẫn đăng ký hoặc liên hệ trực tiếp để được hỗ trợ nhanh.' },
  { icon: 'handshake', title: 'Hợp tác & truyền thông', desc: 'PR, quảng cáo, hợp tác thương hiệu hoặc phỏng vấn báo chí — liên hệ admin@doitay.vn.' },
  { icon: 'report', title: 'Báo cáo vi phạm', desc: 'Phát hiện hành vi gian lận hoặc hồ sơ giả? Gửi báo cáo qua email để chúng tôi xử lý trong 24 giờ.' },
];

export default function ContactPage() {
  return (
    <div className="overflow-hidden">

      {/* ── Hero ── */}
      <section className="bg-surface py-24">
        <div className="mx-auto max-w-4xl px-6 text-center lg:px-8">
          <span className="inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
            <span className="material-symbols-outlined text-sm">support_agent</span>
            Hỗ trợ 24/7
          </span>
          <h1 className="mt-6 font-headline text-5xl font-bold tracking-tight text-on-surface">
            Chúng tôi luôn<br />
            <span className="text-primary">lắng nghe bạn</span>
          </h1>
          <p className="mx-auto mt-6 max-w-xl text-xl leading-relaxed text-on-surface-variant">
            Dù bạn là khách hàng, thợ lành nghề hay đối tác — đội ngũ doitay.vn luôn sẵn sàng hỗ trợ.
          </p>
        </div>
      </section>

      {/* ── Contact cards ── */}
      <section className="bg-surface-container-low py-20">
        <div className="mx-auto max-w-6xl px-6 lg:px-8">
          <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {CONTACT_ITEMS.map((item) => (
              <div
                key={item.label}
                className="rounded-2xl bg-surface-container-lowest p-8 transition-all hover:-translate-y-1 hover:shadow-ambient"
              >
                <div className="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10">
                  <span className="material-symbols-outlined text-2xl text-primary">{item.icon}</span>
                </div>
                <p className="mb-1 text-xs font-bold uppercase tracking-widest text-secondary">{item.label}</p>
                {item.href ? (
                  <a
                    href={item.href}
                    className="block font-headline text-lg font-bold text-on-surface hover:text-primary"
                  >
                    {item.value}
                  </a>
                ) : (
                  <p className="font-headline text-lg font-bold text-on-surface">{item.value}</p>
                )}
                <p className="mt-1 text-sm text-on-surface-variant">{item.sub}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Topic cards ── */}
      <section className="bg-surface py-20">
        <div className="mx-auto max-w-6xl px-6 lg:px-8">
          <h2 className="mb-12 text-center font-headline text-3xl font-bold text-on-surface">
            Bạn cần hỗ trợ về vấn đề gì?
          </h2>
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            {TOPICS.map((t) => (
              <div key={t.title} className="rounded-2xl bg-surface-container-low p-7">
                <span className="material-symbols-outlined mb-4 block text-3xl text-primary">{t.icon}</span>
                <h3 className="mb-2 font-headline text-lg font-bold text-on-surface">{t.title}</h3>
                <p className="text-sm leading-relaxed text-on-surface-variant">{t.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* ── Contact form ── */}
      <section className="bg-surface-container-low py-20">
        <div className="mx-auto max-w-2xl px-6 lg:px-8">
          <div className="rounded-3xl bg-surface-container-lowest p-10 shadow-ambient">
            <h2 className="mb-2 font-headline text-2xl font-bold text-on-surface">Gửi tin nhắn</h2>
            <p className="mb-8 text-sm text-on-surface-variant">
              Điền form bên dưới — chúng tôi sẽ phản hồi trong vòng 2 giờ (giờ hành chính).
            </p>
            <form
              action="mailto:admin@doitay.vn"
              method="get"
              encType="text/plain"
              className="space-y-5"
            >
              <div className="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                  <label className="mb-1.5 block text-sm font-semibold text-on-surface">Họ và tên</label>
                  <input
                    name="name"
                    type="text"
                    placeholder="Nguyễn Văn A"
                    className="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                  />
                </div>
                <div>
                  <label className="mb-1.5 block text-sm font-semibold text-on-surface">Số điện thoại</label>
                  <input
                    name="phone"
                    type="tel"
                    placeholder="0912 345 678"
                    className="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                  />
                </div>
              </div>
              <div>
                <label className="mb-1.5 block text-sm font-semibold text-on-surface">Email</label>
                <input
                  name="email"
                  type="email"
                  placeholder="email@example.com"
                  className="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                />
              </div>
              <div>
                <label className="mb-1.5 block text-sm font-semibold text-on-surface">Nội dung</label>
                <textarea
                  name="body"
                  rows={5}
                  placeholder="Mô tả vấn đề bạn cần hỗ trợ..."
                  className="w-full rounded-xl border border-outline-variant bg-surface px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
                />
              </div>
              <button
                type="submit"
                className="w-full rounded-xl bg-primary px-6 py-3.5 font-headline font-bold text-on-primary shadow-ambient transition-all active:scale-[0.98] hover:brightness-105"
              >
                Gửi tin nhắn
              </button>
            </form>
          </div>
        </div>
      </section>

    </div>
  );
}
