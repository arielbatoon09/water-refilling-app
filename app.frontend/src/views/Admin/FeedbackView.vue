<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/components/AdminLayout.vue';

const feedbacks = ref([
  { 
    id: 1, 
    date: '2024-10-01', 
    customerName: 'John Doe', 
    product: 'Empty Galoons', 
    rating: 4, 
    comments: 'Good quality, but delivery was slow.', 
    status: 'Reviewed' 
  },
  { 
    id: 2, 
    date: '2024-10-02', 
    customerName: 'Jane Smith', 
    product: '2pcs Refill Galoons', 
    rating: 5, 
    comments: 'Excellent service and product!', 
    status: 'Reviewed' 
  },
  { 
    id: 3, 
    date: '2024-10-03', 
    customerName: 'Alice Johnson', 
    product: 'Galoon Cover', 
    rating: 3, 
    comments: 'Average quality, could be better.', 
    status: 'Pending' 
  },
  { 
    id: 4, 
    date: '2024-10-04', 
    customerName: 'Bob Brown', 
    product: '1pcs Refill Galoon', 
    rating: 5, 
    comments: 'Very satisfied with the purchase.', 
    status: 'Reviewed' 
  },
  { 
    id: 5, 
    date: '2024-10-05', 
    customerName: 'Charlie Davis', 
    product: 'Galoon Cover Seal', 
    rating: 2, 
    comments: 'Not happy with the product quality.', 
    status: 'Pending' 
  },
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

const filteredfeedbacks = computed(() => {
  let filtered = feedbacks.value;

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
  let filtered = feedbacks.value;

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
      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="w-full lg:w-1/6">
          <select class="select select-bordered w-full">
            <option disabled selected>Filter feedbacks</option>
            <option>Han Solo</option>
            <option>Greedo</option>
          </select>
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
              <th class="py-2 px-4 w-1/12 whitespace-nowrap">Feedback ID</th>
              <th class="py-2 px-4">Date</th>
              <th class="py-2 px-4">Customer Name</th>
              <th class="py-2 px-4">Product</th>
              <th class="py-2 px-4">Rating</th>
              <th class="py-2 px-4">Comments</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-4">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="feedback in feedbacks" :key="feedback.id">
              <td class="py-4 px-14">{{ feedback.id }}</td>
              <td class="py-4 px-4">{{ feedback.date }}</td>
              <td class="py-4 px-4">{{ feedback.customerName }}</td>
              <td class="py-4 px-4">{{ feedback.product }}</td>
              <td class="py-4 px-4">{{ feedback.rating }}</td>
              <td class="py-4 px-4">{{ feedback.comments }}</td>
              <td class="py-4 px-4">{{ feedback.status }}</td>
              <td class="py-4 px-4">
                <button class="bg-blue-500 text-white px-4 py-2 rounded">View</button>
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