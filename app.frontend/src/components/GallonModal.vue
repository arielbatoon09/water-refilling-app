<script setup>
import { ref } from 'vue';
import { useGallonStore } from '@/stores/gallons';
import Swal from 'sweetalert2';
import EventBus from '@/js/EventBus';

const gallonStore = useGallonStore();
const closeModal = ref(null);
const errorIndicator = ref(false);
const errorMsg = ref(null);

const FormData = ref({
  gallon_image: null,
  gallon_size: null,
  gallon_price: null,
  delivery_fee: null,
});

const handleNewGallon = async () => {
  const response = await gallonStore.addGallonType(FormData.value);
  console.log(response);

  if (response.status === 200) {
    Swal.fire({
      title: 'Perfect!',
      text: response.message,
      icon: 'success',
      confirmButtonText: 'Confirm'
    });

    // Clear fields
    FormData.value.gallon_image = null;
    FormData.value.gallon_size = null;
    FormData.value.gallon_price = null;
    FormData.value.delivery_fee = null;
    errorIndicator.value = false;
    errorMsg.value = null;

    closeModal.value.click();

    EventBus.emit('gallonUpdated');
  } else {
    errorIndicator.value = true;
    errorMsg.value = response.message;
  }
}

const uploadImage = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const imageDataUrl = e.target.result;
    FormData.value.gallon_image = imageDataUrl;
  };

  reader.readAsDataURL(file);
};

</script>

<template>
  <div class="modal-box">
    <form method="dialog">
      <button ref="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
    </form>
    <h3 class="text-lg font-bold">Add New Gallon</h3>
    <!-- Error Indicator -->
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
      Error: {{ errorMsg }}
    </p>

    <!-- Gallon Data Fields -->
    <div class="w-full">
      <!-- Gallon Image -->
      <div class="space-y-1 mt-4">
        <span class="text-gray-700 font-medium">Upload Image</span>
        <input @change="uploadImage" type="file" class="file-input file-input-bordered file-input-md w-full" />
      </div>

      <!-- Gallon Size -->
      <div class="space-y-1 mt-4">
        <span class="text-gray-700 font-medium">Gallon Size</span>
        <input v-model="FormData.gallon_size" type="text" placeholder="Type here" class="input input-bordered w-full" />
      </div>

      <!-- Gallon Price -->
      <div class="space-y-1 mt-4">
        <span class="text-gray-700 font-medium">Price Per Gallon</span>
        <input v-model="FormData.gallon_price" type="text" placeholder="Type here" class="input input-bordered w-full" />
      </div>

      <!-- Gallon Delivery Fee -->
      <div class="space-y-1 mt-4">
        <span class="text-gray-700 font-medium">Delivery Fee Per Gallon</span>
        <input v-model="FormData.delivery_fee" type="text" placeholder="Type here" class="input input-bordered w-full" />
      </div>

      <!-- Action Button -->
      <div class="space-y-2 mt-6">
        <button @click="handleNewGallon" class="btn primary-btn-bg text-white w-full">Add Gallon</button>
      </div>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</template>