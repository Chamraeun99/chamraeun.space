import axios from 'axios'
import { useAuthStore } from '@/stores/auth'

function normalizeDevApiUrl(url) {
  if (!url) return url

  // In local dev, avoid hitting Nuxt app routes at :3000/api (404)
  if (
    typeof window !== 'undefined' &&
    (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') &&
    url === '/api'
  ) {
    return 'http://127.0.0.1:8000/api'
  }

  return url
}

function resolveApiBaseURL() {
  // Server runtime (Nuxt SSR on Node/Render)
  if (typeof window === 'undefined') {
    return process.env.NUXT_PUBLIC_API_URL || process.env.VITE_API_URL || 'http://127.0.0.1:8000/api'
  }

  // Client runtime from Nuxt payload config
  const nuxtRuntimeApi = window.__NUXT__?.config?.public?.apiUrl
  if (nuxtRuntimeApi) return normalizeDevApiUrl(nuxtRuntimeApi)

  // Build-time fallback for local/dev compatibility
  return normalizeDevApiUrl(
    import.meta.env.NUXT_PUBLIC_API_URL || import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api'
  )
}

/** Full-page OAuth entry points live under /api/auth/… so proxies that only route /api/* still reach Laravel. */
export function getOAuthRedirectUrl(provider) {
  const base = resolveApiBaseURL().replace(/\/$/, '')
  return `${base}/auth/${provider}/redirect`
}

const api = axios.create({
  baseURL: resolveApiBaseURL(),
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
  },
  withCredentials: true,
})

