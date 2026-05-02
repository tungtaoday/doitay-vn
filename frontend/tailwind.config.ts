import type { Config } from 'tailwindcss';

const config: Config = {
  content: ['./src/**/*.{ts,tsx}'],
  theme: {
    extend: {
      colors: {
        // ── Primary — xanh mòng két đậm #006781 ─────────────────────
        primary:                    '#006781',
        'on-primary':               '#FFFFFF',
        'primary-container':        '#48BBE2',   // xanh lơ sáng
        'on-primary-container':     '#001F2A',
        'primary-fixed':            '#65D4FC',   // xanh lơ nhạt nhất
        'primary-fixed-dim':        '#48BBE2',
        'on-primary-fixed':         '#001F2A',
        'on-primary-fixed-variant': '#004D62',

        // ── Secondary — navy đậm #102F4B ─────────────────────────────
        secondary:                  '#102F4B',
        'on-secondary':             '#FFFFFF',
        'secondary-container':      '#C4D8E8',
        'on-secondary-container':   '#001D35',

        // ── Tertiary — vàng đồng (verified badges, stars) ────────────
        tertiary:                   '#705D00',
        'on-tertiary':              '#FFFFFF',
        'tertiary-fixed':           '#FFE16D',
        'on-tertiary-fixed':        '#221B00',
        'on-tertiary-fixed-variant':'#534400',
        'tertiary-container':       '#FFE16D',
        'on-tertiary-container':    '#221B00',

        // ── Error ─────────────────────────────────────────────────────
        error:                      '#BA1A1A',
        'on-error':                 '#FFFFFF',
        'error-container':          '#FFDAD6',
        'on-error-container':       '#410002',

        // ── Surface / Neutral ─────────────────────────────────────────
        background:                 '#F8F9FF',
        'on-background':            '#102F4B',
        surface:                    '#F8F9FF',
        'surface-bright':           '#FDFDFF',
        'on-surface':               '#001D35',
        'on-surface-variant':       '#3E484D',   // xám/slate cho text phụ
        'surface-container-lowest': '#FFFFFF',
        'surface-container-low':    '#EEF4FF',   // card nền xanh rất nhạt
        'surface-container':        '#DAE9FF',   // card nền xanh nhạt
        'surface-container-high':   '#C8DAEF',
        'surface-container-highest':'#B5CEEA',
        outline:                    '#6B8099',
        'outline-variant':          '#B0BEC8',
        'inverse-on-surface':       '#EEF4FF',
      },

      fontFamily: {
        headline: ['var(--font-be-vietnam-pro)', 'system-ui', 'sans-serif'],
        sans:     ['var(--font-inter)', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
      },

      borderRadius: {
        '4xl': '2rem',
        '5xl': '3rem',
      },

      boxShadow: {
        ambient: '0 4px 32px 0 rgba(16, 47, 75, 0.06), 0 1px 4px 0 rgba(16, 47, 75, 0.04)',
        soft:    '0 2px 16px 0 rgba(16, 47, 75, 0.04)',
      },
    },
  },
  plugins: [],
};

export default config;
