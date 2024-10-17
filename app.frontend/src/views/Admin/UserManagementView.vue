<script setup>
import { ref, computed } from 'vue';
import AdminLayout from '@/components/AdminLayout.vue';
import SampleImage from '@/assets/inventory/water-galloons.jpg';

const users = ref([
  {
    id: 1,
    username: 'jdoe',
    email: 'jdoe@example.com',
    role: 'Admin',
    status: 'Active',
    lastLogin: '2024-10-10',
    actions: 'Edit/Delete'
  },
  {
    id: 2,
    username: 'asmith',
    email: 'asmith@example.com',
    role: 'User',
    status: 'Inactive',
    lastLogin: '2024-09-15',
    actions: 'Edit/Delete'
  },
  {
    id: 3,
    username: 'bjones',
    email: 'bjones@example.com',
    role: 'Moderator',
    status: 'Active',
    lastLogin: '2024-10-11',
    actions: 'Edit/Delete'
  },
  {
    id: 4,
    username: 'cwhite',
    email: 'cwhite@example.com',
    role: 'User',
    status: 'Pending',
    lastLogin: '2024-08-20',
    actions: 'Edit/Delete'
  },
  {
    id: 5,
    username: 'dgreen',
    email: 'dgreen@example.com',
    role: 'Admin',
    status: 'Active',
    lastLogin: '2024-10-09',
    actions: 'Edit/Delete'
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

const filteredusers = computed(() => {
  let filtered = users.value;

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
  let filtered = users.value;

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
              <th class="py-2 px-4 w-1/12 whitespace-nowrap">User ID</th>
              <th class="py-2 px-4">Username</th>
              <th class="py-2 px-4">Email</th>
              <th class="py-2 px-4">Role</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-4">Last Login</th>
              <th class="py-2 px-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td class="py-4 px-14">{{ user.id }}</td>
              <td class="py-4 px-4">{{ user.username }}</td>
              <td class="py-4 px-4">{{ user.email }}</td>
              <td class="py-4 px-4">{{ user.role }}</td>
              <td class="py-4 px-4">{{ user.status }}</td>
              <td class="py-4 px-4">{{ user.lastLogin }}</td>
              <td class="py-4 px-4">
                <button class="bg-blue-500 text-white px-4 py-2 rounded">Edit</button>
                <button class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
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