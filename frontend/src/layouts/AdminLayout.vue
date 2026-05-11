<template>
  <div
    class="min-h-screen flex bg-gray-50 dark:bg-dark-900 transition-colors duration-300"
  >
    <!-- ═══════ Sidebar ═══════ -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-30 flex flex-col transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)]',
        'bg-white dark:bg-dark-800 border-r border-gray-200/80 dark:border-white/[0.06]',
        sidebarCollapsed ? 'w-[72px]' : 'w-[260px]',
        uiStore.sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'lg:translate-x-0',
      ]"
    >
      <!-- ── Brand Header ── -->
      <div
        class="flex items-center h-16 px-4 border-b border-gray-200/80 dark:border-white/[0.06] flex-shrink-0"
      >
        <router-link to="/" class="flex items-center gap-2.5 min-w-0 group">
          <img
            src="https://res.cloudinary.com/kalapak/image/upload/q_auto/f_auto/v1775860922/Logo_kalapak_om1ygl.png"
            alt="Kalapak Logo"
            class="w-9 h-9 rounded-xl object-contain shadow-md shadow-brand-violet/20 dark:shadow-brand-cyan/20 flex-shrink-0 group-hover:shadow-lg transition-shadow duration-300"
          />
          <transition
            enter-active-class="transition duration-200 ease-out delay-75"
            enter-from-class="opacity-0 -translate-x-2"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition duration-100 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <div
              v-if="!sidebarCollapsed"
              class="flex flex-col leading-none min-w-0"
            >
              <span
                class="text-[15px] font-sans font-bold tracking-tight text-gray-900 dark:text-white truncate"
                >Kalapak</span
              >
              <span
                class="text-[9px] font-semibold uppercase tracking-[0.2em] text-gray-400 dark:text-gray-500"
                >Admin Panel</span
              >
            </div>
          </transition>
        </router-link>
      </div>

      <!-- ── Search Quick Access ── -->
      <div v-if="!sidebarCollapsed" class="px-3 pt-3 pb-1 flex-shrink-0">
        <button
          @click="searchOpen = true"
          class="flex items-center gap-2.5 w-full px-3 py-2 rounded-lg text-[13px] text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-white/[0.03] border border-gray-200/80 dark:border-white/[0.06] hover:border-brand-violet/30 dark:hover:border-brand-cyan/30 hover:text-gray-500 dark:hover:text-gray-400 transition-all duration-200"
        >
          <svg
            class="w-3.5 h-3.5 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
            />
          </svg>
          <span class="flex-1 text-left">Search...</span>
          <kbd
            class="hidden sm:inline-flex items-center gap-0.5 px-1.5 py-0.5 rounded bg-white dark:bg-white/[0.06] text-[10px] font-code text-gray-400 border border-gray-200/60 dark:border-white/[0.06]"
            >⌘K</kbd
          >
        </button>
      </div>
      <div v-else class="flex justify-center pt-3 pb-1 flex-shrink-0">
        <button
          @click="searchOpen = true"
          class="p-2 rounded-lg text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/[0.05] transition-all"
          title="Search (⌘K)"
        >
          <svg
            class="w-4.5 h-4.5"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
            />
          </svg>
        </button>
      </div>

      <!-- ── Navigation ── -->
      <nav
        class="flex-1 overflow-y-auto overflow-x-hidden px-3 py-2 sidebar-scrollbar"
      >
        <!-- Main section -->
        <div class="mb-1">
          <p
            v-if="!sidebarCollapsed"
            class="px-3 pt-3 pb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400/80 dark:text-gray-500/80 select-none"
          >
            Main
          </p>
          <div v-else class="flex justify-center py-2">
            <span class="w-5 h-px bg-gray-200 dark:bg-white/10 rounded-full" />
          </div>

          <router-link
            v-for="item in mainNavItems.filter(
              (i) => !i.superAdminOnly || authStore.isSuperAdmin,
            )"
            :key="item.routeName"
            :to="item.to"
            :title="sidebarCollapsed ? item.label : undefined"
            class="sidebar-link group relative flex items-center gap-3 rounded-lg transition-all duration-200"
            :class="[
              sidebarCollapsed
                ? 'justify-center px-0 py-2.5 mx-auto w-11'
                : 'px-3 py-2',
              isNavActive(item)
                ? 'bg-brand-violet/10 dark:bg-brand-cyan/10 text-brand-violet dark:text-brand-cyan font-semibold'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/80 dark:hover:bg-white/[0.04] hover:text-gray-900 dark:hover:text-white',
            ]"
          >
            <!-- Active indicator bar -->
            <span
              v-if="isNavActive(item)"
              class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full bg-brand-violet dark:bg-brand-cyan transition-all duration-300"
              :class="sidebarCollapsed ? 'h-5 -left-0.5' : 'h-5'"
            />
            <component
              :is="item.icon"
              class="w-[18px] h-[18px] flex-shrink-0"
            />
            <span v-if="!sidebarCollapsed" class="text-[13px] truncate">{{
              item.label
            }}</span>
            <!-- Badge -->
            <span
              v-if="item.badge && !sidebarCollapsed"
              class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center"
              :class="
                item.badgeColor ||
                'bg-gray-100 dark:bg-white/[0.06] text-gray-500 dark:text-gray-400'
              "
            >
              {{ item.badge }}
            </span>
            <!-- Collapsed badge dot -->
            <span
              v-if="item.badge && sidebarCollapsed"
              class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-dark-800"
            />
            <!-- Tooltip for collapsed -->
            <div
              v-if="sidebarCollapsed"
              class="sidebar-tooltip absolute left-full ml-3 px-2.5 py-1.5 rounded-lg bg-gray-900 dark:bg-gray-700 text-white text-xs font-medium shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50 pointer-events-none"
            >
              {{ item.label }}
              <span
                class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900 dark:border-r-gray-700"
              />
            </div>
          </router-link>
        </div>

        <!-- Content section -->
        <div class="mb-1">
          <p
            v-if="!sidebarCollapsed"
            class="px-3 pt-3 pb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400/80 dark:text-gray-500/80 select-none"
          >
            Content
          </p>
          <div v-else class="flex justify-center py-2">
            <span class="w-5 h-px bg-gray-200 dark:bg-white/10 rounded-full" />
          </div>

          <router-link
            v-for="item in contentNavItems"
            :key="item.routeName"
            :to="item.to"
            :title="sidebarCollapsed ? item.label : undefined"
            class="sidebar-link group relative flex items-center gap-3 rounded-lg transition-all duration-200"
            :class="[
              sidebarCollapsed
                ? 'justify-center px-0 py-2.5 mx-auto w-11'
                : 'px-3 py-2',
              isNavActive(item)
                ? 'bg-brand-violet/10 dark:bg-brand-cyan/10 text-brand-violet dark:text-brand-cyan font-semibold'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/80 dark:hover:bg-white/[0.04] hover:text-gray-900 dark:hover:text-white',
            ]"
          >
            <span
              v-if="isNavActive(item)"
              class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full bg-brand-violet dark:bg-brand-cyan transition-all duration-300"
              :class="sidebarCollapsed ? 'h-5 -left-0.5' : 'h-5'"
            />
            <component
              :is="item.icon"
              class="w-[18px] h-[18px] flex-shrink-0"
            />
            <span v-if="!sidebarCollapsed" class="text-[13px] truncate">{{
              item.label
            }}</span>
            <span
              v-if="item.badge && !sidebarCollapsed"
              class="ml-auto text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[20px] text-center"
              :class="
                item.badgeColor ||
                'bg-gray-100 dark:bg-white/[0.06] text-gray-500 dark:text-gray-400'
              "
            >
              {{ item.badge }}
            </span>
            <span
              v-if="item.badge && sidebarCollapsed"
              class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white dark:ring-dark-800"
            />
            <div
              v-if="sidebarCollapsed"
              class="sidebar-tooltip absolute left-full ml-3 px-2.5 py-1.5 rounded-lg bg-gray-900 dark:bg-gray-700 text-white text-xs font-medium shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50 pointer-events-none"
            >
              {{ item.label }}
              <span
                class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900 dark:border-r-gray-700"
              />
            </div>
          </router-link>
        </div>

        <!-- System section (superadmin only) -->
        <div v-if="authStore.isSuperAdmin" class="mb-1">
          <p
            v-if="!sidebarCollapsed"
            class="px-3 pt-3 pb-1.5 text-[10px] font-bold uppercase tracking-[0.12em] text-gray-400/80 dark:text-gray-500/80 select-none"
          >
            System
          </p>
          <div v-else class="flex justify-center py-2">
            <span class="w-5 h-px bg-gray-200 dark:bg-white/10 rounded-full" />
          </div>

          <router-link
            v-for="item in systemNavItems"
            :key="item.routeName"
            :to="item.to"
            :title="sidebarCollapsed ? item.label : undefined"
            class="sidebar-link group relative flex items-center gap-3 rounded-lg transition-all duration-200"
            :class="[
              sidebarCollapsed
                ? 'justify-center px-0 py-2.5 mx-auto w-11'
                : 'px-3 py-2',
              isNavActive(item)
                ? 'bg-brand-violet/10 dark:bg-brand-cyan/10 text-brand-violet dark:text-brand-cyan font-semibold'
                : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100/80 dark:hover:bg-white/[0.04] hover:text-gray-900 dark:hover:text-white',
            ]"
          >
            <span
              v-if="isNavActive(item)"
              class="absolute left-0 top-1/2 -translate-y-1/2 w-[3px] rounded-r-full bg-brand-violet dark:bg-brand-cyan transition-all duration-300"
              :class="sidebarCollapsed ? 'h-5 -left-0.5' : 'h-5'"
            />
            <component
              :is="item.icon"
              class="w-[18px] h-[18px] flex-shrink-0"
            />
            <span v-if="!sidebarCollapsed" class="text-[13px] truncate">{{
              item.label
            }}</span>
            <div
              v-if="sidebarCollapsed"
              class="sidebar-tooltip absolute left-full ml-3 px-2.5 py-1.5 rounded-lg bg-gray-900 dark:bg-gray-700 text-white text-xs font-medium shadow-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150 z-50 pointer-events-none"
            >
              {{ item.label }}
              <span
                class="absolute right-full top-1/2 -translate-y-1/2 border-4 border-transparent border-r-gray-900 dark:border-r-gray-700"
              />
            </div>
          </router-link>
        </div>
      </nav>

      <!-- ── Sidebar Footer ── -->
      <div
        class="flex-shrink-0 border-t border-gray-200/80 dark:border-white/[0.06]"
      >
        <!-- Collapse toggle -->
        <button
          @click="sidebarCollapsed = !sidebarCollapsed"
          class="hidden lg:flex items-center gap-2.5 w-full px-4 py-2.5 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-all duration-200"
          :class="sidebarCollapsed ? 'justify-center' : ''"
        >
          <svg
            class="w-4 h-4 transition-transform duration-300 flex-shrink-0"
            :class="sidebarCollapsed ? 'rotate-180' : ''"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M18.75 19.5l-7.5-7.5 7.5-7.5m-6 15L5.25 12l7.5-7.5"
            />
          </svg>
          <span v-if="!sidebarCollapsed" class="text-[12px] font-medium"
            >Collapse</span
          >
        </button>

        <!-- User card -->
        <div
          class="px-3 py-3 flex items-center gap-2.5 cursor-pointer hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-colors"
          :class="sidebarCollapsed ? 'justify-center' : ''"
          @click="profileOpen = !profileOpen"
        >
          <div class="relative flex-shrink-0">
            <img
              v-if="authStore.user?.avatar"
              :src="authStore.user.avatar"
              :alt="authStore.user?.name"
              class="w-8 h-8 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-white/10"
            />
            <div
              v-else
              class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-violet to-brand-cyan flex items-center justify-center text-white text-xs font-bold shadow-sm"
            >
              {{ authStore.user?.name?.charAt(0)?.toUpperCase() || "?" }}
            </div>
            <span
              class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-green-500 ring-2 ring-white dark:ring-dark-800"
            />
          </div>
          <div v-if="!sidebarCollapsed" class="flex-1 min-w-0">
            <p
              class="text-[13px] font-semibold text-gray-800 dark:text-white truncate"
            >
              {{ authStore.user?.name }}
            </p>
            <p class="text-[11px] text-gray-400 dark:text-gray-500 truncate">
              {{
                authStore.user?.role?.display_name ||
                authStore.user?.role?.name ||
                "Admin"
              }}
            </p>
          </div>
          <svg
            v-if="!sidebarCollapsed"
            class="w-4 h-4 text-gray-400 flex-shrink-0"
            fill="none"
            stroke="currentColor"
            stroke-width="2"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
            />
          </svg>
        </div>
      </div>
    </aside>

    <!-- ── Mobile overlay ── -->
    <transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="uiStore.sidebarOpen"
        class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"
        @click="uiStore.toggleSidebar()"
      />
    </transition>

    <!-- ═══════ Main Area ═══════ -->
    <div
      class="flex-1 flex flex-col min-h-screen min-w-0 transition-all duration-300"
      :class="sidebarCollapsed ? 'lg:ml-[72px]' : 'lg:ml-[260px]'"
    >
      <!-- ── Top Header Bar ── -->
      <header
        class="sticky top-0 z-10 flex items-center justify-between h-16 px-4 sm:px-6 bg-white/80 dark:bg-dark-800/80 backdrop-blur-xl border-b border-gray-200/80 dark:border-white/[0.06]"
      >
        <!-- Left: Mobile toggle + Breadcrumb area -->
        <div class="flex items-center gap-3">
          <button
            class="lg:hidden p-2 -ml-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/[0.05] transition-colors"
            @click="uiStore.toggleSidebar()"
          >
            <svg
              class="w-5 h-5"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
              />
            </svg>
          </button>
        </div>

        <!-- Right actions -->
        <div class="flex items-center gap-1.5 sm:gap-2">
          <!-- View site link -->
          <router-link
            to="/"
            class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[12px] font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/[0.05] transition-all"
          >
            <svg
              class="w-3.5 h-3.5"
              fill="none"
              stroke="currentColor"
              stroke-width="2"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"
              />
            </svg>
            View Site
          </router-link>

          <div class="hidden sm:block w-px h-5 bg-gray-200 dark:bg-white/10" />

          <ThemeToggle />

          <!-- Notification Bell -->
          <div class="relative" ref="notifDropdownRef">
            <button
              @click="toggleNotifications"
              class="relative p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/[0.05] transition-all duration-200"
            >
              <svg
                class="w-[18px] h-[18px]"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                />
              </svg>
              <span
                v-if="notifStore.unreadCount > 0"
                class="absolute top-0.5 right-0.5 min-w-[16px] h-4 flex items-center justify-center px-1 rounded-full bg-red-500 text-white text-[10px] font-bold ring-2 ring-white dark:ring-dark-800"
              >
                {{
                  notifStore.unreadCount > 99 ? "99+" : notifStore.unreadCount
                }}
              </span>
            </button>

            <!-- Notification Backdrop (mobile only) -->
            <div
              v-if="notifOpen"
              class="fixed inset-0 bg-black/30 z-40 sm:hidden"
              @click="notifOpen = false"
            />
            <!-- Notification Dropdown -->
            <transition
              enter-active-class="transition duration-200 ease-out"
              enter-from-class="opacity-0 scale-95"
              enter-to-class="opacity-100 scale-100"
              leave-active-class="transition duration-150 ease-in"
              leave-from-class="opacity-100 scale-100"
              leave-to-class="opacity-0 scale-95"
            >
              <div
                v-if="notifOpen"
                class="fixed left-4 right-4 top-20 z-50 sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-2 sm:w-96 rounded-xl bg-white dark:bg-dark-800 shadow-xl shadow-black/8 dark:shadow-black/30 ring-1 ring-gray-200/80 dark:ring-white/[0.08] overflow-hidden"
              >
                <div
                  class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-white/[0.06]"
                >
                  <h3 class="text-sm font-bold text-gray-900 dark:text-white">
                    Notifications
                  </h3>
                  <button
                    v-if="notifStore.unreadCount > 0"
                    @click="notifStore.markAllRead()"
                    class="text-[11px] font-semibold text-brand-violet dark:text-brand-cyan hover:underline"
                  >
                    Mark all read
                  </button>
                </div>
                <div class="max-h-80 overflow-y-auto">
                  <div
                    v-if="
                      notifStore.loading &&
                      notifStore.notifications.length === 0
                    "
                    class="flex items-center justify-center py-8"
                  >
                    <svg
                      class="w-5 h-5 animate-spin text-gray-400"
                      fill="none"
                      viewBox="0 0 24 24"
                    >
                      <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                      />
                      <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                      />
                    </svg>
                  </div>
                  <div
                    v-else-if="notifStore.notifications.length === 0"
                    class="flex flex-col items-center justify-center py-10 px-4"
                  >
                    <svg
                      class="w-10 h-10 text-gray-300 dark:text-gray-600 mb-2"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="1.5"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"
                      />
                    </svg>
                    <p class="text-sm text-gray-400 dark:text-gray-500">
                      No notifications yet
                    </p>
                  </div>
                  <div v-else>
                    <div
                      v-for="notif in notifStore.notifications"
                      :key="notif.id"
                      class="group relative flex gap-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-white/[0.03] transition-colors cursor-pointer border-b border-gray-50 dark:border-white/[0.03] last:border-0"
                      :class="{
                        'bg-brand-violet/[0.03] dark:bg-brand-cyan/[0.03]':
                          !notif.read_at,
                      }"
                      @click="handleNotifClick(notif)"
                    >
                      <div class="flex-shrink-0 mt-0.5">
                        <div
                          class="w-8 h-8 rounded-lg flex items-center justify-center"
                          :class="notifIconClass(notif.data?.icon)"
                        >
                          <svg
                            v-if="notif.data?.icon === 'success'"
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M4.5 12.75l6 6 9-13.5"
                            />
                          </svg>
                          <svg
                            v-else-if="notif.data?.icon === 'warning'"
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                            />
                          </svg>
                          <svg
                            v-else-if="notif.data?.icon === 'error'"
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                            />
                          </svg>
                          <svg
                            v-else
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
                            />
                          </svg>
                        </div>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p
                          class="text-[13px] font-semibold text-gray-900 dark:text-white truncate"
                        >
                          {{ notif.data?.title || "Notification" }}
                        </p>
                        <p
                          class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2 mt-0.5"
                        >
                          {{ notif.data?.message }}
                        </p>
                        <p
                          class="text-[10px] text-gray-400 dark:text-gray-500 mt-1"
                        >
                          {{ formatTime(notif.created_at) }}
                        </p>
                      </div>
                      <div v-if="!notif.read_at" class="flex-shrink-0 mt-2">
                        <span
                          class="w-2 h-2 rounded-full bg-brand-violet dark:bg-brand-cyan block"
                        />
                      </div>
                      <button
                        @click.stop="notifStore.deleteNotification(notif.id)"
                        class="absolute top-2 right-2 p-1 rounded-md text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 opacity-0 group-hover:opacity-100 transition-all"
                      >
                        <svg
                          class="w-3.5 h-3.5"
                          fill="none"
                          stroke="currentColor"
                          stroke-width="2"
                          viewBox="0 0 24 24"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6 18L18 6M6 6l12 12"
                          />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </transition>
          </div>

          <!-- Profile Dropdown -->
          <div class="relative" ref="profileDropdownRef">
            <button
              @click="profileOpen = !profileOpen"
              class="flex items-center gap-2 px-1.5 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-white/[0.05] transition-colors"
            >
              <img
                v-if="authStore.user?.avatar"
                :src="authStore.user.avatar"
                :alt="authStore.user?.name"
                class="w-7 h-7 rounded-lg object-cover ring-1 ring-gray-200 dark:ring-white/10"
              />
              <div
                v-else
                class="w-7 h-7 rounded-lg bg-gradient-to-br from-brand-violet to-brand-cyan flex items-center justify-center text-white text-[11px] font-bold"
              >
                {{ authStore.user?.name?.charAt(0)?.toUpperCase() || "?" }}
              </div>
              <span
                class="hidden sm:block text-[13px] font-medium text-gray-700 dark:text-gray-200 max-w-[120px] truncate"
              >
                {{ authStore.user?.name || "User" }}
              </span>
              <svg
                class="w-3.5 h-3.5 text-gray-400 transition-transform duration-200"
                :class="{ 'rotate-180': profileOpen }"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M19 9l-7 7-7-7"
                />
              </svg>
            </button>

            <transition
              enter-active-class="transition duration-150 ease-out"
              enter-from-class="opacity-0 scale-95 -translate-y-1"
              enter-to-class="opacity-100 scale-100 translate-y-0"
              leave-active-class="transition duration-100 ease-in"
              leave-from-class="opacity-100 scale-100"
              leave-to-class="opacity-0 scale-95"
            >
              <div
                v-if="profileOpen"
                class="absolute right-0 mt-2 w-60 rounded-xl bg-white dark:bg-dark-800 shadow-xl shadow-black/8 dark:shadow-black/30 ring-1 ring-gray-200/80 dark:ring-white/[0.08] py-1.5 z-50"
              >
                <div
                  class="px-4 py-3 border-b border-gray-100 dark:border-white/[0.06]"
                >
                  <p
                    class="text-sm font-semibold text-gray-800 dark:text-white truncate"
                  >
                    {{ authStore.user?.name }}
                  </p>
                  <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    {{ authStore.user?.email }}
                  </p>
                  <span
                    v-if="authStore.user?.role?.name"
                    class="inline-flex items-center gap-1 mt-2 px-2 py-0.5 text-[10px] font-semibold rounded-md bg-brand-violet/8 text-brand-violet dark:bg-brand-cyan/8 dark:text-brand-cyan uppercase tracking-wider"
                  >
                    <span class="w-1 h-1 rounded-full bg-current" />
                    {{
                      authStore.user.role.display_name ||
                      authStore.user.role.name
                    }}
                  </span>
                </div>
                <div class="py-1 px-1.5">
                  <router-link
                    to="/member/profile"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/[0.04] transition-colors"
                    @click="profileOpen = false"
                  >
                    <svg
                      class="w-4 h-4 text-gray-400"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="1.8"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"
                      />
                    </svg>
                    My Profile
                  </router-link>
                  <router-link
                    to="/"
                    class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-[13px] text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/[0.04] transition-colors"
                    @click="profileOpen = false"
                  >
                    <svg
                      class="w-4 h-4 text-gray-400"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="1.8"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"
                      />
                    </svg>
                    View Site
                  </router-link>
                </div>
                <div
                  class="border-t border-gray-100 dark:border-white/[0.06] mx-1.5 my-1"
                />
                <div class="px-1.5 pb-0.5">
                  <button
                    @click="
                      handleLogout();
                      profileOpen = false;
                    "
                    class="flex items-center gap-2.5 w-full px-3 py-2 rounded-lg text-[13px] text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors"
                  >
                    <svg
                      class="w-4 h-4"
                      fill="none"
                      stroke="currentColor"
                      stroke-width="1.8"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"
                      />
                    </svg>
                    Sign Out
                  </button>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </header>

      <!-- ── Content ── -->
      <main class="flex-1 p-4 sm:p-6">
        <router-view v-slot="{ Component }">
          <KeepAlive :max="20">
            <component :is="Component" v-if="Component" :key="route.fullPath" />
          </KeepAlive>
        </router-view>
      </main>
    </div>

    <!-- Search Modal -->
    <SearchModal :open="searchOpen" @close="searchOpen = false" />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, computed } from "vue";
