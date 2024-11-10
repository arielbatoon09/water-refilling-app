import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    authUser: null,
  }),
  getters: {
    user: (state) => state.authUser,
    isAuthenticated: (state) => {
      console.log('Checking isAuthenticated:', state.authUser !== null);
      return state.authUser !== null;
    },
  },
  persist: true,
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },
    async fetchUser() {
      try {
        const response = await axios.get('/api/user');
        const userData = response.data;
        if (userData.id) {
          this.authUser = userData;
        } else {
          this.authUser = null;
        }
      } catch (error) {
        console.error(error);
      }
    },

    async register(FormData) {
      try {
        await this.getToken();
        const response = await axios.post('/api/register', {
          name: FormData.name,
          email: FormData.email,
          password: FormData.password,
        });

        await this.fetchUser();
        return response.data;

      } catch (error) {
        console.error(error);
      }
    },

    async login(FormData) {
      try {
        console.log(FormData);
        await this.getToken();
        const response = await axios.post('/api/login', {
          email: FormData.email,
          password: FormData.password
        });

        await this.fetchUser();
        return response.data;


      } catch (error) {
        console.error(error);
      }
    },

    async logout() {
      try {
        const response = await axios.get('/api/logout');
        localStorage.removeItem('auth');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },
  },
});