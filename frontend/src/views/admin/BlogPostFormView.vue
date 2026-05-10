<template>
  <div>
    <!-- Breadcrumb -->
    <nav class="flex items-center gap-2 text-sm mb-6">
      <router-link to="/admin/blog" class="text-gray-500 hover:text-brand-violet dark:hover:text-brand-cyan transition-colors">Blog Posts</router-link>
      <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      <span class="text-gray-900 dark:text-white font-medium">{{ isEdit ? 'Edit Post' : 'New Post' }}</span>
    </nav>

    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
      <div>
        <h1 class="text-2xl font-sans font-bold dark:text-white">{{ isEdit ? 'Edit Post' : 'Create Post' }}</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ isEdit ? 'Update article content and settings' : 'Write a new article for your blog' }}</p>
      </div>
    </div>

    <form @submit.prevent="handleSubmit">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Content -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Title & Excerpt -->
          <div class="glass-card space-y-5">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-violet dark:text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Post Details
            </h2>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Title <span class="text-red-400">*</span></label>
              <input v-model="form.title" @input="autoSlug" type="text" required class="input-field" placeholder="Your amazing article title" />
            </div>

            <!-- Slug: WordPress-style Hybrid -->
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Permalink</label>
              <!-- Collapsed: show URL preview with Edit button -->
              <div v-if="!slugEditing" class="flex items-center gap-2 px-3 py-2 bg-gray-50 dark:bg-dark-700/40 border border-gray-200 dark:border-dark-600 rounded-lg">
                <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <span class="text-xs text-gray-500 dark:text-gray-400 truncate">kalapak-team.space/blog/<span class="text-brand-violet dark:text-brand-cyan font-medium">{{ form.slug || slugify(form.title || 'post-title') }}</span></span>
                <button type="button" @click="slugEditing = true" class="ml-auto shrink-0 text-[10px] font-semibold text-brand-violet dark:text-brand-cyan hover:underline">Edit</button>
              </div>
              <!-- Expanded: editable slug input -->
              <div v-else class="space-y-2">
                <div class="flex items-center">
                  <span class="inline-flex items-center px-3 h-[38px] text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-dark-600 border border-r-0 border-gray-200 dark:border-dark-500 rounded-l-lg">/blog/</span>
                  <input v-model="form.slug" @input="slugManuallyEdited = true" ref="slugInput" type="text" required class="input-field !rounded-l-none" placeholder="your-post-slug" />
                </div>
                <div class="flex items-center gap-2">
                  <button type="button" @click="slugEditing = false" class="text-[10px] font-semibold text-brand-violet dark:text-brand-cyan hover:underline">Done</button>
                  <span class="text-gray-300 dark:text-dark-500">|</span>
                  <button type="button" @click="resetSlug" class="text-[10px] font-semibold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:underline">Reset from title</button>
                  <span v-if="slugManuallyEdited" class="ml-auto text-[10px] text-amber-500 dark:text-amber-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    Manually edited
                  </span>
                </div>
              </div>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">
                Excerpt <span class="text-red-400">*</span>
                <span class="text-gray-400 font-normal">(shown in listings)</span>
              </label>
              <textarea v-model="form.excerpt" required rows="2" class="input-field" placeholder="A brief summary that hooks the reader..." />
            </div>
          </div>

          <!-- Content Editor -->
          <div class="glass-card space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-violet dark:text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Content <span class="text-red-400">*</span>
            </h2>
            <ContentEditor v-if="editorReady" v-model="form.content" />
            <div v-else class="flex items-center justify-center h-48 text-gray-400">
              <svg class="w-5 h-5 animate-spin mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
              Loading editor...
            </div>
          </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-6">
          <!-- Cover Image -->
          <div class="glass-card space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-violet dark:text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              Cover Image
            </h2>
            <!-- Storage Provider Selector -->
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Upload to</label>
              <!-- Both allowed: show toggle -->
              <div v-if="allowedProviders === 'both'" class="flex gap-2">
                <button type="button" @click="form.storage_provider = 'supabase'"
                  :class="form.storage_provider === 'supabase'
                    ? 'bg-emerald-50 text-emerald-700 border-emerald-300 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/30 ring-1 ring-emerald-200 dark:ring-emerald-500/20'
                    : 'bg-white dark:bg-dark-700 text-gray-500 border-gray-200 dark:border-dark-600 hover:border-gray-300'"
                  class="flex-1 px-3 py-2 rounded-lg border text-xs font-medium transition-all text-center flex items-center justify-center gap-1.5">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 109 113" fill="currentColor"><path d="M63.7 110.3c-2.6 3.1-7.8 3.1-10.4 0L2.5 49.2c-3.5-4.2-.3-10.4 5.2-10.4h100.6c5.5 0 8.7 6.2 5.2 10.4l-49.8 61.1z"/></svg>
                  Supabase
                </button>
                <button type="button" @click="form.storage_provider = 'cloudinary'"
                  :class="form.storage_provider === 'cloudinary'
                    ? 'bg-blue-50 text-blue-700 border-blue-300 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/30 ring-1 ring-blue-200 dark:ring-blue-500/20'
                    : 'bg-white dark:bg-dark-700 text-gray-500 border-gray-200 dark:border-dark-600 hover:border-gray-300'"
                  class="flex-1 px-3 py-2 rounded-lg border text-xs font-medium transition-all text-center flex items-center justify-center gap-1.5">
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                  Cloudinary
                </button>
              </div>
              <!-- Single provider locked -->
              <div v-else class="flex items-center gap-2 px-3 py-2 rounded-lg border bg-gray-50 dark:bg-dark-700 border-gray-200 dark:border-dark-600 text-xs text-gray-500 dark:text-gray-400">
                <svg v-if="allowedProviders === 'supabase'" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 109 113" fill="currentColor"><path d="M63.7 110.3c-2.6 3.1-7.8 3.1-10.4 0L2.5 49.2c-3.5-4.2-.3-10.4 5.2-10.4h100.6c5.5 0 8.7 6.2 5.2 10.4l-49.8 61.1z"/></svg>
                <svg v-else class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                {{ allowedProviders === 'supabase' ? 'Supabase' : 'Cloudinary' }}
                <span class="ml-auto text-[10px] text-gray-400">Set by Super Admin</span>
              </div>
            </div>
            <div class="relative">
              <div v-if="imagePreview || existingImage" class="relative rounded-xl overflow-hidden group">
                <img :src="imagePreview || existingImage" alt="Cover preview" class="w-full h-40 object-cover" />
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                  <button type="button" @click="removeImage" class="px-3 py-1.5 bg-white/90 text-gray-800 text-xs rounded-lg font-medium hover:bg-white transition-colors">Remove</button>
                </div>
              </div>
              <label v-else class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 dark:border-dark-500 rounded-xl cursor-pointer hover:border-brand-violet dark:hover:border-brand-cyan transition-colors bg-gray-50/50 dark:bg-dark-700/30">
                <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                <span class="text-xs text-gray-500">Click to upload</span>
                <span class="text-[10px] text-gray-400 mt-1">PNG, JPG up to 2MB</span>
                <input type="file" accept="image/*" @change="onFileChange" class="hidden" />
              </label>
            </div>
          </div>

          <!-- Publish Settings -->
          <div class="glass-card space-y-4">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-violet dark:text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              Publish Settings
            </h2>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Status</label>
              <div class="flex gap-2">
                <button type="button" @click="form.status = 'draft'" :class="form.status === 'draft' ? 'bg-yellow-50 text-yellow-700 border-yellow-300 dark:bg-yellow-500/10 dark:text-yellow-400 dark:border-yellow-500/30' : 'bg-white dark:bg-dark-700 text-gray-500 border-gray-200 dark:border-dark-600 hover:border-gray-300'" class="flex-1 px-3 py-2 rounded-lg border text-xs font-medium transition-all text-center">
                  Draft
                </button>
                <button type="button" @click="form.status = 'published'" :class="form.status === 'published' ? 'bg-green-50 text-green-700 border-green-300 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/30' : 'bg-white dark:bg-dark-700 text-gray-500 border-gray-200 dark:border-dark-600 hover:border-gray-300'" class="flex-1 px-3 py-2 rounded-lg border text-xs font-medium transition-all text-center">
                  Published
                </button>
              </div>
            </div>
            <div>
              <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1.5">Category</label>
              <CustomSelect
                v-model="form.category_id"
                :options="[{ label: 'Uncategorized', value: '' }, ...categories.map(c => ({ label: c.name, value: c.id }))]"
                placeholder="Uncategorized"
              />
            </div>
            <div class="flex items-center justify-between py-2">
              <div>
                <p class="text-sm font-medium dark:text-gray-200">Featured</p>
                <p class="text-[10px] text-gray-500 dark:text-gray-400">Pin to top of blog</p>
              </div>
              <button type="button" @click="form.is_featured = !form.is_featured" :class="form.is_featured ? 'bg-brand-violet dark:bg-brand-cyan' : 'bg-gray-300 dark:bg-dark-600'" class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors">
                <span :class="form.is_featured ? 'translate-x-5' : 'translate-x-1'" class="inline-block h-3 w-3 rounded-full bg-white transition-transform" />
              </button>
            </div>
          </div>

          <!-- SEO Preview -->
          <div class="glass-card space-y-3">
            <h2 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-violet dark:text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              SEO Preview
            </h2>
            <div class="bg-white dark:bg-dark-700 rounded-lg p-3 border border-gray-200 dark:border-dark-600">
              <p class="text-sm text-blue-600 dark:text-blue-400 font-medium truncate">{{ form.title || 'Post title' }}</p>
              <p class="text-[10px] text-green-600 dark:text-green-500 truncate mt-0.5">kalapak-team.space/blog/{{ form.slug || slugify(form.title || 'post-title') }}</p>
              <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ form.excerpt || 'Add an excerpt to see the preview here...' }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="glass-card space-y-3">
            <button type="submit" :disabled="saving" class="btn-primary w-full flex items-center justify-center gap-2 text-sm">
              <svg v-if="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              {{ saving ? 'Saving...' : (isEdit ? 'Update Post' : (form.status === 'published' ? 'Publish Post' : 'Save Draft')) }}
            </button>
            <router-link to="/admin/blog" class="btn-ghost w-full flex items-center justify-center gap-2 text-sm">
              Cancel
            </router-link>
            <p v-if="error" class="text-sm text-red-500 text-center">{{ error }}</p>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch, nextTick } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { adminApi } from '@/services/api'