import { useRouter, useRoute } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useUiStore } from "@/stores/ui";
import { useNotificationStore } from "@/stores/notifications";
import { adminApi } from "@/services/api";
import ThemeToggle from "@/components/common/ThemeToggle.vue";
import SearchModal from "@/components/admin/AdminSearchModal.vue";
import {
  HomeIcon,
  UsersIcon,
  FolderIcon,
  DocumentTextIcon,
  EnvelopeIcon,
  UserGroupIcon,
  PhotoIcon,
  CogIcon,
  ClipboardDocumentListIcon,
  ClipboardDocumentCheckIcon,
  ShieldCheckIcon,
  InboxStackIcon,
  TagIcon,
  RectangleStackIcon,
  BookOpenIcon,
  ListBulletIcon,
} from "@heroicons/vue/24/outline";

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const uiStore = useUiStore();
const notifStore = useNotificationStore();

const profileOpen = ref(false);
const profileDropdownRef = ref(null);
const notifDropdownRef = ref(null);
const notifOpen = ref(false);
const sidebarCollapsed = ref(false);
const searchOpen = ref(false);
const pendingApprovalsCount = ref(0);

async function fetchPendingApprovals() {
  if (!authStore.isSuperAdmin) return;
  try {
    const { data } = await adminApi.getApprovalRequests({ status: "pending" });
    pendingApprovalsCount.value = data.meta?.total || 0;
  } catch {}
}

