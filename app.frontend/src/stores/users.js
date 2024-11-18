import { defineStore } from 'pinia';
import axios from 'axios';

export const useUsersStore = defineStore('user', {
  actions: {
    async getToken() {
      await axios.get('/sanctum/csrf-cookie');
    },

    async getAllUser(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-all-user');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async getAllUserById(id){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-user-by-uid/' + id);
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async removeUserById(id){
      try {
        await this.getToken();
        const response = await axios.post('/api/remove-user/' + id);
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async updateUserWithId(FormData){
      try {
        await this.getToken();
        const response = await axios.post('/api/update-user', {
          name: FormData.name,
          email: FormData.email,
          user_role: FormData.user_role,
          flag: FormData.flag,
          id: FormData.id,
        });
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async getInformation(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-user-info');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async getAddressInformation(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-user-address');
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
    },

    async changeUserInformation(FormData){
      try {
        await this.getToken();
        const response = await axios.post('/api/change-user-info', {
          name: FormData.name,
          email: FormData.email
        });
        return response;
      } catch (error) {
        console.log('Error in ' + error);
      }
      
    },

    async addUserAddress(FormData){
      try {
        await this.getToken();
        const response = await axios.post('/api/add-user-address', {
          addressLine: FormData.addressLine,
          municipality: FormData.municipality,
          city: FormData.city,
          postalCode: FormData.postalCode,
          phone_number: FormData.phone_number,
        });
        return response;
      } catch (error) {
        console.log('Error in ' + error);
      }
      
    },

    async changeUserAddresss(FormData){
      try {
        await this.getToken();
        const response = await axios.post('/api/change-user-address', {
          addressLine: FormData.addressLine,
          municipality: FormData.municipality,
          city: FormData.city,
          postalCode: FormData.postalCode,
          phone_number: FormData.phone_number,
        });
        return response;
      } catch (error) {
        console.log('Error in ' + error);
      }
      
    },

    async changePassword(FormData){
      try {
        await this.getToken();
        const response = await axios.post('/api/change-user-password', {
          current_password: FormData.current_password,
          new_password: FormData.new_password
        });
        return response.data;
      } catch (error) {
        console.log('Error in ' + error);
      }
      
    },



  },
});
