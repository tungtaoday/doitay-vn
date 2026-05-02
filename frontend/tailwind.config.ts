import type { Config } from 'tailwindcss';

const config: Config = {
  content: ['./src/**/*.{ts,tsx}'],
  theme: {
    extend: {
      colors: {
        // ── Primary — sky blue #48BBE2 ──────────────────────────────
        primary:                    '#48BBE2',
        'on-primary':               '#FFFFFF',
        'primary-container':        '#B8E6F7',
        'on-primary-container':     '#001F2A',
        'primary-fixed':            '#B8E6F7',
        'primary-fixed-dim':        '#7ECDE8',
        'on-primary-fixed':         '#001F2A',
        'on-primary-fixed-variant': '#1D4A5E',

        // ── Secondary — deep navy #102F4B ───────────────────────────
        secondary:                  '#102F4B',
        'on-secondary':             '#FFFFFF',
        'secondary-container':      '#C4D8E8',
        'on-secondary-container':   '#001D35',

        // ── Tertiary — gold #FFD700 (verified badges only) ──────────
        tertiary:                   '#FFD700',
        'on-tertiary':              '#1A1400',
        'tertiary-fixed':           '#FFD700',
        'on-tertiary-fixed':        '#1A1400',
        'on-tertiary-fixed-variant':'#3D2F00',
        'tertiary-container':       '#FFECB3',
        'on-tertiary-container':    '#1A1400',

        // ── Error ───────────────────────────────────────────────────
        error:                      '#BA1A1A',
        'on-error':                 '#FFFFFF',
        'error-container':          '#FFDAD6',
        'on-error-container':       '#410002',

        // ── Surface / Neutral ───────────────────────────────────────
        background:                 '#F8F9FF',
        'on-background':            '#102F4B',
        surface:                    '#F8F9FF',
        'surface-bright':           '#FDFDFF',
        'on-surface':               '#001D35',
        'on-surface-variant':       '#3D5066',
        'surface-container-lowest': '#FFFFFF',
        'surface-container-low':    '#EEF1F8',
        'surface-container':        '#E4E8F2',
        'surface-container-high':   '#D8DCE8',
        'surface-container-highest':'#CDD2DC',
        outline:                    '#6B8099',
        'outline-variant':          '#B0BEC8',
        'inverse-on-surface':       '#EEF1F8',
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
        // Ambient shadow tinted navy at 6% opacity
        ambient: '0 4px 32px 0 rgba(16, 47, 75, 0.06), 0 1px 4px 0 rgba(16, 47, 75, 0.04)',
        soft:    '0 2px 16px 0 rgba(16, 47, 75, 0.04)',
      },
    },
  },
  plugins: [],
};

export default config;
