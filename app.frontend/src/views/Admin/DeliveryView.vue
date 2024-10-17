<script setup>
import AdminLayout from '@/components/AdminLayout.vue';
import { ref, computed } from 'vue';

const delivery = ref([
  { id: 1, status: 'Delivered', customer: 'John Doe', order: 'Empty Galoons', deliveryDate: 'Oct. 5, 2024', address: 'Day-as, Cordova' },
  { id: 2, status: 'Pending', customer: 'Jane Smith', order: '2pcs Refill Galoons', deliveryDate: 'Oct. 5, 2024', address: 'Day-as, Cordova' },
  { id: 3, status: 'Failed', customer: 'Mike Johnson', order: 'Galoon Cover', deliveryDate: 'Oct. 5, 2024', address: 'Day-as, Cordova' },
  { id: 4, status: 'Delivered', customer: 'Chris Lee', order: '1pcs Refill Galoon', deliveryDate: 'Oct. 5, 2024', address: 'Day-as, Cordova' },
  { id: 5, status: 'Pending', customer: 'Sara Wilson', order: 'Galoon Cover Seal', deliveryDate: 'Oct. 5, 2024', address: 'Day-as, Cordova' },
]);

const searchQuery = ref('');
const searchInput = ref('');
const currentTab = ref('All Delivery');
const currentPage = ref(1);
const itemsPerPage = 4;

const tabs = ['All Delivery', 'Delivered', 'Pending', 'Failed'];

// Trigger search when button is clicked
const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const filteredDelivery = computed(() => {
  let filtered = delivery.value;

  if (currentTab.value !== 'All Delivery') {
    filtered = filtered.filter(order => order.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(order =>
      order.customer.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  let filtered = delivery.value;

  if (currentTab.value !== 'All Delivery') {
    filtered = filtered.filter(order => order.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(order =>
      order.customer.toLowerCase().includes(searchQuery.value.toLowerCase())
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

      <!-- Tabs & Search -->
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
      <div class="overflow-x-auto w-100">
        <table class="min-w-full bg-white text-left">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="py-2 px-4 w-14 whitespace-nowrap">Order ID</th>
              <th class="py-2 px-10">Customer</th>
              <th class="py-2 px-4">Order</th>
              <th class="py-2 px-4">Delivery Date</th>
              <th class="py-2 px-4">Delivery Address</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-1">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredDelivery" :key="order.id">
              <td class="py-4 px-10">{{ order.id }}</td>
              <td class="py-4 px-10">{{ order.customer }}</td>
              <td class="py-4 px-4">{{ order.order }}</td>
              <td class="py-4 px-4">{{ order.deliveryDate }}</td>
              <td class="py-4 px-4">{{ order.address }}</td>
              <td class="py-4 px-4">
                <span :class="[
                  'p-2 rounded',
                  order.status === 'Delivered' ? 'bg-green-200 text-green-700' : '',
                  order.status === 'Pending' ? 'bg-orange-200 text-orange-700' : '',
                  order.status === 'Failed' ? 'bg-red-200 text-red-700' : '',
                ]">
                  {{ order.status }}
                </span>
              </td>
              <td>
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