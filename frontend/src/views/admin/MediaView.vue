<template>
  <div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-sans font-bold dark:text-white">Media Library</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage uploaded images and documents</p>
      </div>
      <div class="flex items-center gap-2">
        <button v-if="selectedIds.length && authStore.canDo('media', 'delete')" @click="bulkDelete" class="px-3 py-2 text-xs font-medium rounded-lg bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-500/20 transition-colors flex items-center gap-1.5">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          Delete {{ selectedIds.length }}
        </button>
        <!-- Single provider locked by super admin -->
        <div v-if="allowedProviders !== 'both'" class="flex items-center gap-1.5 px-3 py-2 rounded-lg border bg-gray-50 dark:bg-dark-700 border-gray-200 dark:border-dark-600 text-xs text-gray-500 dark:text-gray-400">
          <svg v-if="allowedProviders === 'supabase'" class="w-3.5 h-3.5 text-emerald-500" viewBox="0 0 109 113" fill="currentColor"><path d="M63.7 110.3c-2.6 3.1-7.8 3.1-10.4 0L2.5 49.2c-3.5-4.2-.3-10.4 5.2-10.4h100.6c5.5 0 8.7 6.2 5.2 10.4l-49.8 61.1z"/></svg>
          <svg v-else class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
          {{ allowedProviders === 'supabase' ? 'Supabase' : 'Cloudinary' }}
          <span class="text-[10px] text-gray-400 ml-1">· Set by Super Admin</span>
        </div>
        <!-- Both allowed: toggle buttons -->
        <div v-else class="flex items-center border border-gray-200 dark:border-dark-600 rounded-lg overflow-hidden">
          <button type="button" @click="storageProvider = 'supabase'"
            :class="storageProvider === 'supabase' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700'"
            class="px-3 py-2 flex items-center gap-1.5 transition-colors text-xs font-medium">
            <svg class="w-3.5 h-3.5" viewBox="0 0 109 113" fill="currentColor"><path d="M63.7 110.3c-2.6 3.1-7.8 3.1-10.4 0L2.5 49.2c-3.5-4.2-.3-10.4 5.2-10.4h100.6c5.5 0 8.7 6.2 5.2 10.4l-49.8 61.1z"/></svg>
            Supabase
          </button>
          <button type="button" @click="storageProvider = 'cloudinary'"
            :class="storageProvider === 'cloudinary' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-dark-700'"
            class="px-3 py-2 flex items-center gap-1.5 transition-colors text-xs font-medium border-l border-gray-200 dark:border-dark-600">
            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
            Cloudinary
          </button>
        </div>
        <label v-if="authStore.canDo('media', 'create')" class="btn-primary cursor-pointer flex items-center gap-2 text-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
          Upload Files
          <input type="file" accept="image/*,.pdf" multiple class="hidden" @change="uploadFiles" />
        </label>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-brand-violet/10 dark:bg-brand-violet/20 flex items-center justify-center">
          <svg class="w-5 h-5 text-brand-violet" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Total Files</p>
          <p class="text-lg font-bold dark:text-white">{{ meta.total || media.length }}</p>
        </div>
      </div>
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Images</p>
          <p class="text-lg font-bold text-blue-500">{{ imageCount }}</p>
        </div>
      </div>
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Documents</p>
          <p class="text-lg font-bold text-amber-500">{{ docCount }}</p>
        </div>
      </div>
      <div class="glass-card !p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
          <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
        </div>
        <div>
          <p class="text-xs text-gray-500 dark:text-gray-400">Storage</p>
          <p class="text-lg font-bold text-green-500">{{ totalSize }}</p>
        </div>
      </div>
    </div>

    <!-- Upload Progress -->
    <div v-if="uploading" class="glass-card !p-4 mb-6">
      <div class="flex items-center gap-3 mb-2">
        <div class="w-5 h-5 border-2 border-brand-violet/30 border-t-brand-violet rounded-full animate-spin" />
        <p class="text-sm font-medium dark:text-white">Uploading {{ uploadProgress.current }} of {{ uploadProgress.total }}...</p>
      </div>
      <div class="w-full bg-gray-200 dark:bg-dark-600 rounded-full h-2">
        <div class="bg-brand-violet h-2 rounded-full transition-all duration-300" :style="{ width: (uploadProgress.current / uploadProgress.total * 100) + '%' }"></div>
      </div>
    </div>

    <!-- Drag & Drop Zone -->
    <div v-if="!media.length && !loading"
      class="glass-card mb-6 border-2 border-dashed border-gray-300 dark:border-dark-500 hover:border-brand-violet dark:hover:border-brand-cyan transition-colors cursor-pointer"
      :class="{ 'border-brand-violet dark:border-brand-cyan bg-brand-violet/5 dark:bg-brand-cyan/5': isDragging }"
      @dragover.prevent="isDragging = true" @dragleave="isDragging = false"
      @drop.prevent="handleDrop" @click="$refs.dropInput.click()">
      <div class="flex flex-col items-center justify-center py-16">
        <div class="w-16 h-16 rounded-2xl bg-brand-violet/10 dark:bg-brand-cyan/10 flex items-center justify-center mb-4">
          <svg class="w-8 h-8 text-brand-violet dark:text-brand-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
        </div>
        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Drop files here or click to browse</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400">Supports JPG, PNG, WebP, GIF, PDF — Max 10MB</p>
      </div>
      <input ref="dropInput" type="file" accept="image/*,.pdf" multiple class="hidden" @change="uploadFiles" />
    </div>

    <!-- Search & Filter Bar -->
    <div v-if="media.length || search || typeFilter" class="glass-card !p-4 mb-6">
      <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
        <div class="relative flex-1 w-full">
          <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <input v-model="search" type="text" placeholder="Search files by name..." class="input-field !pl-10 w-full" @input="debouncedFetch" />
        </div>
        <CustomSelect
          v-model="typeFilter"
          :options="[{ label: 'All Types', value: '' }, { label: 'Images', value: 'image' }, { label: 'Documents', value: 'document' }]"
          placeholder="All Types"
          size="md"
          class="w-full md:w-40"
          @change="fetchMedia()"
        />
        <div class="flex items-center border border-gray-200 dark:border-dark-600 rounded-lg overflow-hidden">
          <button @click="viewMode = 'grid'" class="p-2 transition-colors" :class="viewMode === 'grid' ? 'bg-brand-violet/10 text-brand-violet dark:bg-brand-cyan/10 dark:text-brand-cyan' : 'text-gray-400 hover:text-gray-600'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
          </button>
          <button @click="viewMode = 'list'" class="p-2 transition-colors" :class="viewMode === 'list' ? 'bg-brand-violet/10 text-brand-violet dark:bg-brand-cyan/10 dark:text-brand-cyan' : 'text-gray-400 hover:text-gray-600'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-20">
      <div class="w-10 h-10 border-4 border-brand-violet/30 border-t-brand-violet rounded-full animate-spin" />
    </div>

    <!-- Grid View -->
    <div v-else-if="viewMode === 'grid' && media.length" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
      <div v-for="item in media" :key="item.id"
        class="glass-card !p-0 group relative overflow-hidden rounded-xl cursor-pointer transition-all hover:shadow-lg hover:scale-[1.02]"
        :class="{ 'ring-2 ring-brand-violet dark:ring-brand-cyan': selectedIds.includes(item.id) }"
        @click="openPreview(item)">
        <!-- Select Checkbox -->
        <div class="absolute top-2 left-2 z-10">
          <input type="checkbox" :checked="selectedIds.includes(item.id)" @click.stop @change="toggleSelect(item.id)"
            class="w-4 h-4 rounded border-gray-300 text-brand-violet focus:ring-brand-violet dark:border-dark-500 opacity-0 group-hover:opacity-100 transition-opacity"
            :class="{ '!opacity-100': selectedIds.includes(item.id) }" />
        </div>
        <!-- Thumbnail -->
        <div class="aspect-square bg-gray-100 dark:bg-dark-700 overflow-hidden">
          <img v-if="isImage(item)" :src="item.url" :alt="item.original_name" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-300" loading="lazy" />
          <div v-else class="w-full h-full flex flex-col items-center justify-center">
            <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="text-[10px] font-mono uppercase text-gray-400">{{ getExt(item) }}</span>
          </div>
        </div>
        <!-- File Info -->
        <div class="p-2.5">
          <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate">{{ item.original_name || item.filename }}</p>
          <div class="flex items-center justify-between mt-1">
            <span class="text-[10px] text-gray-400">{{ formatSize(item.size) }}</span>
            <span class="text-[10px] text-gray-400">{{ timeAgo(item.created_at) }}</span>
          </div>
        </div>
        <!-- Hover Actions -->
        <div class="absolute top-2 right-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
          <button @click.stop="copyUrl(item)" class="w-7 h-7 rounded-lg bg-black/50 backdrop-blur-sm text-white flex items-center justify-center hover:bg-black/70 transition-colors" title="Copy URL">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
          </button>
          <button v-if="authStore.canDo('media', 'delete')" @click.stop="deleteTarget = item" class="w-7 h-7 rounded-lg bg-red-500/80 backdrop-blur-sm text-white flex items-center justify-center hover:bg-red-600 transition-colors" title="Delete">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
          </button>
        </div>
      </div>
    </div>

    <!-- List View -->
    <div v-else-if="viewMode === 'list' && media.length" class="glass-card overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-200 dark:border-dark-600">
              <th class="px-4 py-3 text-left w-10">
                <input type="checkbox" :checked="allSelected" @change="toggleSelectAll" class="w-4 h-4 rounded border-gray-300 text-brand-violet focus:ring-brand-violet dark:border-dark-500" />
              </th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">File</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Size</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uploaded By</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
              <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 dark:divide-dark-700">
            <tr v-for="item in media" :key="item.id" class="hover:bg-gray-50/50 dark:hover:bg-dark-700/30 transition-colors cursor-pointer" @click="openPreview(item)">
              <td class="px-4 py-3" @click.stop>
                <input type="checkbox" :checked="selectedIds.includes(item.id)" @change="toggleSelect(item.id)" class="w-4 h-4 rounded border-gray-300 text-brand-violet focus:ring-brand-violet dark:border-dark-500" />
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-100 dark:bg-dark-600 flex-shrink-0">
                    <img v-if="isImage(item)" :src="item.url" :alt="item.original_name" class="w-full h-full object-cover" loading="lazy" />
                    <div v-else class="w-full h-full flex items-center justify-center">
                      <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                  </div>
                  <p class="text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ item.original_name || item.filename }}</p>
                </div>
              </td>
              <td class="px-4 py-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium" :class="isImage(item) ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'">
                  {{ getExt(item).toUpperCase() }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ formatSize(item.size) }}</td>
              <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ item.uploader?.name || '—' }}</td>
              <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ formatDate(item.created_at) }}</td>
              <td class="px-4 py-3 text-right" @click.stop>
                <div class="flex items-center justify-end gap-1">
                  <button @click="copyUrl(item)" class="p-1.5 rounded-lg text-gray-400 hover:text-brand-violet dark:hover:text-brand-cyan hover:bg-brand-violet/10 dark:hover:bg-brand-cyan/10 transition-colors" title="Copy URL">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                  </button>
                  <button v-if="authStore.canDo('media', 'delete')" @click="deleteTarget = item" class="p-1.5 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-500/10 transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Empty State (with data but filtered to nothing) -->
    <div v-else-if="!loading && !media.length && (search || typeFilter)" class="glass-card flex flex-col items-center justify-center py-16">
      <div class="w-16 h-16 rounded-2xl bg-gray-100 dark:bg-dark-700 flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
      </div>
      <h3 class="font-semibold text-gray-900 dark:text-white mb-1">No files found</h3>
      <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter.</p>
      <button @click="search = ''; typeFilter = ''; fetchMedia()" class="mt-4 text-sm text-brand-violet dark:text-brand-cyan hover:underline">Clear filters</button>
    </div>

    <!-- Pagination -->
    <div v-if="!loading && meta.last_page > 1" class="mt-6">
      <Pagination :current-page="meta.current_page" :last-page="meta.last_page" @page-change="fetchMedia" />
    </div>

    <!-- Preview Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="preview" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm" @click.self="preview = null">
          <Transition enter-active-class="transition-all duration-200" leave-active-class="transition-all duration-200" enter-from-class="opacity-0 scale-95" leave-to-class="opacity-0 scale-95">
            <div v-if="preview" class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden border border-gray-200 dark:border-dark-600 flex flex-col">
              <!-- Modal Header -->
              <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-dark-600">
                <div class="min-w-0 flex-1 mr-4">
                  <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ preview.original_name || preview.filename }}</h3>
                  <div class="flex items-center gap-3 mt-1 flex-wrap">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium" :class="isImage(preview) ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'">
                      {{ preview.mime_type }}
                    </span>
                    <span class="text-xs text-gray-400">{{ formatSize(preview.size) }}</span>
                    <span class="text-xs text-gray-400">{{ formatDate(preview.created_at) }}</span>
                    <span v-if="preview.uploader" class="text-xs text-gray-400">by {{ preview.uploader.name }}</span>
                  </div>
                </div>
                <div class="flex items-center gap-1 flex-shrink-0">
                  <button @click="copyUrl(preview)" class="p-2 rounded-lg text-gray-400 hover:text-brand-violet dark:hover:text-brand-cyan hover:bg-brand-violet/10 dark:hover:bg-brand-cyan/10 transition-colors" title="Copy URL">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                  </button>
                  <a :href="preview.url" target="_blank" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:bg-dark-700 transition-colors" title="Open original">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                  <button v-if="authStore.canDo('media', 'delete')" @click="deleteTarget = preview; preview = null" class="p-2 rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-500/10 transition-colors" title="Delete">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                  <button @click="preview = null" class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 dark:hover:text-gray-300 dark:hover:bg-dark-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                  </button>
                </div>
              </div>
              <!-- Preview Content -->
              <div class="flex-1 overflow-auto p-4 flex items-center justify-center bg-gray-50 dark:bg-dark-900 min-h-[300px]">
                <img v-if="isImage(preview)" :src="preview.url" :alt="preview.original_name" class="max-w-full max-h-[65vh] object-contain rounded-lg shadow-lg" />
                <div v-else class="flex flex-col items-center text-center py-12">
                  <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">Preview not available for this file type</p>
                  <a :href="preview.url" target="_blank" class="btn-primary text-sm">Download File</a>
                </div>
              </div>
            </div>
          </Transition>
        </div>
      </Transition>
    </Teleport>

    <!-- Delete Confirm Modal -->
    <Teleport to="body">
      <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
        <div v-if="deleteTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="deleteTarget = null">
          <div class="bg-white dark:bg-dark-800 rounded-2xl shadow-2xl max-w-sm w-full p-6 border border-gray-200 dark:border-dark-600">
            <div class="flex items-center gap-3 mb-4">
              <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </div>
              <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Delete File</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">This action cannot be undone.</p>
              </div>
            </div>
            <div class="flex items-center gap-3 mb-5 p-3 rounded-lg bg-gray-50 dark:bg-dark-700">
              <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-200 dark:bg-dark-600 flex-shrink-0">
                <img v-if="isImage(deleteTarget)" :src="deleteTarget.url" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full flex items-center justify-center">
                  <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
              </div>
              <div class="min-w-0">
                <p class="text-sm font-semibold dark:text-white truncate">{{ deleteTarget.original_name || deleteTarget.filename }}</p>
                <p class="text-xs text-gray-400">{{ formatSize(deleteTarget.size) }}</p>
              </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
              <button @click="deleteTarget = null" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 dark:bg-dark-600 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-dark-500 transition-colors">Cancel</button>
              <button @click="confirmDelete" class="px-4 py-2 text-sm font-medium rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors">Delete</button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { adminApi } from '@/services/api'
