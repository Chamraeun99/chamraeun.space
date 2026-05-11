import { useAuthStore } from "@/stores/auth";

export default defineNuxtPlugin((nuxtApp) => {
  const router = nuxtApp.$router;

  router.beforeEach(async (to, from) => {
    // Re-clicking the current admin sidebar link should not re-run navigation (avoids remount + refetch).
    if (
      from.name != null &&
      to.fullPath === from.fullPath &&
      typeof to.path === "string" &&
      to.path.startsWith("/admin")
    ) {
      return false;
    }

    const authStore = useAuthStore();

    // OAuth return URLs carry ?token=… — must not run prefetch fetchMe with a stale
    // localStorage token first (401 → axios redirects to login before the callback applies the new token).
    const isOAuthCallback =
      to.name === "github-callback" || to.name === "google-callback";

    if (!isOAuthCallback && authStore.token && !authStore.user) {
      await authStore.fetchMe();
    }

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
      return { name: "login", query: { redirect: to.fullPath } };
    }

    if (to.meta.requiresAdmin && !authStore.isAdmin) {
      return { name: "home" };
    }

    if (to.meta.requiresSuperAdmin && !authStore.isSuperAdmin) {
      return { name: "admin-dashboard" };
    }

    if (to.meta.guestOnly && authStore.isAuthenticated && !isOAuthCallback) {
      return { name: "home" };
    }

    return true;
  });

  router.afterEach((to) => {
    document.title =
      to.meta.title || "Kalapak Code Team | Modern Tech Solutions from Cambodia";
  });
});
