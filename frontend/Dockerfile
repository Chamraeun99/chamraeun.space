# ===========================================
# Stage 1: Base (shared package files)
# ===========================================
FROM node:20-alpine AS base
WORKDIR /app
COPY package.json package-lock.json* ./

# ===========================================
# Stage 2: Development (docker-compose local)
# ===========================================
FROM base AS dev
RUN npm install
COPY . .
EXPOSE 3000
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "3000"]

# ===========================================
# Stage 3: Build (production bundle)
# ===========================================
FROM base AS build
# Render / CI: set these so the client bundle contains the correct Turnstile site key and API URLs.
# Falls back to .env.production in the repo when args are unset.
ARG NUXT_PUBLIC_TURNSTILE_SITE_KEY
ARG NUXT_PUBLIC_API_URL
ARG NUXT_PUBLIC_SITE_URL
ENV NUXT_PUBLIC_TURNSTILE_SITE_KEY=$NUXT_PUBLIC_TURNSTILE_SITE_KEY
ENV NUXT_PUBLIC_API_URL=$NUXT_PUBLIC_API_URL
ENV NUXT_PUBLIC_SITE_URL=$NUXT_PUBLIC_SITE_URL
RUN npm ci
COPY . .
ENV NODE_ENV=production
RUN npm run build

# ===========================================
# Stage 4: Production (Render / Nuxt SSR)
# ===========================================
FROM node:20-alpine AS production
WORKDIR /app
ENV NODE_ENV=production

# Copy Nuxt production output (server + public assets)
COPY --from=build /app/.output ./.output

EXPOSE 10000

CMD ["node", ".output/server/index.mjs"]