import { useUiStore } from '@/stores/ui'
import { useAuthStore } from '@/stores/auth'
import ContentEditor from '@/components/common/ContentEditor.vue'
import CustomSelect from '@/components/common/CustomSelect.vue'

const route = useRoute()
const router = useRouter()
const uiStore = useUiStore()
const authStore = useAuthStore()

const isEdit = computed(() => !!route.params.id)
const form = ref({ title: '', slug: '', excerpt: '', content: '', category_id: '', status: 'draft', is_featured: false, storage_provider: localStorage.getItem('media_storage_provider') || 'supabase' })
watch(() => form.value.storage_provider, (v) => localStorage.setItem('media_storage_provider', v))
const categories = ref([])
const coverImage = ref(null)
const imagePreview = ref(null)
const existingImage = ref(null)
const saving = ref(false)
const error = ref('')
const editorReady = ref(false)
const allowedProviders = ref('both')

function slugify(text) {
  return text.toLowerCase().replace(/[^\w\s-]/g, '').replace(/[\s_]+/g, '-').replace(/^-+|-+$/g, '') || 'post'
}

const slugManuallyEdited = ref(false)
const slugEditing = ref(false)
const slugInput = ref(null)

function autoSlug() {
  if (!isEdit.value && !slugManuallyEdited.value) {
    form.value.slug = slugify(form.value.title)
  }
}