api.interceptors.request.use((config) => {
  const token = import.meta.client ? localStorage.getItem('auth_token') : null
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

api.interceptors.response.use(
  (response) => {
    // When an admin action is intercepted (queued for approval), treat it as an error
    // so existing catch blocks in views show the right message.
    if (response.status === 202 && response.data?.intercepted) {
      const err = new Error(response.data.message || 'Your action has been queued for super-admin approval.')
      err.response = response
      return Promise.reject(err)
    }
    return response
  },
  (error) => {
    if (error.response?.status === 401) {
      const authStore = useAuthStore()
      authStore.logout()
      if (typeof window !== 'undefined') {
        const path = window.location.pathname || ''
        const onOAuthCallback = /\/auth\/(?:google|github)\/callback$/.test(path)
        if (!onOAuthCallback) {
          window.location.href = '/auth/login'
        }
      }
    }
    if (error.response?.status === 429) {
      const retryAfter = error.response.headers['retry-after'] || 60
      error.message = `Too many requests. Please wait ${retryAfter} seconds and try again.`
    }
    return Promise.reject(error)
  }
)

export const authApi = {
  login: (data) => api.post('/auth/login', data),
  register: (data) => api.post('/auth/register', data),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
  forgotPassword: (data) => api.post('/auth/forgot-password', data),
  resetPassword: (data) => api.post('/auth/reset-password', data),
}

export const publicApi = {
  getProjects: (params) => api.get('/projects', { params }),
  getProject: (slug) => api.get(`/projects/${slug}`),
  getBlogPosts: (params) => api.get('/blog/posts', { params }),
  getBlogPost: (slug) => api.get(`/blog/posts/${slug}`),
  getBlogCategories: () => api.get('/blog/categories'),
  getTeam: () => api.get('/team'),
  getTags: () => api.get('/tags'),
  getSettings: () => api.get('/settings/public'),
  sendContact: (data) => api.post('/contact', data),
  getDocs: () => api.get('/docs'),
  getDoc: (slug) => api.get(`/docs/${slug}`),
  getDocsNav: () => api.get('/docs/nav'),
}

export const memberApi = {
  getProfile: () => api.get('/member/profile'),
  updateProfile: (data) => api.put('/member/profile', data),
  updatePassword: (data) => api.put('/member/password', data),
  getStorageSettings: () => api.get('/member/storage-settings'),
  uploadAvatar: (formData) =>
    api.post('/member/avatar', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  submitApplication: (data) => api.post('/applications', data),

  // Notifications
  getNotifications: (params) => api.get('/member/notifications', { params }),
  getUnreadCount: () => api.get('/member/notifications/unread-count'),
  markNotificationRead: (id) => api.put(`/member/notifications/${id}/read`),
  markAllNotificationsRead: () => api.put('/member/notifications/read-all'),
  deleteNotification: (id) => api.delete(`/member/notifications/${id}`),
}

export const adminApi = {
  getDashboardStats: () => api.get('/admin/dashboard/stats'),
  getDashboardActivity: () => api.get('/admin/dashboard/activity'),

  // Own permissions
  getMyPermissions: () => api.get('/admin/my-permissions'),

  // Storage Stats
  getStorageStats: () => api.get('/admin/storage-stats'),
  refreshStorageStats: () => api.post('/admin/storage-stats/refresh'),

  getUsers: (params) => api.get('/admin/users', { params }),
  getUser: (id) => api.get(`/admin/users/${id}`),
  createUser: (data) => api.post('/admin/users', data),
  updateUser: (id, data) => api.put(`/admin/users/${id}`, data),
  deleteUser: (id) => api.delete(`/admin/users/${id}`),
  toggleUserActive: (id) => api.put(`/admin/users/${id}/toggle-active`),

  getProjects: (params) => api.get('/admin/projects', { params }),
  getProject: (id) => api.get(`/admin/projects/${id}`),
  createProject: (formData) =>
    api.post('/admin/projects', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  updateProject: (id, formData) =>
    api.post(`/admin/projects/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteProject: (id) => api.delete(`/admin/projects/${id}`),

  getBlogPosts: (params) => api.get('/admin/blog/posts', { params }),
  getBlogPost: (id) => api.get(`/admin/blog/posts/${id}`),
  createBlogPost: (data) =>
    api.post('/admin/blog/posts', data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  updateBlogPost: (id, data) =>
    api.post(`/admin/blog/posts/${id}`, data, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteBlogPost: (id) => api.delete(`/admin/blog/posts/${id}`),
  getBlogCategories: () => api.get('/admin/blog/categories'),
  createBlogCategory: (data) => api.post('/admin/blog/categories', data),
  updateBlogCategory: (id, data) => api.put(`/admin/blog/categories/${id}`, data),
  deleteBlogCategory: (id) => api.delete(`/admin/blog/categories/${id}`),

  getApplications: (params) => api.get('/admin/applications', { params }),
  getApplication: (id) => api.get(`/admin/applications/${id}`),
  updateApplicationStatus: (id, data) => api.put(`/admin/applications/${id}/status`, data),

  getMessages: (params) => api.get('/admin/messages', { params }),
  getMessage: (id) => api.get(`/admin/messages/${id}`),
  markMessageRead: (id) => api.put(`/admin/messages/${id}/read`),
  deleteMessage: (id) => api.delete(`/admin/messages/${id}`),

  getTeamMembers: (params) => api.get('/admin/team', { params }),
  createTeamMember: (formData) =>
    api.post('/admin/team', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  updateTeamMember: (id, formData) =>
    api.post(`/admin/team/${id}`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteTeamMember: (id) => api.delete(`/admin/team/${id}`),

  getRoles: () => api.get('/admin/roles'),
  createRole: (data) => api.post('/admin/roles', data),
  updateRole: (id, data) => api.put(`/admin/roles/${id}`, data),
  deleteRole: (id) => api.delete(`/admin/roles/${id}`),

  getMedia: (params) => api.get('/admin/media', { params }),
  uploadMedia: (formData) =>
    api.post('/admin/media/upload', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    }),
  deleteMedia: (id) => api.delete(`/admin/media/${id}`),

  getSettings: () => api.get('/admin/settings'),
  updateSettings: (data) => api.put('/admin/settings', data),
  getStorageSettings: () => api.get('/admin/settings/storage'),

  getApprovalRequests: (params) => api.get('/admin/approval-requests', { params }),
  approveRequest: (id, data) => api.post(`/admin/approval-requests/${id}/approve`, data),
  rejectRequest: (id, data) => api.post(`/admin/approval-requests/${id}/reject`, data),

  getUserPermissions: (userId) => api.get(`/admin/users/${userId}/permissions`),
  updateUserPermissions: (userId, data) => api.put(`/admin/users/${userId}/permissions`, data),

  getActivityLogs: (params) => api.get('/admin/activity-logs', { params }),

  getTags: () => api.get('/admin/tags'),
  createTag: (data) => api.post('/admin/tags', data),
  updateTag: (id, data) => api.put(`/admin/tags/${id}`, data),
  deleteTag: (id) => api.delete(`/admin/tags/${id}`),

  search: (params) => api.get('/admin/search', { params }),

  getDocs: (params) => api.get('/admin/docs', { params }),
  getAllDocs: () => api.get('/admin/docs/all'),
  getDoc: (id) => api.get(`/admin/docs/${id}`),
  createDoc: (data) => api.post('/admin/docs', data),
  updateDoc: (id, data) => api.put(`/admin/docs/${id}`, data),
  deleteDoc: (id) => api.delete(`/admin/docs/${id}`),
  reorderDocs: (data) => api.post('/admin/docs/reorder', data),
  getDocCategories: () => api.get('/admin/docs/categories'),
  createDocCategory: (data) => api.post('/admin/docs/categories', data),
  renameDocCategory: (data) => api.post('/admin/docs/categories/rename', data),
  deleteDocCategory: (data) => api.post('/admin/docs/categories/delete', data),

  getDocMenus: () => api.get('/admin/doc-menus'),
  getDocMenusFlat: () => api.get('/admin/doc-menus/flat'),
  createDocMenu: (data) => api.post('/admin/doc-menus', data),
  updateDocMenu: (id, data) => api.put(`/admin/doc-menus/${id}`, data),
  deleteDocMenu: (id) => api.delete(`/admin/doc-menus/${id}`),
  reorderDocMenus: (data) => api.post('/admin/doc-menus/reorder', data),
}

export default api
