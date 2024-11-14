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
        description: FormData.description,
        cost: FormData.cost,
      });

      return response.data;
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
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async updateProducts(FormData) {
      try {
        await this.getToken();
        const response = await axios.post(`/api/update-product/${FormData.id}`, {
          product_name: FormData.product_name,
          stock: FormData.stock,
          product_image: FormData.product_image,
          description: FormData.description,
          cost: FormData.cost,
        });

        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async removeProduct(id){
      try {
        await this.getToken();
        const response = await axios.post(`/api/remove-product/${id}`);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async addToCart(FormData) {
      try {
        await this.getToken();
        const response = await axios.post('/api/add-to-cart', {
          product_id: FormData.product_id,
          order_quantity: FormData.order_quantity,
          unit_price: FormData.unit_price,
        });
        return response.data;

      } catch (error) {
        console.error(error);
      }
    },

    async getAllUIDCart() {
      try {
        await this.getToken();
        const response = await axios.get('/api/get-uid-cart');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async updateCartOrderQuantity(id, order_quantity) {
      try {
        await this.getToken();
        const response = await axios.post('/api/update-cart-qty', {
          id: id,
          order_quantity: order_quantity
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async removeCartOrderById(id) {
      try {
        await this.getToken();
        const response = await axios.post(`/api/remove-to-cart/${id}`);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async checkoutCart(cartArray, mop, deliveryType) {
      try {
        const response = await axios.post('/api/checkout', {
          mop: mop,
          deliveryType: deliveryType,
          cartArray: cartArray
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async getAllPurchases() {
      try {
        await this.getToken();
        const response = await axios.get('/api/get-orders-uid');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async handlePayNow(data) {
      try {
        await this.getToken();
        const response = await axios.post('/api/checkout/pay-now', {
          data: data
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async updateOrderPaid(refid, status) {
      try {
        await this.getToken();
        const response = await axios.post('/api/checkout/paid', {
          refid: refid,
          status: status
        });
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async removeOrderByCartId(id) {
      try {
        await this.getToken();
        const response = await axios.post(`/api/checkout/remove/${id}`);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async sendReviewFeedback(FormData) {
      try {
        await this.getToken();
        const response = await axios.post('/api/add-review', {
          resource: FormData.resource,
          resource_ref: FormData.resource_ref,
          orders: FormData.orders,
          rate: FormData.rate,
          comment: FormData.comment,
        });
        return response.data;

      } catch (error) {
        console.error(error);
      }
    },

    async getAllOrder(){
      try {
        await this.getToken();
        const response = await axios.get('/api/get-all-orders');
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },

    async updateAllOrder(id){
      try {
        await this.getToken();
        const response = await axios.post('/api/update-orders/' + id);
        return response.data;
      } catch (error) {
        console.error(error);
      }
    },



  },
});