// ── Navigation groups ──
const mainNavItems = [
  {
    label: "Dashboard",
    to: "/admin",
    routeName: "admin-dashboard",
    icon: HomeIcon,
  },
  {
    label: "Users",
    to: "/admin/users",
    routeName: "admin-users",
    icon: UsersIcon,
    superAdminOnly: true,
  },
  {
    label: "Team",
    to: "/admin/team",
    routeName: "admin-team",
    icon: UserGroupIcon,
  },
];

const contentNavItems = [
  {
    label: "Projects",
    to: "/admin/projects",
    routeName: "admin-projects",
    icon: FolderIcon,
  },
  {
    label: "Blog Posts",
    to: "/admin/blog",
    routeName: "admin-blog",
    icon: DocumentTextIcon,
  },
  {
    label: "Categories",
    to: "/admin/categories",
    routeName: "admin-categories",
    icon: RectangleStackIcon,
  },
  {
    label: "Media",
    to: "/admin/media",
    routeName: "admin-media",
    icon: PhotoIcon,
  },
  { label: "Tags", to: "/admin/tags", routeName: "admin-tags", icon: TagIcon },
  {
    label: "Messages",
    to: "/admin/messages",
    routeName: "admin-messages",
    icon: EnvelopeIcon,
    badge: null,
    badgeColor: "bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400",
  },
  {
    label: "Applications",
    to: "/admin/applications",
    routeName: "admin-applications",
    icon: InboxStackIcon,
  },
  {
    label: "Docs",
    to: "/admin/docs",
    routeName: "admin-docs",
    icon: BookOpenIcon,
  },
  {
    label: "Doc Categories",
    to: "/admin/doc-categories",
    routeName: "admin-doc-categories",
    icon: TagIcon,
  },
  {
    label: "Doc Menus",
    to: "/admin/doc-menus",
    routeName: "admin-doc-menus",
    icon: ListBulletIcon,
  },
];

