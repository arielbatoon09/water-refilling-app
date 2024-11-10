<script setup>
import { ref, onMounted, computed } from 'vue';
import { useGallonStore } from '@/stores/gallons';
import AdminLayout from '@/components/AdminLayout.vue';
import BookDeliveryModal from '@/components/GallonModal.vue';
import EventBus from '@/js/EventBus';

const gallonStore = useGallonStore();
const gallonData = ref({});
const searchInput = ref('');
const filteredGallons = ref({});
const currentPage = ref(1);
const itemsPerPage = 5;

// Fetch Gallon List
const renderGallon = async () => {
  const response = await gallonStore.getAllGallon();
  gallonData.value = response.data || {};
  filteredGallons.value = gallonData.value;
};

const triggerSearch = () => {
  filteredGallons.value = Object.fromEntries(
    Object.entries(gallonData.value).filter(([key, gallon]) =>
      gallon.gallon_size.toLowerCase().includes(searchInput.value.toLowerCase()) ||
      String(gallon.id).includes(searchInput.value)
    )
  );
  currentPage.value = 1;
};

const paginatedGallons = computed(() => {
  const gallonsArray = Object.values(filteredGallons.value);
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return gallonsArray.slice(start, end);
});

// Total pages based on filtered results
const totalPages = computed(() => {
  const gallonsArray = Object.values(filteredGallons.value);
  return Math.ceil(gallonsArray.length / itemsPerPage);
});

// Navigation functions for pagination
const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

// Check if there are any filtered results
const noResultsFound = computed(() => {
  return Object.keys(filteredGallons.value).length === 0;
});

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
        <div class="flex items-center w-full lg:w-1/4">
          <input type="text" placeholder="Search..." class="px-4 py-3 rounded w-full" v-bind:value="searchInput"
            @input="searchInput = $event.target.value" />
          <button @click="triggerSearch" class="btn btn-square rounded-l primary-btn-bg text-white">
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
              <th class="py-4 px-4 w-14 whitespace-nowrap text-center">ID</th>
              <th class="py-4 px-10 text-center">Thumbnail</th>
              <th class="py-4 px-4 text-center">Gallon Name</th>
              <th class="py-4 px-4 text-center">Price</th>
              <th class="py-4 px-4 text-center">Delivery Fee</th>
              <th class="py-4 px-4 text-center">Status</th>
              <th class="py-4 px-1 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="gallon in paginatedGallons" :key="gallon.id">
              <td class="py-4 px-10 text-center">{{ gallon.id }}</td>
              <td class="py-4 px-10 w-[4rem] h-[7rem] overflow-hidden text-center">
                <img class="h-full w-full object-cover" :src="gallon.gallon_image" />
              </td>
              <td class="py-4 px-4 text-center">{{ gallon.gallon_size }}</td>
              <td class="py-4 px-4 text-center">₱{{ gallon.gallon_price }}.00</td>
              <td class="py-4 px-4 text-center">₱{{ gallon.delivery_fee }}.00</td>
              <td class="py-4 px-4 text-center">
                <span
                  :class="['p-2 rounded', gallon.flag === 1 ? 'bg-green-200 text-green-700' : '', gallon.flag === 0 ? 'bg-orange-200 text-orange-700' : '']">
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

        <!-- Search Found -->
        <p v-if="noResultsFound" class="bg-white text-center py-10 text-xl text-gray-600">Search Not Found</p>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between">
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div>
    </div>
  </AdminLayout>
</template>