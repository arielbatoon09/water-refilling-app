<script setup>
import AdminLayout from '@/components/AdminLayout.vue';
import { useRefillStore } from '@/stores/refill';
import { ref, computed, onMounted } from 'vue';
import ManualAddRefill from '@/components/ManualRefillModal.vue';
import Swal from 'sweetalert2';
import EventBus from '@/js/EventBus';

const refills = ref([]);
const refillStore = useRefillStore();
const searchInput = ref('');
const searchQuery = ref('');
const currentTab = ref('All');
const currentPage = ref(1);
const itemsPerPage = 10;

const tabs = ['All', 'Waiting Delivery', 'Pending Payment', 'Delivered', 'Completed'];

const performSearch = () => {
  searchQuery.value = searchInput.value.trim();
  currentPage.value = 1;
};

const filteredRefills = computed(() => {
  const query = searchQuery.value.toLowerCase();
  let filtered = refills.value.filter((refill) => {
    const matchesTab = currentTab.value === 'All' || refill.status === currentTab.value;
    const matchesQuery = query
      ? [
        refill.id,
        refill.name,
        refill.gallon_details.map(gallon => `${gallon.gallon_size} ${gallon.no_of_gallon}`).join(', '),
        refill.delivery_type,
        refill.mop,
        refill.user_role === 'user'
          ? `${refill.address.address} ${refill.address.municipality} ${refill.address.city} ${refill.address.postal_code} ${refill.address.phone_number}`
          : 'Alexa Water Refilling Station',
        refill.t_overall_fee,
        refill.status,
      ]
        .join(' ')
        .toLowerCase()
        .includes(query)
      : true;

    return matchesTab && matchesQuery;
  });

  const start = (currentPage.value - 1) * itemsPerPage;
  return filtered.slice(start, start + itemsPerPage);
});

const totalPages = computed(() => {
  const query = searchQuery.value.toLowerCase();
  const filtered = refills.value.filter((refill) => {
    const matchesTab = currentTab.value === 'All' || refill.status === currentTab.value;
    const matchesQuery = query
      ? [
        refill.id,
        refill.name,
        refill.gallon_details.map(gallon => `${gallon.gallon_size} ${gallon.no_of_gallon}`).join(', '),
        refill.delivery_type,
        refill.mop,
        refill.user_role === 'user'
          ? `${refill.address.address} ${refill.address.municipality} ${refill.address.city} ${refill.address.postal_code} ${refill.address.phone_number}`
          : 'Alexa Water Refilling Station',
        refill.t_overall_fee,
        refill.status,
      ]
        .join(' ')
        .toLowerCase()
        .includes(query)
      : true;

    return matchesTab && matchesQuery;
  });

  return Math.ceil(filtered.length / itemsPerPage);
});

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const getAllRefills = async () => {
  try {
    const response = await refillStore.getAllRefill();
    console.log(response);
    refills.value = response.data;
  } catch (error) {
    console.error('Failed to fetch refills:', error);
  }
};

const changeToDelivered = async (id) => {
  try {
    const response = await refillStore.changeStatus(id);

    if (response.status == 200) {
      Swal.fire('success', 'Delivered!', 'success');
    } else {
      console.log(response);
    }
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const confirmAction = (id) => {
  Swal.fire({
    title: 'Are you sure?',
    text: 'This action cannot be undone!',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, do it!',
    cancelButtonText: 'No, cancel!',
    reverseButtons: true,
  }).then((result) => {
    if (result.isConfirmed) {
      changeToDelivered(id);
      getAllRefills();
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

onMounted(() => {
  getAllRefills();
  EventBus.on('refillUpdated', getAllRefills);
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Refill Button -->
      <button onclick="manualRefill.showModal()" class="btn primary-btn-bg text-white">
        <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M5 12h14m-7 7V5" />
        </svg>
        Manual Refill
      </button>

      <dialog id="manualRefill" class="modal">
        <manualAddRefill />
      </dialog>

      <!-- Add Refill & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="space-x-2 overflow-x-auto flex flex-nowrap">
          <button v-for="tab in tabs" :key="tab" @click="currentTab = tab"
            :class="['px-4 py-2 rounded whitespace-nowrap', currentTab === tab ? 'primary-btn-bg text-white' : 'bg-gray-200']">
            {{ tab }}
          </button>
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
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center whitespace-nowrap">ID</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center whitespace-nowrap">Name</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Details</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Delivery Type</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Delivery Date</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">MOP</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Address</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Phone</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Total Fee</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Status</th>
              <th class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="Refill in filteredRefills" :key="Refill.id" class="hover:bg-gray-50">
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center whitespace-nowrap">{{ Refill.id }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ Refill.name }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center">
                <span v-for="(gallon, index) in Refill.gallon_details" :key="index">
                  {{ gallon.gallon_size }} - {{ gallon.no_of_gallon }} Gallon(s)
                  <span v-if="index < Refill.gallon_details.length - 1">, </span>
                </span>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center">{{ Refill.delivery_type }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center">{{ Refill.delivery_date }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center">{{ Refill.mop }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-normal">
                <span v-if="Refill.user_role === 'user'">
                  {{ Refill.address.address }}, {{ Refill.address.municipality }} <br>
                  {{ Refill.address.city }}, {{ Refill.address.postal_code }}
                </span>
                <span v-else>
                  Alexa Water Refilling Station
                </span>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center">
                <span v-if="Refill.user_role === 'user'">
                  0{{ Refill.address.phone_number }}
                </span>
                <span v-else>
                  N/A
                </span>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center whitespace-nowrap">₱{{ Refill.t_overall_fee }}.00</td>
              <td class="py-2 px-2 sm:py-4 sm:px-6 text-center">
                <span :class="['p-2 rounded',
                  Refill.status === 'Completed' ? 'bg-green-200 text-green-700' : '',
                  Refill.status === 'Pending Payment' ? 'bg-orange-200 text-orange-700' : '',
                  Refill.status === 'Delivered' ? 'bg-cyan-200 text-cyan-700' : '',
                  Refill.status === 'Waiting Delivery' ? 'bg-yellow-200 text-yellow-700' : ''
                ]">
                  {{ Refill.status }}
                </span>
              </td>
              <td class="text-center">
                <div class="dropdown dropdown-end">
                  <button class="btn btn-xs sm:btn-sm">
                    <svg class="w-4 h-4 sm:w-6 sm:h-6 text-gray-700" xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                        d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </button>
                  <ul class="dropdown-content menu bg-base-100 rounded-box z-[1] w-40 p-2 shadow">
                    <li :class="{ 'disabled': Refill.status !== 'Waiting Delivery' }">
                      <a @click="confirmAction(Refill.id)">Mark as Delivered</a>
                    </li>
                  </ul>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
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