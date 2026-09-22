import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/LoginView.vue'),
  },
  {
    path: '/',
    name: 'journal',
    component: () => import('@/views/JournalView.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/students',
    name: 'students',
    component: () => import('@/views/StudentsView.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/disciplines',
    name: 'disciplines',
    component: () => import('@/views/DisciplinesView.vue'),
    meta: { requiresAuth: true },
  },
  {
    path: '/groups',
    name: 'groups',
    component: () => import('@/views/GroupsView.vue'),
    meta: { requiresAuth: true },
  },
  // Необязательно: перенаправление на 404 или главную
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

router.beforeEach((to) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) {
    return '/login'
  }
})

export default router