import { useUiStore } from '@/stores/ui'
import { useAuthStore } from '@/stores/auth'
import Pagination from '@/components/common/Pagination.vue'
import CustomSelect from '@/components/common/CustomSelect.vue'

const uiStore = useUiStore()
const authStore = useAuthStore()
const media = ref([])
const loading = ref(true)
const search = ref('')
const typeFilter = ref('')
const viewMode = ref('grid')
const selectedIds = ref([])
const preview = ref(null)
const deleteTarget = ref(null)
const uploading = ref(false)
const uploadProgress = ref({ current: 0, total: 0 })
const isDragging = ref(false)
const storageProvider = ref(localStorage.getItem('media_storage_provider') || 'supabase')
watch(storageProvider, (v) => localStorage.setItem('media_storage_provider', v))
const allowedProviders = ref('both')
const meta = ref({ current_page: 1, last_page: 1, total: 0 })

let debounceTimer = null
function debouncedFetch() {
  clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => fetchMedia(), 300)
}

const imageCount = computed(() => media.value.filter(m => isImage(m)).length)
const docCount = computed(() => media.value.filter(m => !isImage(m)).length)
const totalSize = computed(() => {
  const bytes = media.value.reduce((sum, m) => sum + (m.size || 0), 0)
  return formatSize(bytes)
})
const allSelected = computed(() => media.value.length > 0 && selectedIds.value.length === media.value.length)

