<template>
  <div>
    <h2 class="text-2xl font-sans font-bold gradient-text mb-2 text-center">Forgot Password</h2>
    <p class="text-sm text-gray-500 dark:text-gray-400 text-center mb-6">Enter your email to receive a reset link.</p>

    <div v-if="sent" class="text-center py-4">
      <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
      </div>
      <p class="text-sm text-gray-600 dark:text-gray-400">Check your email for the reset link.</p>
    </div>

    <form v-else @submit.prevent="handleSubmit" class="space-y-5">
      <div>
        <label class="block text-sm font-medium dark:text-gray-300 mb-1">Email</label>
        <input v-model="email" type="email" required class="input-field" placeholder="your@email.com" />
      </div>
      <div class="flex justify-center">
        <VueTurnstile :site-key="turnstileSiteKey" v-model="turnstileToken" theme="dark" />
      </div>
      <button type="submit" :disabled="loading" class="btn-primary w-full">
        <span v-if="loading">Sending...</span>
        <span v-else>Send Reset Link</span>
      </button>
      <p v-if="!turnstileToken" class="text-xs text-center text-gray-400">Waiting for CAPTCHA verification…</p>
      <p v-if="error" class="text-sm text-red-500 text-center">{{ error }}</p>
    </form>

    <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
      <router-link :to="{ name: 'login' }" class="text-brand-violet dark:text-brand-cyan hover:underline">
        Back to login
      </router-link>
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { authApi } from '@/services/api'
import VueTurnstile from 'vue-turnstile'

const email = ref('')
const loading = ref(false)
const sent = ref(false)
const error = ref('')
const turnstileSiteKey = useTurnstileSiteKey()
const turnstileToken = ref('')

async function handleSubmit() {
  loading.value = true
  error.value = ''
  try {
    await authApi.forgotPassword({ email: email.value, turnstile_token: turnstileToken.value })
    sent.value = true
  } catch (e) {
    error.value = e.response?.data?.message || 'Failed to send reset link'
    // Reset the CAPTCHA so the user can re-verify
    turnstileToken.value = ''
  } finally {
    loading.value = false
  }
}
</script>
