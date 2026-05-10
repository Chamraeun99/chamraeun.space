import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { memberApi } from '@/services/api'

export const useNotificationStore = defineStore('notifications', () => {
  const notifications = ref([])
  const unreadCount = ref(0)
  const loading = ref(false)
  const meta = ref({ current_page: 1, last_page: 1, total: 0 })

  let pollInterval = null
  /** How many layouts/components subscribed to polling (Navbar + AdminLayout both call startPolling). */
  let pollConsumers = 0

  async function fetchNotifications(page = 1) {
    loading.value = true
    try {
      const { data } = await memberApi.getNotifications({ page })
      notifications.value = data.data
      unreadCount.value = data.unread_count
      meta.value = data.meta
    } catch {
      // silently fail
    } finally {
      loading.value = false
    }
  }

  async function fetchUnreadCount() {
    try {
      const { data } = await memberApi.getUnreadCount()
      unreadCount.value = data.unread_count
    } catch {
      // silently fail
    }
  }

  async function markAsRead(id) {
    try {
      await memberApi.markNotificationRead(id)
      const n = notifications.value.find((n) => n.id === id)
      if (n) n.read_at = new Date().toISOString()
      if (unreadCount.value > 0) unreadCount.value--
    } catch {
      // silently fail
    }
  }

  async function markAllRead() {
    try {
      await memberApi.markAllNotificationsRead()
      notifications.value.forEach((n) => {
        if (!n.read_at) n.read_at = new Date().toISOString()
      })
      unreadCount.value = 0
    } catch {
      // silently fail
    }
  }

  async function deleteNotification(id) {
    try {
      await memberApi.deleteNotification(id)
      const n = notifications.value.find((n) => n.id === id)
      if (n && !n.read_at && unreadCount.value > 0) unreadCount.value--
      notifications.value = notifications.value.filter((n) => n.id !== id)
    } catch {
      // silently fail
    }
  }

  function startPolling() {
    pollConsumers++
    if (pollConsumers === 1) {
      fetchUnreadCount()
      pollInterval = setInterval(fetchUnreadCount, 60000)
    }
  }

  function stopPolling() {
    pollConsumers = Math.max(0, pollConsumers - 1)
    if (pollConsumers === 0 && pollInterval) {
      clearInterval(pollInterval)
      pollInterval = null
    }
  }

  function $reset() {
    pollConsumers = 0
    if (pollInterval) {
      clearInterval(pollInterval)
      pollInterval = null
    }
    notifications.value = []
    unreadCount.value = 0
    meta.value = { current_page: 1, last_page: 1, total: 0 }
  }

  return {
    notifications,
    unreadCount,
    loading,
    meta,
    fetchNotifications,
    fetchUnreadCount,
    markAsRead,
    markAllRead,
    deleteNotification,
    startPolling,
    stopPolling,
    $reset,
  }
})
