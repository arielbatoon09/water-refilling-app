<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/components/AdminLayout.vue';
import SampleImage from '@/assets/inventory/water-galloons.jpg';

const products = ref([
  { id: 1, img: SampleImage, name: 'Empty Galoons', stock: 50, cost: '₱100.00', status: 'In Stock' },
  { id: 2, img: SampleImage, name: '2pcs Refill Galoons', stock: 10, cost: '₱150.00', status: 'Low Stock' },
  { id: 3, img: SampleImage, name: 'Galoon Cover', stock: 0, cost: '₱200.00', status: 'Out of Stock' },
  { id: 4, img: SampleImage, name: '1pcs Refill Galoon', stock: 20, mop: 'PayMaya', cost: '₱250.00', status: 'In Stock' },
  { id: 5, img: SampleImage, name: 'Galoon Cover Seal', stock: 0, cost: '₱300.00', status: 'Out of Stock' },
]);

const searchQuery = ref('');
const searchInput = ref('');
const currentTab = ref('All Items');
const currentPage = ref(1);
const itemsPerPage = 4;

const tabs = ['All Items', 'In Stock', 'Out of Stock', 'Low Stock'];

// Trigger search when button is clicked
const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const filteredProducts = computed(() => {
  let filtered = products.value;

  if (currentTab.value !== 'All Items') {
    filtered = filtered.filter(product => product.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  let filtered = products.value;

  if (currentTab.value !== 'All Items') {
    filtered = filtered.filter(product => product.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  return Math.ceil(filtered.length / itemsPerPage);
});

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};
</script>


<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Item Button -->
      <button class="btn primary-btn-bg text-white">
        <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M5 12h14m-7 7V5" />
        </svg>
        New Product
      </button>

      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="space-x-2 overflow-x-auto flex flex-nowrap">
          <button v-for="tab in tabs" :key="tab" @click="currentTab = tab"
            :class="['px-4 py-2 rounded whitespace-nowrap', currentTab === tab ? 'primary-btn-bg text-white' : 'bg-gray-200']">
            {{ tab }}
          </button>
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
      <div class="overflow-x-auto w-100 mt-4">
        <table class="min-w-full bg-white text-left">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="py-2 px-4 w-16">Item ID</th>
              <th class="py-2 px-4 w-20">Image</th>
              <th class="py-2 px-4">Product Name</th>
              <th class="py-2 px-4">Stock</th>
              <th class="py-2 px-4">Cost</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-4">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in filteredProducts" :key="product.id">
              <td class="py-4 px-10">{{ product.id }}</td>
              <td class="py-4 px-4 w-20">
                <img :src="SampleImage" />
              </td>
              <td class="py-4 px-4">{{ product.name }}</td>
              <td class="py-4 px-4">{{ product.stock }}</td>
              <td class="py-4 px-4">{{ product.cost }}</td>
              <td class="py-4 px-4">
                <span :class="[ 
                  'p-2 rounded', 
                  product.status === 'In Stock' ? 'bg-green-200 text-green-700 whitespace-nowrap' : '', 
                  product.status === 'Low Stock' ? 'bg-orange-200 text-orange-700 whitespace-nowrap' : '', 
                  product.status === 'Out of Stock' ? 'bg-red-200 text-red-700 whitespace-nowrap' : '' 
                ]">
                  {{ product.status }}
                </span>
              </td>
              <td class="py-4 px-3">
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
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div>
    </div>
  </AdminLayout>
</template>