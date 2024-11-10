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

    async addGallonType(FormData) {
      try {
        await this.getToken();
        const response = await axios.post('/api/add-gallon-type', {
          gallon_image: FormData.gallon_image,
          gallon_size: FormData.gallon_size,
          gallon_price: FormData.gallon_price,
          delivery_fee: FormData.delivery_fee,
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },


  },
});