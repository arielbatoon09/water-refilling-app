import { defineStore } from 'pinia';
import axios from 'axios';

export const useSalesStore = defineStore('sales', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

    async getAllSuccessSales(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-sales-report');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async getAllTallySuccessSales(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-tally-sales-report');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async getAllSuccessSalesData(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-sales-data-report');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },
  },
});
