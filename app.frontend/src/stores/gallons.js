import { defineStore } from 'pinia';
import axios from 'axios';

export const useGallonStore = defineStore('gallon', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

    async getAllGallon() {
      try {
        await this.getToken();
        const response = await axios.get('/api/get-all-gallon');
        return response.data;

      } catch (error) {
        console.error(error);
      }
    },


  },
});