const systemNavItems = computed(() => [
  {
    label: "Roles",
    to: "/admin/roles",
    routeName: "admin-roles",
    icon: ShieldCheckIcon,
  },
  {
    label: "Settings",
    to: "/admin/settings",
    routeName: "admin-settings",
    icon: CogIcon,
  },
  {
    label: "Activity Logs",
    to: "/admin/activity-logs",
    routeName: "admin-activity-logs",
    icon: ClipboardDocumentListIcon,
  },
  {
    label: "Approval Requests",
    to: "/admin/approval-requests",
    routeName: "admin-approval-requests",
    icon: ClipboardDocumentCheckIcon,
    badge: pendingApprovalsCount.value || null,
    badgeColor:
      "bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400",
  },
]);

function isNavActive(item) {
  if (item.routeName === "admin-dashboard") {
    return route.path === "/admin" || route.name === "admin-dashboard";
  }
  return route.name === item.routeName || route.path.startsWith(item.to);
}

function handleClickOutside(e) {
  if (
    profileDropdownRef.value &&
    !profileDropdownRef.value.contains(e.target)
  ) {
    profileOpen.value = false;
  }
  if (notifDropdownRef.value && !notifDropdownRef.value.contains(e.target)) {
    notifOpen.value = false;
  }
}

function toggleNotifications() {
  notifOpen.value = !notifOpen.value;
  if (notifOpen.value) {
    notifStore.fetchNotifications();
  }
}

