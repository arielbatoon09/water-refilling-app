import { defineStore } from 'pinia';
import axios from 'axios';

export const useProductStore = defineStore('product', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

    async addProduct(FormData) {
      await this.getToken();
      const response = await axios.post('/api/add-product', {
        product_name: FormData.product_name,
        stock: FormData.stock,
        product_image: FormData.product_image,
        // product_image: "http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg",
        description: FormData.description,
        cost: FormData.cost,
      });
      console.log(response);
    },

    async getProducts() {
      try {
        await this.getToken();
        const response = await axios.get('/api/get-products');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async getSelectedProducts(id) {
      try {
        await this.getToken();
        const response = await axios.get(`/api/get-products-by-pid/${id}`);
        // return response.data;
        console.log(response.data);
      } catch (error) {
        console.error(error);
      }
    },

    async updateProducts(FormData) {
      try {
        await this.getToken();
        const response = await axios.post(`/api/update-product/${FormData.product_id}`,{
          product_name: FormData.product_name,
          stock: FormData.stock,
          // product_image: FormData.product_image,
          product_image: "http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg",
          description: FormData.description,
          cost: FormData.cost,
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

  },
});