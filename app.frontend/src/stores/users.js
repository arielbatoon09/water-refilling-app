import { defineStore } from 'pinia';
import axios from 'axios';

export const useUsersStore = defineStore('user', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

  },
});