function handleNotifClick(notif) {
  if (!notif.read_at) {
    notifStore.markAsRead(notif.id);
  }
  if (notif.data?.action_url) {
    notifOpen.value = false;
    router.push(notif.data.action_url);
  }
}

function notifIconClass(icon) {
  switch (icon) {
    case "success":
      return "bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400";
    case "warning":
      return "bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400";
    case "error":
      return "bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400";
    default:
      return "bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400";
  }
}

function formatTime(dateStr) {
  if (!dateStr) return "";
  const date = new Date(dateStr);
  const now = new Date();
  const diff = Math.floor((now - date) / 1000);
  if (diff < 60) return "Just now";
  if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
  if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
  if (diff < 604800) return `${Math.floor(diff / 86400)}d ago`;
  return date.toLocaleDateString();
}

watch(
  () => authStore.isAuthenticated,
  (loggedIn) => {
    if (loggedIn) notifStore.startPolling();
    else notifStore.$reset();
  },
  { immediate: true },
);

function handleSearchKeydown(e) {
  if ((e.metaKey || e.ctrlKey) && e.key === "k") {
    e.preventDefault();
    searchOpen.value = true;
  }
}

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
  document.addEventListener("keydown", handleSearchKeydown);
  fetchPendingApprovals();
});
onUnmounted(() => {
  document.removeEventListener("click", handleClickOutside);
  document.removeEventListener("keydown", handleSearchKeydown);
  notifStore.stopPolling();
});

async function handleLogout() {
  authStore.logout();
  router.push({ name: "login" });
}
</script>

<style scoped>
/* Sidebar custom scrollbar */
.sidebar-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.sidebar-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.sidebar-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 99px;
}
:root.dark .sidebar-scrollbar::-webkit-scrollbar-thumb,
.dark .sidebar-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.06);
}
.sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
  background: rgba(0, 0, 0, 0.18);
}
:root.dark .sidebar-scrollbar:hover::-webkit-scrollbar-thumb,
.dark .sidebar-scrollbar:hover::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.12);
}
</style>
