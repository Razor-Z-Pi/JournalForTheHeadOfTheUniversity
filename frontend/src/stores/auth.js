import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null);
  const token = ref(localStorage.getItem('token') || null);
  const router = useRouter();

  const login = async (email, password) => {
    const response = await axios.post('http://localhost:8000/api/login', { email, password });
    token.value = response.data.token;
    user.value = response.data.user;
    localStorage.setItem('token', token.value);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
    router.push('/');
  };

  const logout = async () => {
    await axios.post('http://localhost:8000/api/logout');
    token.value = null;
    user.value = null;
    localStorage.removeItem('token');
    delete axios.defaults.headers.common['Authorization'];
    router.push('/login');
  };

  return { user, token, login, logout };
});