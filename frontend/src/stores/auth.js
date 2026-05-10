import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'
import { authApi, memberApi, adminApi } from '@/services/api'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const token = ref(import.meta.client ? localStorage.getItem('auth_token') : null)
  const loading = ref(false)

  // Per-resource permissions (fetched for admin users)
  const permissions = ref({
    projects: { can_create: false, can_update: false, can_delete: false },
    categories: { can_create: false, can_update: false, can_delete: false },
    tags: { can_create: false, can_update: false, can_delete: false },
    team_members: { can_create: false, can_update: false, can_delete: false },
    blog_posts: { can_create: false, can_update: false, can_delete: false },
    media: { can_create: false, can_update: false, can_delete: false },
  })

  const isAuthenticated = computed(() => !!token.value)
  const isSuperAdmin = computed(() => user.value?.role?.name === 'superadmin')
  const isAdmin = computed(() => ['admin', 'superadmin'].includes(user.value?.role?.name))
  const isMember = computed(() => ['member', 'admin', 'superadmin'].includes(user.value?.role?.name))

  function canDo(resource, action) {
    if (isSuperAdmin.value) return true
    return permissions.value[resource]?.['can_' + action] ?? false
  }

  async function fetchPermissions() {
    if (!token.value || !isAdmin.value) return
    try {
      const { data } = await adminApi.getMyPermissions()
      permissions.value = data.data
    } catch {
      // ignore – permissions stay at defaults (all false)
    }
  }

  async function login(credentials) {
    loading.value = true
    try {
      const { data } = await authApi.login(credentials)
      token.value = data.data.token
      user.value = data.data.user
      if (import.meta.client) {
        localStorage.setItem('auth_token', data.data.token)
      }
      return data
    } finally {
      loading.value = false
    }
  }

  async function register(formData) {
    loading.value = true
    try {
      const { data } = await authApi.register(formData)
      token.value = data.data.token
      user.value = data.data.user
      if (import.meta.client) {
        localStorage.setItem('auth_token', data.data.token)
      }
      return data
    } finally {
      loading.value = false
    }
  }

  /**
   * @param {{ oauthDirectBackendBase?: string }} [opts]
   * After OAuth, call with `oauthDirectBackendBase` from `useRuntimeConfig().public.oauthApiBase` so `/auth/me`
   * hits Laravel directly. The Nitro `/api` proxy can omit `Authorization`, which caused `?error=github_session`.
   */
  async function fetchMe(opts = {}) {
    if (!token.value) return
    loading.value = true
    try {
      const direct = typeof opts.oauthDirectBackendBase === 'string'
        ? opts.oauthDirectBackendBase.replace(/\/$/, '')
        : ''
      let userPayload
      if (import.meta.client && direct.startsWith('http')) {
        const { data } = await axios.get(`${direct}/auth/me`, {
          headers: {
            Authorization: `Bearer ${token.value}`,
            Accept: 'application/json',
          },
          withCredentials: false,
        })
        userPayload = data.data
      } else {
        const { data } = await authApi.me()
        userPayload = data.data
      }
      user.value = userPayload
      await fetchPermissions()
    } catch {
      logout()
    } finally {
      loading.value = false
    }
  }

  async function updateProfile(formData) {
    const { data } = await memberApi.updateProfile(formData)
    user.value = data.data
    return data
  }

  async function updatePassword(formData) {
    return await memberApi.updatePassword(formData)
  }

  async function uploadAvatar(formData) {
    const { data } = await memberApi.uploadAvatar(formData)
    user.value = data.data
    return data
  }

  async function logout() {
    if (token.value) {
      try { await authApi.logout() } catch { /* ignore */ }
    }
    user.value = null
    token.value = null
    if (import.meta.client) {
      localStorage.removeItem('auth_token')
    }
  }

  return {
    user,
    token,
    loading,
    permissions,
    isAuthenticated,
    isSuperAdmin,
    isAdmin,
    isMember,
    canDo,
    fetchPermissions,
    login,
    register,
    fetchMe,
    updateProfile,
    updatePassword,
    uploadAvatar,
    logout,
  }
})