function isImage(item) {
  return item.mime_type?.startsWith('image/')
}

function getExt(item) {
  const name = item.original_name || item.filename || ''
  return name.split('.').pop() || ''
}

function formatSize(bytes) {
  if (!bytes) return '0 B'
  const k = 1024
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i]
}

function formatDate(d) {
  if (!d) return '—'
  return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

function timeAgo(date) {
  if (!date) return ''
  const diff = Date.now() - new Date(date).getTime()
  const mins = Math.floor(diff / 60000)
  if (mins < 1) return 'Just now'
  if (mins < 60) return `${mins}m ago`
  const hours = Math.floor(mins / 60)
  if (hours < 24) return `${hours}h ago`
  const days = Math.floor(hours / 24)
  if (days < 30) return `${days}d ago`
  return `${Math.floor(days / 30)}mo ago`
}

function toggleSelect(id) {
  const idx = selectedIds.value.indexOf(id)
  idx === -1 ? selectedIds.value.push(id) : selectedIds.value.splice(idx, 1)
}

function toggleSelectAll() {
  selectedIds.value = allSelected.value ? [] : media.value.map(m => m.id)
}

function openPreview(item) {
  preview.value = item
}

function copyUrl(item) {
  if (item.url) {
    navigator.clipboard.writeText(item.url)
    uiStore.showToast('URL copied to clipboard')
  }
}

async function fetchMedia(page = 1) {
  loading.value = true
  try {
    const params = { page: typeof page === 'number' ? page : 1 }
    if (search.value) params.search = search.value
    if (typeFilter.value) params.type = typeFilter.value
    const { data } = await adminApi.getMedia(params)
    media.value = data.data || []
    meta.value = data.meta || { current_page: 1, last_page: 1, total: 0 }
  } catch {
    media.value = []
  } finally {
    loading.value = false
  }
}

function formatUploadError(err) {
  const d = err.response?.data
  if (d?.errors && typeof d.errors === 'object') {
    const fileErr = d.errors.file
    if (Array.isArray(fileErr) && fileErr[0]) return String(fileErr[0])
    if (typeof fileErr === 'string') return fileErr
    const firstKey = Object.keys(d.errors)[0]
    const vals = firstKey ? d.errors[firstKey] : null
    if (Array.isArray(vals) && vals[0]) return String(vals[0])
  }
  return d?.message || err.message || 'Upload failed'
}

async function uploadFiles(e) {
  const files = Array.from(e.target.files || [])
  if (!files.length) return
  uploading.value = true
  uploadProgress.value = { current: 0, total: files.length }

  let ok = 0
  const failures = []

  for (const file of files) {
    const fd = new FormData()
    fd.append('file', file)
    fd.append('storage_provider', storageProvider.value)
    try {
      await adminApi.uploadMedia(fd)
      ok++
    } catch (err) {
      failures.push({ name: file.name, message: formatUploadError(err) })
    }
    uploadProgress.value.current++
  }

  uploading.value = false

  if (ok > 0 && failures.length === 0) {
    uiStore.showToast(`${ok} file${ok > 1 ? 's' : ''} uploaded!`)
  } else if (ok > 0 && failures.length > 0) {
    const f = failures[0]
    const hint = failures.length > 1 ? ` (+${failures.length - 1} more)` : ''
    uiStore.showToast(`${ok} uploaded, ${failures.length} failed${hint}. ${f.name}: ${f.message}`, 'warning')
  } else if (failures.length > 0) {
    const f = failures[0]
    const more = failures.length > 1 ? ` (${failures.length} files)` : ''
    uiStore.showToast(`Upload failed${more}: ${f.name} — ${f.message}`, 'error')
  }

  if (e.target && 'value' in e.target) e.target.value = ''
  fetchMedia()
}

function handleDrop(e) {
  isDragging.value = false
  const files = e.dataTransfer?.files
  if (files?.length) {
    uploadFiles({ target: { files }, preventDefault: () => {} })
  }
}

async function confirmDelete() {
  if (!deleteTarget.value) return
  try {
    await adminApi.deleteMedia(deleteTarget.value.id)
    media.value = media.value.filter(m => m.id !== deleteTarget.value.id)
    uiStore.showToast('File deleted')
  } catch (e) {
    if (e.response?.data?.intercepted) {
      uiStore.showToast(e.response.data.message || 'Queued for approval', 'warning')
    } else {
      uiStore.showToast('Failed to delete', 'error')
    }
  }
  deleteTarget.value = null
}

async function bulkDelete() {
  for (const id of selectedIds.value) {
    try {
      await adminApi.deleteMedia(id)
      media.value = media.value.filter(m => m.id !== id)
    } catch {}
  }
  uiStore.showToast(`${selectedIds.value.length} files deleted`)
  selectedIds.value = []
}

onMounted(async () => {
  try {
    const { data } = await adminApi.getStorageSettings()
    const allowed = data?.data?.allowed_storage_providers || 'both'
    allowedProviders.value = allowed
    if (allowed !== 'both') storageProvider.value = allowed
  } catch { /* ignore */ }
  fetchMedia()
})
</script>
