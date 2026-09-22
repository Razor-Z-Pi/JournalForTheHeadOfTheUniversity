<template>
  <div class="login-page">
    <div class="login-bg-blob blob-1"></div>
    <div class="login-bg-blob blob-2"></div>

    <transition name="fade-slide" appear>
      <div class="login-card">
        <div class="text-center mb-4">
          <div class="logo-circle">
            <i class="bi bi-journal-bookmark-fill"></i>
          </div>
          <h3 class="mt-3 mb-1 fw-bold">Журнал Старосты</h3>
          <p class="text-muted mb-0">Войдите, чтобы продолжить</p>
        </div>

        <div v-if="error" class="alert alert-danger d-flex align-items-center gap-2">
          <i class="bi bi-exclamation-triangle-fill"></i>
          {{ error }}
        </div>

        <form @submit.prevent="handleLogin">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-icon">
              <i class="bi bi-envelope"></i>
              <input type="email" class="form-control" v-model="email" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">Пароль</label>
            <div class="input-icon">
              <i class="bi bi-lock"></i>
              <input type="password" class="form-control" v-model="password" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100 py-2" :disabled="loading">
            <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
            <i v-else class="bi bi-box-arrow-in-right me-2"></i>
            {{ loading ? 'Вход...' : 'Войти' }}
          </button>
        </form>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'

const email = ref('starosta@mail.ru')
const password = ref('123456')
const error = ref('')
const loading = ref(false)

const authStore = useAuthStore()

const handleLogin = async () => {
  error.value = ''
  loading.value = true
  try {
    await authStore.login(email.value, password.value)
  } catch (e) {
    error.value = e.response?.data?.message || 'Ошибка входа. Проверьте данные.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #eef2ff 0%, #f5f3ff 100%);
  padding: 1rem;
}

.login-bg-blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(80px);
  opacity: 0.55;
  animation: floatBlob 8s ease-in-out infinite;
}

.blob-1 {
  width: 400px; height: 400px;
  background: #a5b4fc;
  top: -100px; left: -100px;
}

.blob-2 {
  width: 350px; height: 350px;
  background: #c4b5fd;
  bottom: -100px; right: -100px;
  animation-delay: -4s;
}

@keyframes floatBlob {
  0%, 100% { transform: translate(0, 0) scale(1); }
  50%      { transform: translate(20px, -20px) scale(1.05); }
}

.login-card {
  position: relative;
  z-index: 1;
  width: 100%;
  max-width: 420px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(12px);
  border-radius: 20px;
  padding: 2.5rem 2rem;
  box-shadow: 0 20px 60px rgba(31, 41, 55, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.7);
}

.logo-circle {
  width: 64px; height: 64px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--app-primary) 0%, #8b5cf6 100%);
  color: #fff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 1.8rem;
  box-shadow: 0 8px 24px rgba(99, 102, 241, 0.4);
  animation: popIn 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}

@keyframes popIn {
  from { transform: scale(0); opacity: 0; }
  to   { transform: scale(1); opacity: 1; }
}

.form-label {
  font-weight: 500;
  color: #374151;
  font-size: 0.9rem;
}

.input-icon {
  position: relative;
}
.input-icon i {
  position: absolute;
  left: 12px;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
}
.input-icon .form-control {
  padding-left: 38px;
  border-radius: 10px;
  height: 44px;
  border: 1px solid #e5e7eb;
  transition: all 0.2s ease;
}
.input-icon .form-control:focus {
  border-color: var(--app-primary);
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
}

.btn-primary {
  border-radius: 10px;
  font-weight: 600;
}
</style>