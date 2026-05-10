<template>
  <div>
    <!-- Header -->
    <div class="mb-8">
      <h2 class="text-3xl font-sans font-bold text-white mb-2">Create account</h2>
      <p class="text-gray-400">Join the Kalapak Code Team community</p>
    </div>

    <!-- Form -->
    <form @submit.prevent="handleRegister" class="space-y-5">
      <!-- Full Name -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
          </div>
          <input
            v-model="form.name"
            type="text"
            required
            class="w-full pl-12 pr-4 py-3.5 bg-dark-800/80 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-brand-violet focus:ring-1 focus:ring-brand-violet/50 transition-all"
            placeholder="Your full name"
          />
        </div>
      </div>

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
            minlength="8"
            class="w-full pl-12 pr-12 py-3.5 bg-dark-800/80 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-brand-violet focus:ring-1 focus:ring-brand-violet/50 transition-all"
            placeholder="Min. 8 characters"
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
        <!-- Password Strength -->
        <div v-if="form.password" class="mt-3 space-y-2">
          <div class="flex gap-1.5">
            <div
              v-for="i in 4"
              :key="i"
              class="h-1.5 flex-1 rounded-full transition-all duration-300"
              :class="i <= passwordStrength.score ? passwordStrength.color : 'bg-dark-600'"
            ></div>
          </div>
          <p class="text-xs" :class="passwordStrength.textColor">{{ passwordStrength.label }}</p>
        </div>
      </div>

      <!-- Confirm Password -->
      <div>
        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
            </svg>
          </div>
          <input
            v-model="form.password_confirmation"
            :type="showConfirm ? 'text' : 'password'"
            required
            class="w-full pl-12 pr-12 py-3.5 bg-dark-800/80 border border-dark-600 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-brand-violet focus:ring-1 focus:ring-brand-violet/50 transition-all"
            placeholder="Re-enter password"
          />
          <button
            type="button"
            @click="showConfirm = !showConfirm"
            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 hover:text-gray-300 transition-colors"
          >
            <svg v-if="!showConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
            </svg>
          </button>
          <!-- Match indicator -->
          <div v-if="form.password_confirmation" class="absolute inset-y-0 right-10 flex items-center pr-2">
            <svg v-if="passwordsMatch" class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <svg v-else class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
      </div>

      <!-- Terms -->
      <div>
        <label class="flex items-start gap-3 cursor-pointer group">
          <div class="relative mt-0.5">
            <input v-model="acceptTerms" type="checkbox" class="sr-only peer" />
            <div class="w-5 h-5 rounded-md border-2 border-dark-500 bg-dark-800 peer-checked:bg-brand-violet peer-checked:border-brand-violet transition-all flex items-center justify-center">
              <svg v-if="acceptTerms" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
              </svg>
            </div>
          </div>
          <span class="text-sm text-gray-400 group-hover:text-gray-300 transition-colors leading-relaxed">
            I agree to the
            <a href="#" class="text-brand-cyan hover:text-brand-violet transition-colors">Terms of Service</a>
            and
            <a href="#" class="text-brand-cyan hover:text-brand-violet transition-colors">Privacy Policy</a>
          </span>
        </label>
      </div>

      <!-- Turnstile CAPTCHA (omit when no site key to avoid console errors) -->
      <div v-if="turnstileSiteKey" class="flex justify-center">
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
        :disabled="authStore.loading || !acceptTerms"
        class="relative w-full py-3.5 rounded-xl font-semibold text-white overflow-hidden group disabled:opacity-50 disabled:cursor-not-allowed transition-all"
      >
        <div class="absolute inset-0 bg-gradient-to-r from-brand-violet to-brand-cyan group-hover:opacity-90 transition-opacity"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-brand-cyan to-brand-violet opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
        <span class="relative flex items-center justify-center gap-2">
          <svg v-if="authStore.loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ authStore.loading ? 'Creating account...' : 'Create Account' }}
        </span>
      </button>
    </form>

    <!-- Divider -->
    <div class="relative my-8">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-dark-600"></div>
      </div>
      <div class="relative flex justify-center">
        <span class="px-4 text-xs text-gray-500 bg-dark-900 uppercase tracking-wider">or sign up with</span>
      </div>
    </div>

    <!-- Social -->
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

    <!-- Login Link -->
    <p class="text-center text-sm text-gray-500 mt-8">
      Already have an account?
      <router-link
        :to="{ name: 'login' }"
        class="text-brand-cyan hover:text-brand-violet font-semibold transition-colors"
      >
        Sign in
      </router-link>
    </p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { getOAuthRedirectUrl } from '@/services/api'
import { formatAuthRequestMessage } from '@/utils/authRequestError'
import VueTurnstile from 'vue-turnstile'

const router = useRouter()
const authStore = useAuthStore()

const turnstileSiteKey = useTurnstileSiteKey()
const turnstileToken = ref('')

function loginWithGoogle() {
  window.location.href = getOAuthRedirectUrl('google')
}

function loginWithGithub() {
  window.location.href = getOAuthRedirectUrl('github')
}

const form = ref({ name: '', email: '', password: '', password_confirmation: '' })
const error = ref('')
const showPassword = ref(false)
const showConfirm = ref(false)
const acceptTerms = ref(false)

const passwordsMatch = computed(() => {
  return form.value.password && form.value.password_confirmation && form.value.password === form.value.password_confirmation
})

const passwordStrength = computed(() => {
  const pw = form.value.password
  let score = 0
  if (pw.length >= 8) score++
  if (/[A-Z]/.test(pw)) score++
  if (/[0-9]/.test(pw)) score++
  if (/[^A-Za-z0-9]/.test(pw)) score++

  const levels = [
    { score: 0, label: '', color: 'bg-dark-600', textColor: 'text-gray-500' },
    { score: 1, label: 'Weak', color: 'bg-red-500', textColor: 'text-red-400' },
    { score: 2, label: 'Fair', color: 'bg-yellow-500', textColor: 'text-yellow-400' },
    { score: 3, label: 'Good', color: 'bg-blue-500', textColor: 'text-blue-400' },
    { score: 4, label: 'Strong', color: 'bg-green-500', textColor: 'text-green-400' },
  ]

  return levels[score] || levels[0]
})

async function handleRegister() {
  error.value = ''
  if (!passwordsMatch.value) {
    error.value = 'Passwords do not match'
    return
  }
  try {
    await authStore.register({ ...form.value, turnstile_token: turnstileToken.value })
    router.push('/')
  } catch (e) {
    error.value = formatAuthRequestMessage(e, 'Registration failed')
  }
}
</script>
