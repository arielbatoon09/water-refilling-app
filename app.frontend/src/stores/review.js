import { defineStore } from 'pinia';
import axios from 'axios';

export const useReviewStore = defineStore('review', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

    async getAllReviewAdmin(){
      try {
        await this.getToken();
        const response = await axios.get('/api/all-review-admin');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },
  },
});
