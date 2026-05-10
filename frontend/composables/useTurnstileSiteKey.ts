/** Site key from Cloudflare Turnstile (NUXT_PUBLIC_TURNSTILE_SITE_KEY or VITE_TURNSTILE_SITE_KEY at build time). */
export function useTurnstileSiteKey(): string {
  const { public: p } = useRuntimeConfig()
  const k = p.turnstileSiteKey
  return typeof k === 'string' ? k : ''
}
