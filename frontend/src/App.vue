<template>
  <div class="app-wrapper">
    <nav v-if="authStore.token" class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
      <div class="container-fluid">
        <router-link class="navbar-brand d-flex align-items-center gap-2" to="/">
          <i class="bi bi-journal-bookmark-fill"></i>
          <span>Журнал Старосты</span>
        </router-link>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#mainNav"
          aria-controls="mainNav"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link class="nav-link" to="/" exact-active-class="active">
                <i class="bi bi-table me-1"></i>Журнал
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/students" active-class="active">
                <i class="bi bi-people me-1"></i>Студенты
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/groups" active-class="active">
                <i class="bi bi-diagram-3 me-1"></i>Группы
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/disciplines" active-class="active">
                <i class="bi bi-book me-1"></i>Дисциплины
              </router-link>
            </li>
          </ul>
          <button class="btn btn-outline-light btn-sm" @click="authStore.logout">
            <i class="bi bi-box-arrow-right me-1"></i>Выйти
          </button>
        </div>
      </div>
    </nav>

    <main class="app-main">
      <router-view v-slot="{ Component }">
        <transition name="fade-slide" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '@/stores/auth'
const authStore = useAuthStore()
</script>

<style>
:root {
  --app-bg: #f4f6fb;
  --app-primary: #4284fd;
  --app-primary-dark: #3352b8;
  --app-dark: #1f2937;
  --app-border: #e5e7eb;
}

html, body {
  background: var(--app-bg);
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  color: #111827;
  min-height: 100vh;
}

.app-wrapper {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.app-navbar {
  background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
  box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
  padding: 0.6rem 0;
}

.app-navbar .navbar-brand {
  font-weight: 700;
  letter-spacing: 0.3px;
}

.app-navbar .nav-link {
  color: rgba(255, 255, 255, 0.75) !important;
  font-weight: 500;
  padding: 0.5rem 1rem !important;
  border-radius: 8px;
  transition: all 0.2s ease;
  position: relative;
}

.app-navbar .nav-link:hover {
  color: #fff !important;
  background: rgba(255, 255, 255, 0.08);
  transform: translateY(-1px);
}

.app-navbar .nav-link.active {
  color: #fff !important;
  background: var(--app-primary);
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
}

.app-main {
  flex: 1;
  padding: 1.5rem 0 3rem;
}

.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.25s ease, transform 0.25s ease;
}
.fade-slide-enter-from {
  opacity: 0;
  transform: translateY(12px);
}
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

.card {
  border: 1px solid var(--app-border);
  border-radius: 14px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  transition: box-shadow 0.25s ease, transform 0.25s ease;
}

.card:hover {
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
}

.btn {
  border-radius: 8px;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-primary {
  background: var(--app-primary);
  border-color: var(--app-primary);
}

.btn-primary:hover {
  background: var(--app-primary-dark);
  border-color: var(--app-primary-dark);
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

h2 {
  font-weight: 700;
  color: var(--app-dark);
  letter-spacing: -0.3px;
}

::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}
::-webkit-scrollbar-track {
  background: transparent;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>