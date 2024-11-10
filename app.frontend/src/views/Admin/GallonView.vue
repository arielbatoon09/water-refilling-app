<script setup>
import { ref, onMounted, computed } from 'vue';
import { useGallonStore } from '@/stores/gallons';
import AdminLayout from '@/components/AdminLayout.vue';
import BookDeliveryModal from '@/components/GallonModal.vue';
import EventBus from '@/js/EventBus';

const gallonStore = useGallonStore();
const gallonData = ref({});
const searchQuery = ref('');
const searchInput = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

// Render Gallon List
const renderGallon = async () => {
  const response = await gallonStore.getAllGallon();
  return gallonData.value = response.data;
};

onMounted(() => {
  renderGallon();
  EventBus.on('gallonUpdated', renderGallon);
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="space-x-2 overflow-x-auto flex flex-nowrap">
          <button onclick="bookDelivery.showModal()" class="btn primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M5 12h14m-7 7V5" />
            </svg>
            Add Gallon Type
          </button>

          <!-- Gallon Modal -->
          <dialog id="bookDelivery" class="modal">
            <BookDeliveryModal />
          </dialog>
        </div>

        <!-- Search -->
        <div class="flex items-center gap-2 w-full lg:w-1/4">
          <input v-model="searchInput" type="text" placeholder="Search..." class="px-4 py-3 rounded w-full" />
          <button @click="performSearch" class="btn btn-square primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto w-100">
        <table class="min-w-full bg-white text-left">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="py-2 px-4 w-14 whitespace-nowrap text-center">ID</th>
              <th class="py-2 px-10 text-center">Thumbnail</th>
              <th class="py-2 px-4 text-center">Gallon Name</th>
              <th class="py-2 px-4 text-center">Price</th>
              <th class="py-2 px-4 text-center">Delivery Fee</th>
              <th class="py-2 px-4 text-center">Status</th>
              <th class="py-2 px-1 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="gallon in gallonData" :key="gallon.id">
              <td class="py-4 px-10 text-center">{{ gallon.id }}</td>
              <td class="py-4 px-10 w-[4rem] h-[7rem] overflow-hidden text-center">
                <img class="h-full w-full object-cover" :src="gallon.gallon_image" />
              </td>
              <td class="py-4 px-4 text-center">{{ gallon.gallon_size }}</td>
              <td class="py-4 px-4 text-center">₱{{ gallon.gallon_price }}.00</td>
              <td class="py-4 px-4 text-center">₱{{ gallon.delivery_fee }}.00</td>
              <td class="py-4 px-4 text-center">
                <span :class="[
                  'p-2 rounded',
                  gallon.flag === 1 ? 'bg-green-200 text-green-700' : '',
                  gallon.flag === 0 ? 'bg-orange-200 text-orange-700' : '',
                ]">
                  {{ gallon.flag === 1 ? 'Active' : 'Disabled' }}
                </span>
              </td>
              <td class="text-center">
                <button class="btn btn-ghost">
                  <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                      d="M6 12h.01m6 0h.01m5.99 0h.01" />
                  </svg>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between">
        <!-- <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button> -->
      </div>
    </div>
  </AdminLayout>
</template>