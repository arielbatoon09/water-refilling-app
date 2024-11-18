<script setup>
import { ref, onMounted, computed } from 'vue';
import { useGallonStore } from '@/stores/gallons';
import { useRefillStore } from '@/stores/refill';
import Swal from 'sweetalert2';
import EventBus from '@/js/EventBus';

const gallonStore = useGallonStore();
const refillStore = useRefillStore();
const isOpenGallonDropdown = ref(false);
const selectedGallonOption = ref(null);
const gallonPills = ref([]);
const gallonData = ref({});
const slideState = ref(0);
const no_of_gallon = ref(null);
const closeModal = ref(null);
const errorIndicator = ref(false);
const errorMsg = ref(null);
const today = new Date();
const year = today.getFullYear();
const month = String(today.getMonth() + 1).padStart(2, '0'); 
const day = String(today.getDate()).padStart(2, '0');
const formattedDate = `${year}-${month}-${day}`;

const FormData = ref({
  gallon_details: [],
  delivery_type: 'Walk-In',
  mop: 'Over-The-Counter',
  delivery_date: formattedDate
});

// Render Gallon List
const renderGallon = async () => {
  const response = await gallonStore.getAllGallon();
  return gallonData.value = response.data;
};

// Selected Gallon Option
function selectGallonOption(gallon) {
  selectedGallonOption.value = gallon;
  isOpenGallonDropdown.value = false;
};

// Handle Gallon Pills List
const handleGallonPills = () => {
  if (!selectedGallonOption.value || !selectedGallonOption.value.gallon_size || !no_of_gallon.value) return;
  const noOfGallon = no_of_gallon.value;

  const existingPill = gallonPills.value.find(pill => pill.gallon_size === selectedGallonOption.value.gallon_size);

  if (existingPill) {
    existingPill.no_of_gallon += noOfGallon;
  } else {
    if (gallonPills.value.length < 3) { 
      gallonPills.value.push({
        gallon_size: selectedGallonOption.value.gallon_size,
        gallon_each_price: selectedGallonOption.value.gallon_price,
        gallon_each_dfee: selectedGallonOption.value.delivery_fee,
        no_of_gallon: noOfGallon
      });
      errorIndicator.value = false;
    } else {
      errorIndicator.value = true;
      errorMsg.value = 'You can only add up to 3 different gallon sizes.';
    }
  }
};


// Remove Gallon Pills
const removeGallonPills = (index) => {
  return gallonPills.value.splice(index, 1);
};

// Handle Book Now
const handleBookNow = async () => {
  FormData.value.gallon_details = gallonPills.value;
  console.log(FormData.value);
  const response = await refillStore.bookRefill(FormData.value);
  console.log(response);

  if (response.status === 200) {
    Swal.fire({
      title: 'Perfect!',
      text: response.message,
      icon: 'success',
      confirmButtonText: 'Confirm'
    });

    // Clear Fields
    FormData.value.gallon_details = [];
    FormData.value.delivery_type = 'Walk-In';
    FormData.value.mop = 'Over-The-Counter';
    FormData.value.delivery_date = null;
    slideState.value = 0;
    gallonPills.value = [];
    selectedGallonOption.value = [];
    no_of_gallon.value = null;
    selectedGallonOption.value.gallon_size = 'Select Gallon Size';
    errorIndicator.value = false;

    closeModal.value.click();

    // Emit an event to notify other components
    EventBus.emit('refillUpdated');
  } else {
    errorIndicator.value = true;
    errorMsg.value = 'Make sure fill up the booking details';
  }
};

onMounted(() => {
  renderGallon();
});
</script>

<template>
  <div class="modal-box">
    <form method="dialog">
      <button ref="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
    </form>
    <h3 class="text-lg font-bold">Book Delivery</h3>
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

    <!-- Delivery Data Fields -->
    <div class="w-full">
      <!-- Gallon Sizes & Quanity Pills -->
      <div class="mt-4 flex flex-wrap gap-2]" v-if="gallonPills.length > 0">
        <div v-for="(pill, index) in gallonPills" :key="index" class="badge bg-gray-200 hover:bg-gray-300 cursor-pointer py-3 gap-2 mr-1 mb-1">
          <svg @click="removeGallonPills(index)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            class="inline-block h-4 w-4 stroke-current cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
          <span>{{ pill.gallon_size }} ({{ pill.no_of_gallon }}pcs)</span>
        </div>
      </div>
      <div class="slide-1">
        <!-- Gallon Sizes -->
        <div class="space-y-1 mt-5">
          <span class="text-gray-700 font-medium">Gallon Sizes</span>
          <div class="relative">
            <button @click="isOpenGallonDropdown = !isOpenGallonDropdown"
              class="select select-bordered w-full text-left flex flex-col justify-center">
              {{ selectedGallonOption ? selectedGallonOption.gallon_size : 'Select Gallon Size' }}
            </button>

            <div v-if="isOpenGallonDropdown"
              class="absolute w-full mt-1 bg-white border border-gray-300 rounded shadow-lg z-10 h-[8rem] overflow-y-auto">
              <div v-for="gallon in gallonData" :key="gallon.id" @click="selectGallonOption(gallon)"
                class="px-4 py-2 cursor-pointer hover:bg-gray-100 flex items-center space-x-2">
                <img :src="gallon.gallon_image" alt="" class="w-6 h-6" />
                <span>{{ gallon.gallon_size }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Number of Gallon/s -->
        <div class="space-y-1 mt-4">
          <span class="text-gray-700 font-medium">Number of Gallon/s</span>
          <input v-model="no_of_gallon" type="number" placeholder="Enter number" class="input input-bordered w-full" />
        </div>

        <!-- Add to List Button -->
        <div class="space-y-1 mt-1.5">
          <span @click="handleGallonPills" class="text-[#094b76] text-sm transition cursor-pointer"><strong>+
            </strong><span class="hover:underline">Add To List</span></span>
        </div>
      </div>

      <div class="space-y-2 mt-6">
        <button @click="handleBookNow" class="btn primary-btn-bg text-white w-full">Book Now</button>
      </div>
    </div>
  </div>
  <form method="dialog" class="modal-backdrop">
    <button>close</button>
  </form>
</template>