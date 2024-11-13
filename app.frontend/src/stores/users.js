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

  },
});