function resetSlug() {
  form.value.slug = slugify(form.value.title)
  slugManuallyEdited.value = false
}

watch(slugEditing, (val) => {
  if (val) nextTick(() => slugInput.value?.focus())
})

function onFileChange(e) {
  const file = e.target.files[0]
  if (file) {
    coverImage.value = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

function removeImage() {
  coverImage.value = null
  imagePreview.value = null
  existingImage.value = null
}

function formatSaveError(err) {
  const d = err.response?.data
  if (d?.errors && typeof d.errors === 'object') {
    const messages = []
    for (const key of Object.keys(d.errors)) {
      const vals = d.errors[key]
      if (Array.isArray(vals)) messages.push(...vals)
      else if (vals != null && vals !== '') messages.push(String(vals))
    }
    if (messages.length) return messages[0]
  }
  return d?.message || 'Failed to save'
}

onMounted(async () => {
  const neededAction = isEdit.value ? 'update' : 'create'
  if (!authStore.canDo('blog_posts', neededAction)) {
    uiStore.showToast(`You don't have permission to ${neededAction} blog posts`, 'error')
    router.replace({ name: 'admin-blog' })
    return
  }
  try {
    const { data } = await adminApi.getBlogCategories()
    categories.value = data.data || []
    if (!isEdit.value && categories.value.length && (form.value.category_id === '' || form.value.category_id === null)) {
      form.value.category_id = categories.value[0].id
    }
  } catch { /* categories empty until seeded */ }

  try {
    const { data } = await adminApi.getStorageSettings()
    const allowed = data?.data?.allowed_storage_providers || 'both'
    allowedProviders.value = allowed
    if (allowed !== 'both') {
      form.value.storage_provider = allowed
    }
  } catch { /* ignore */ }
  if (isEdit.value) {
    try {
      const { data } = await adminApi.getBlogPost(route.params.id)
      const p = data.data
      form.value = {
        title: p.title, slug: p.slug || '', excerpt: p.excerpt || '', content: p.content || '',
        category_id: p.category?.id || '', status: p.status, is_featured: p.is_featured,
        storage_provider: p.storage_provider || 'supabase',
      }
      if (p.cover_image) existingImage.value = p.cover_image
    } catch {}
  }
  editorReady.value = true
})

async function handleSubmit() {
  saving.value = true; error.value = ''
  if (!form.value.slug) form.value.slug = slugify(form.value.title)
  try {
    const fd = new FormData()
    Object.entries(form.value).forEach(([k, v]) => {
      if (v !== '' && v !== null && v !== undefined) {
        fd.append(k, typeof v === 'boolean' ? (v ? '1' : '0') : v)
      }
    })
    if (coverImage.value) fd.append('cover_image', coverImage.value)
    if (isEdit.value) {
      fd.append('_method', 'PUT')
      await adminApi.updateBlogPost(route.params.id, fd)
    } else {
      await adminApi.createBlogPost(fd)
    }
    uiStore.showToast(`Post ${isEdit.value ? 'updated' : 'created'}!`)
    router.push({ name: 'admin-blog' })
  } catch (e) {
    if (e.response?.data?.intercepted) {
      uiStore.showToast(e.response.data.message || 'Queued for approval', 'warning')
      router.push({ name: 'admin-blog' })
    } else {
      error.value = formatSaveError(e)
    }
  } finally { saving.value = false }
}
</script>
