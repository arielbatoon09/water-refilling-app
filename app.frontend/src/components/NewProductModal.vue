<script setup>
import { ref } from 'vue';
import { useProductStore } from '@/stores/product';
import Swal from 'sweetalert2';
import EventBus from '@/js/EventBus';

const productStore = useProductStore();
const errorIndicator = ref(false);

const FormData = ref({
  product_name: null,
  stock: null,
  product_image: null,
  description: null,
  cost: null,
});

const handleImage = (event) => {
  const file = event.target.files[0];
  if (file) {
    FormData.value.product_image  = file.name;
  } else {
    FormData.value.product_image  = null;
    productImagePreview.value = null;
  }
};

const addNewProduct = async () => {
  const response = await productStore.addProduct(FormData.value);

  if(response.status === 200){
    FormData.value.product_name = null;
    FormData.value.stock = null;
    FormData.value.product_image = null;
    FormData.value.description = null;
    FormData.value.cost = null;
  }

}

const uploadImage = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const imageDataUrl = e.target.result;
    FormData.value.product_image = imageDataUrl;
  };

  reader.readAsDataURL(file);
};


</script>

<template>
  <div class="modal-box">
    <form method="dialog">
      <button ref="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
    </form>
    <h3 class="text-lg font-bold">New Product</h3>
    <p v-if="errorIndicator" class="text-red-500 flex items-center gap-1 mt-1">
      <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#e86868">
        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
        <g id="SVGRepo_iconCarrier">
          <circle cx="12" cy="17" r="1" fill="#e86868"></circle>
          <path d="M12 10L12 14" stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          </path>
          <path
            d="M3.44722 18.1056L10.2111 4.57771C10.9482 3.10361 13.0518 3.10362 13.7889 4.57771L20.5528 18.1056C21.2177 19.4354 20.2507 21 18.7639 21H5.23607C3.7493 21 2.78231 19.4354 3.44722 18.1056Z"
            stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </g>
      </svg>
      Error: Make sure to fill up all the product details
    </p>

    <div class="w-full">

      <div class="slide-1">
        <div class="space-y-1 mt-5">
          <span class="text-gray-700 font-medium">Product Image</span>
          <div class="relative">
            <input type="file" @change="uploadImage" class="file-input file-input-bordered w-full" />
          </div>
        </div>

        <div class="space-y-1 mt-5">
          <span class="text-gray-700 font-medium">Product Name</span>
          <div class="relative">
            <input v-model="FormData.product_name" type="text" placeholder="Enter product name" class="input input-bordered w-full" />
          </div>
        </div>

        <div class="space-y-1 mt-4">
          <span class="text-gray-700 font-medium">Product Stock</span>
          <input v-model="FormData.stock" type="number" placeholder="Enter number" class="input input-bordered w-full" />
        </div>

        <div class="space-y-1 mt-4">
          <span class="text-gray-700 font-medium">Product Cost</span>
          <input v-model="FormData.cost" type="number" placeholder="Enter number" class="input input-bordered w-full" />
        </div>

        <div class="space-y-1 mt-4">
          <span class="text-gray-700 font-medium">Product Description</span>
          <textarea v-model="FormData.description" rows="10" cols="10" placeholder="Enter a product description" class="input input-bordered w-full" ></textarea>
        </div>

        <div class="space-y-1 mt-4">
          <button class="btn w-full primary-btn-bg text-white" @click="addNewProduct">
            New Product
          </button>
        </div>
      </div>

      </div>

  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</template>