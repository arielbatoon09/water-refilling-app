<script setup>
import AdminLayout from '@/components/AdminLayout.vue';
import { ref, computed } from 'vue';

const orders = ref([
  { id: 1, status: 'Completed', customer: 'John Doe', order: 'Empty Galoons', orderType: 'Delivery', mop: 'Over-The-Counter', cost: '₱100.00' },
  { id: 2, status: 'Pending', customer: 'Jane Smith', order: '2pcs Refill Galoons', orderType: 'Walk-In', mop: 'G-Cash ', cost: '₱150.00' },
  { id: 3, status: 'Cancelled', customer: 'Mike Johnson', order: 'Galoon Cover', orderType: 'Walk-In', mop: 'G-Cash', cost: '₱200.00' },
  { id: 4, status: 'Reviewed', customer: 'Chris Lee', order: '1pcs Refill Galoon', orderType: 'Walk-In', mop: 'PayMaya', cost: '₱250.00' },
  { id: 5, status: 'Reviewed', customer: 'Sara Wilson', order: 'Galoon Cover Seal', orderType: 'Walk-In', mop: 'Over the counter', cost: '₱300.00' },
]);

const searchQuery = ref('');
const searchInput = ref('');
const currentTab = ref('All Orders');
const currentPage = ref(1);
const itemsPerPage = 4;

const tabs = ['All Orders', 'Completed', 'Pending', 'Cancelled', 'Reviewed'];

// Trigger search when button is clicked
const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const filteredOrders = computed(() => {
  let filtered = orders.value;

  if (currentTab.value !== 'All Orders') {
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
  let filtered = orders.value;

  if (currentTab.value !== 'All Orders') {
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
      <!-- Add Order Button -->
      <button class="btn primary-btn-bg text-white">
        <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M5 12h14m-7 7V5" />
        </svg>
        Manual Order
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
      <div class="overflow-x-auto w-100">
        <table class="min-w-full bg-white text-left">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="py-2 px-4 w-14 whitespace-nowrap">Order ID</th>
              <th class="py-2 px-10">Customer</th>
              <th class="py-2 px-4">Order</th>
              <th class="py-2 px-4">Order Type</th>
              <th class="py-2 px-4">Payment Method</th>
              <th class="py-2 px-4">Cost</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-1">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredOrders" :key="order.id">
              <td class="py-4 px-10">{{ order.id }}</td>
              <td class="py-4 px-10">{{ order.customer }}</td>
              <td class="py-4 px-4">{{ order.order }}</td>
              <td class="py-4 px-4">{{ order.orderType }}</td>
              <td class="py-4 px-4">{{ order.mop }}</td>
              <td class="py-4 px-4">{{ order.cost }}</td>
              <td class="py-4 px-4">
                <span :class="[
                  'p-2 rounded',
                  order.status === 'Completed' ? 'bg-green-200 text-green-700' : '',
                  order.status === 'Pending' ? 'bg-orange-200 text-orange-700' : '',
                  order.status === 'Cancelled' ? 'bg-red-200 text-red-700' : '',
                  order.status === 'Reviewed' ? 'bg-yellow-200 text-yellow-700' : ''
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