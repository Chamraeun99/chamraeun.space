const devApiProxyTarget =
  process.env.NUXT_DEV_API_PROXY || process.env.VITE_DEV_API_PROXY || 'http://127.0.0.1:8000'
const isDev = process.env.NODE_ENV !== 'production'

export default defineNuxtConfig({
  // Docker-on-Windows dev can intermittently fail to generate Nitro server files.
  // Disable SSR in local dev to keep the app reliably available at localhost:3000.
  ssr: false,
  pages: true,
  devtools: { enabled: false },
  experimental: {
    // Prevent Docker+Windows dev race that can miss generated app manifest files.
    appManifest: false,
  },
  app: {
    head: {
      titleTemplate: "%s | Kalapak Code Team",
      meta: [
        { name: "description", content: "Modern tech solutions from Cambodia by Kalapak Code Team." },
        { property: "og:site_name", content: "Kalapak Code Team" },
      ],
      link: [
        { rel: 'icon', type: 'image/png', href: 'https://res.cloudinary.com/kalapak/image/upload/q_auto/f_auto/v1775860922/Logo_kalapak_om1ygl.png' },
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: 'anonymous' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600;700&family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap',
        },
      ],
    },
  },
  modules: ['@pinia/nuxt', '@nuxtjs/tailwindcss'],
  pinia: {
    storesDirs: ['./src/stores/**'],
  },
  runtimeConfig: {
    public: {
      // Prefer explicit env; in local dev fall back to Laravel directly to avoid /api 404 when proxy/env is missing.
      apiUrl: process.env.NUXT_PUBLIC_API_URL || process.env.VITE_API_URL || (isDev ? 'http://127.0.0.1:8000/api' : '/api'),
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || "https://kalapak.com",
    },
  },
  // Local dev: keep frontend requests at /api and forward them to Laravel.
  // Use NUXT_DEV_API_PROXY=http://backend:8000 when running frontend inside docker-compose.
  nitro: {
    devProxy: {
      '/api': {
        target: devApiProxyTarget,
        changeOrigin: true,
      },
    },
  },
  css: [
    'aos/dist/aos.css',
    'highlight.js/styles/github-dark.css',
    '@/assets/styles/animations.css',
    '@/assets/styles/glassmorphism.css',
    '@/assets/styles/main.css',
  ],
  alias: {
    '@': '/src',
  },
  compatibilityDate: '2026-04-24',
})
