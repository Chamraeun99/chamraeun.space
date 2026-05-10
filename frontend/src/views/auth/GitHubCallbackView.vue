<template>
  <div class="min-h-screen flex items-center justify-center bg-dark-900">
    <div class="text-center">
      <div class="w-14 h-14 mx-auto mb-4 border-[3px] border-gray-200 dark:border-dark-600 border-t-brand-violet dark:border-t-brand-cyan rounded-full animate-spin" />
      <p class="text-gray-400">{{ message }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const message = ref('Signing you in...')

function queryString(val) {
  if (val == null) return ''
  const s = Array.isArray(val) ? val[0] : val
  return typeof s === 'string' ? s : ''
}

onMounted(async () => {
  const token = queryString(route.query.token)
  const err = queryString(route.query.error)

  if (err) {
    message.value = 'GitHub sign-in failed. Redirecting...'
    setTimeout(() => router.push({ name: 'login', query: { error: 'github_failed' } }), 2000)
    return
  }

  if (token) {
    localStorage.setItem('auth_token', token)
    authStore.token = token
    await authStore.fetchMe()
    if (!authStore.user) {
      message.value = 'Could not complete sign-in. Redirecting...'
      setTimeout(() => router.push({ name: 'login', query: { error: 'github_session' } }), 2000)
      return
    }
    const redirect = authStore.isAdmin ? '/admin' : '/'
    router.push(redirect)
  } else {
    message.value = 'Invalid callback. Redirecting...'
    setTimeout(() => router.push({ name: 'login' }), 2000)
  }
})
</script>
