<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-3xl font-sans font-bold text-white mb-2">Welcome back</h2>
      <p class="text-gray-400">Sign in to your account to continue</p>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleLogin" class="space-y-5">
      <!-- Email -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
          </div>
          <input
            v-model="form.email"
            type="email"
            required
            class="w-full pl-12 pr-4 py-3.5 bg-dark-800/80 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-brand-violet focus:ring-1 focus:ring-brand-violet/50 transition-all"
            placeholder="your@email.com"
          />
        </div>
      </div>

      <!-- Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
          </div>
          <input
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            required
            class="w-full pl-12 pr-12 py-3.5 bg-dark-800/80 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-brand-violet focus:ring-1 focus:ring-brand-violet/50 transition-all"
            placeholder="••••••••"
          />
          <button
            type="button"
            @click="showPassword = !showPassword"
            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-300 transition-colors"
          >
            <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Remember + Forgot -->
      <div class="flex items-center justify-between">
        <label class="flex items-center gap-2.5 cursor-pointer group">
          <div class="relative">
            <input v-model="form.remember" type="checkbox" class="sr-only peer" />
            <div class="w-5 h-5 rounded-md border-2 border-dark-500 bg-dark-800 peer-checked:bg-brand-violet peer-checked:border-brand-violet transition-all flex items-center justify-center">
              <svg v-if="form.remember" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <span class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors">Remember me</span>
        </label>
        <router-link
          :to="{ name: 'forgot-password' }"
          class="text-sm text-brand-cyan hover:text-brand-violet transition-colors"
        >
          Forgot password?
        </router-link>
      </div>

      <!-- Turnstile CAPTCHA -->
      <div class="flex justify-center">
        <VueTurnstile :site-key="turnstileSiteKey" v-model="turnstileToken" theme="dark" />
      </div>

      <!-- Error -->
      <div v-if="error" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20">
        <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
        </svg>
        <p class="text-sm text-red-400">{{ error }}</p>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        :disabled="authStore.loading"
        class="relative w-full py-3.5 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed transition-all"
      >
        <div class="absolute inset-0 bg-gradient-to-r from-brand-violet to-brand-cyan group-hover:opacity-90 transition-opacity"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-brand-cyan to-brand-violet opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <span class="relative flex items-center justify-center gap-2">
          <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ authStore.loading ? 'Signing in...' : 'Sign In' }}
        </span>
      </button>
    </form>

    <!-- Divider -->
    <div class="relative my-8">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-dark-600"></div>
      </div>
      <div class="relative flex justify-center">
        <span class="px-4 text-xs text-gray-500 bg-dark-900 uppercase tracking-wider">or continue with</span>
      </div>
    </div>

    <!-- Social Login -->
    <div class="grid grid-cols-2 gap-3">
      <button
        type="button"
        @click="loginWithGithub"
        class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-dark-800/60 border border-dark-600 text-gray-300 hover:bg-dark-700 hover:border-dark-500 transition-all group"
      >
        <svg class="w-5 h-5 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
          <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
        </svg>
        <span class="text-sm font-medium">GitHub</span>
      </button>
      <button
        type="button"
        @click="loginWithGoogle"
        class="flex items-center justify-center gap-2.5 px-4 py-3 rounded-xl bg-dark-800/60 border border-dark-600 text-gray-300 hover:bg-dark-700 hover:border-dark-500 transition-all group"
      >
        <svg class="w-5 h-5 group-hover:text-white transition-colors" viewBox="0 0 24 24" fill="currentColor">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        <span class="text-sm font-medium">Google</span>
      </button>
    </div>

    <!-- Register Link -->
    <p class="text-center text-sm text-gray-500 mt-8">
      Don't have an account?
      <router-link
        :to="{ name: 'register' }"
        class="text-brand-cyan hover:text-brand-violet font-semibold transition-colors"
      >
        Create account
      </router-link>
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import VueTurnstile from 'vue-turnstile'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const config = useRuntimeConfig()

const turnstileSiteKey = useTurnstileSiteKey()
const turnstileToken = ref('')
const form = ref({ email: '', password: '', remember: false })
const error = ref('')
const showPassword = ref(false)

function loginWithGoogle() {
  const backendUrl = (config.public.apiUrl || 'http://127.0.0.1:8000/api').replace(/\/api\/?$/, '')
  window.location.href = `${backendUrl}/auth/google/redirect`
}

function loginWithGithub() {
  const backendUrl = (config.public.apiUrl || 'http://127.0.0.1:8000/api').replace(/\/api\/?$/, '')
  window.location.href = `${backendUrl}/auth/github/redirect`
}

async function handleLogin() {
  error.value = ''
  try {
    await authStore.login({ ...form.value, turnstile_token: turnstileToken.value })
    const redirect = route.query.redirect || (authStore.isAdmin ? '/admin' : '/')
    router.push(redirect)
  } catch (e) {
    if (!e.response) {
      error.value = 'Network error. Please check your connection.'
    } else if (e.response.status === 502) {
      error.value = 'Server is starting up. Please wait a moment and try again.'
    } else {
      error.value = e.response?.data?.message || 'Invalid credentials'
    }
  }
}
</script>
