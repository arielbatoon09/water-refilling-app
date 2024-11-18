<script setup>
import { ref, computed, onMounted } from 'vue';
import { useSalesStore } from '@/stores/sales';
import AdminLayout from '@/components/AdminLayout.vue';

// Data and state management
const salesData = ref([]);
const salesStore = useSalesStore();
const searchInput = ref('');
const searchQuery = ref('');
const selectedResource = ref('');
const currentPage = ref(1);
const itemsPerPage = 10;

// Perform search when the input changes
const performSearch = () => {
  searchQuery.value = searchInput.value.trim();
  currentPage.value = 1;
};

// Filtered and paginated sales data
const filteredSales = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const selectedRes = selectedResource.value;

  const filtered = salesData.value.filter((sale) => {
    const matchesSearch =
      sale.name.toLowerCase().includes(search) ||
      sale.id.toLowerCase().includes(search) ||
      sale.details.toLowerCase().includes(search) ||
      sale.mop.toLowerCase().includes(search);

    const matchesResource =
      !selectedRes || (selectedRes === 'Refill' && sale.type === 'Refill') || (selectedRes === 'Order' && sale.type === 'Order');

    return matchesSearch && matchesResource;
  });

  // Pagination logic
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return filtered.slice(startIndex, endIndex);
});

// Total pages for pagination
const totalPages = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const selectedRes = selectedResource.value;

  const totalFiltered = salesData.value.filter((sale) => {
    const matchesSearch =
      sale.name.toLowerCase().includes(search) ||
      sale.id.toLowerCase().includes(search) ||
      sale.details.toLowerCase().includes(search) ||
      sale.mop.toLowerCase().includes(search);

    const matchesResource =
      !selectedRes || (selectedRes === 'Refill' && sale.type === 'Refill') || (selectedRes === 'Order' && sale.type === 'Order');

    return matchesSearch && matchesResource;
  });

  return Math.ceil(totalFiltered.length / itemsPerPage);
});

// Fetch sales data from the backend
const renderSales = async () => {
  const response = await salesStore.getAllSuccessSales();
  salesData.value = response.data.map((sale) => ({
    ...sale,
    type: sale.details.includes('Gallon') ? 'Refill' : 'Order',
  }));
};

// Pagination controls
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value += 1;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value -= 1;
  }
};

// Lifecycle hook
onMounted(() => {
  renderSales();
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Filters and Search -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Resource Filter -->
        <div class="w-full lg:w-1/6">
          <select v-model="selectedResource" class="select select-bordered w-full">
            <option value="">All Resources</option>
            <option value="Refill">Refill</option>
            <option value="Order">Order</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex items-center w-full lg:w-1/4">
          <input v-model="searchInput" type="text" placeholder="Search..." class="px-4 py-3 rounded w-full" />
          <button @click="performSearch" class="btn btn-square rounded-l primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="w-full">
        <table class="w-full table-auto bg-white text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50 text-sm md:text-base">
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">User ID</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Customer</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Type</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Reference</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Details</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Recorded Date</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">MOP</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Amount</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="sale in filteredSales" :key="sale.id" class="hover:bg-gray-50">
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.uid }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.name }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.type }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.id }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.details }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.updated_at }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ sale.mop }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">₱{{ sale.amount }}.00</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span class="p-2 rounded bg-green-200 text-green-700">
                  Recorded
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex justify-between">
        <button @click="prevPage" :disabled="currentPage === 1" class="btn px-4 py-2 bg-gray-200 rounded">
          Previous
        </button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages" class="btn px-4 py-2 bg-gray-200 rounded">
          Next
        </button>
      </div>
    </div>
  </AdminLayout>
</template>
