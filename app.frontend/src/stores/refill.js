import { defineStore } from 'pinia';
import axios from 'axios';

export const useRefillStore = defineStore('refill', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

    async bookRefill(FormData) {
      try {
        await this.getToken();

        const response = await axios.post('/api/add-refills', {
          gallon_details: FormData.gallon_details,
          delivery_type: FormData.delivery_type,
          mop: FormData.mop,
          delivery_date: FormData.delivery_date
        });
        return response.data;

      } catch (error) {
        console.error(error);
      }
    },

    async getBookRefills() {
      try {
        await this.getToken();
        const response = await axios.get('/api/get-refills-by-uid');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async payRefillNow(data) {
      try {
        await this.getToken();
        const response = await axios.post('/api/pay-refills', {
          id: data.id,
          gallon_details: data.gallon_details,
          t_refill_fee: data.t_refill_fee,
          t_delivery_fee: data.t_delivery_fee,
          mop: data.mop,
          deliveryType: data.delivery_type,
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async paidRefill(id, mop, deliveryType) {
      try {
        await this.getToken();
        const response = await axios.post('/api/paid-refill', {
          id: id,
          mop: mop,
          deliveryType: deliveryType
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async cancelRefill(id) {
      try {
        await this.getToken();
        const response = await axios.post(`/api/cancel-refill/${id}`);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async completeRefill(id) {
      try {
        await this.getToken();
        const response = await axios.post(`/api/complete-refill/${id}`);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async getAllRefill(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-all-refills');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async changeStatus (id){
      try {
        await this.getToken();
        const response = await axios.post('/api/change-refills-delivered/' + id);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    }

  },
});