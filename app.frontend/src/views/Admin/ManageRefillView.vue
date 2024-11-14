<script setup>
import AdminLayout from '@/components/AdminLayout.vue';
import { useRefillStore } from '@/stores/refill';
import { ref, computed, onMounted } from 'vue';
import manualAddRefill from '@/components/ManualRefillModal.vue';
import Swal from 'sweetalert2';

const refills = ref([]);
const refillStore = useRefillStore();
const searchQuery = ref('');
const searchInput = ref('');
const currentTab = ref('All refills');
const currentPage = ref(1);
const itemsPerPage = 4;

const tabs = ['All refills', 'Completed', 'Pending', 'Cancelled', 'Reviewed'];

const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const filteredrefills = computed(() => {
  let filtered = refills.value;

  if (currentTab.value !== 'All refills') {
    filtered = filtered.filter(Refill => Refill.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(Refill =>
      Refill.customer.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  let filtered = refills.value;

  if (currentTab.value !== 'All refills') {
    filtered = filtered.filter(Refill => Refill.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(Refill =>
      Refill.customer.toLowerCase().includes(searchQuery.value.toLowerCase())
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

const getAllRefills = async () => {
  try {
    const response = await refillStore.getAllRefill();
    refills.value = response.data;
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const changeToDelivered = async (id) => {
  try {
    const response = await refillStore.changeStatus(id);
    
    if(response.status == 200){
      Swal.fire('success', 'Delivered!', 'success');
    }else{
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
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

onMounted(() => {
  getAllRefills();
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Refill Button -->
      <button onclick="manualRefill.showModal()" class="btn primary-btn-bg text-white" >
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
            <tr class="bRefill-b bRefill-gray-200">
              <th class="py-2 px-4 w-14 whitespace-nowrap">Refill ID</th>
              <th class="py-2 px-10">Customer</th>
              <th class="py-2 px-4">Refill Details</th>
              <th class="py-2 px-4">Refill Type</th>
              <th class="py-2 px-4">Payment Method</th>
              <th class="py-2 px-4">Address</th>
              <th class="py-2 px-4">Phone Number</th>
              <th class="py-2 px-4">Total Fee</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-1">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="Refill in filteredrefills" :key="Refill.id">
              <td class="py-4 px-10">{{ Refill.id }}</td>
              <td class="py-4 px-10">{{ Refill.user_role }}</td>
              <td class="py-4 px-4">
                <span v-for="(gallon, index) in Refill.gallon_details" :key="index">
                  {{ gallon.gallon_size }} - {{ gallon.no_of_gallon }}
                  <span v-if="index < Refill.gallon_details.length - 1">, </span>
                </span>
              </td>
              <td class="py-4 px-4">{{ Refill.delivery_type }}</td>
              <td class="py-4 px-4">{{ Refill.mop }}</td>
              <td class="py-4 px-4">
                <span v-if="Refill.user_role == 'user'">
                  <small>{{ Refill.address.address }}, {{ Refill.address.municipality }} <br> {{ Refill.address.city }}, {{ Refill.address.postal_code }}</small> 
                </span>
                <span v-else>
                  Alexa Water Refilling Station
                </span>
              </td>
              <td class="py-4 px-4">
                <span v-if="Refill.user_role == 'user'">
                  {{ Refill.address.phone_number }} 
                </span>
                <span v-else>
                  Alexa Water Refilling Station
                </span>
              </td>
              <td class="py-4 px-4">₱{{ Refill.t_overall_fee }}.00</td>
              <td class="py-4 px-4">
                <span :class="[
                  'p-2 rounded',
                  Refill.status === 'Completed' ? 'bg-green-200 text-green-700' : '',
                  Refill.status === 'Pending' ? 'bg-orange-200 text-orange-700' : '',
                  Refill.status === 'Cancelled' ? 'bg-red-200 text-red-700' : '',
                  Refill.status === 'Reviewed' ? 'bg-yellow-200 text-yellow-700' : ''
                ]">
                  {{ Refill.status }}
                </span>
              </td>
              <td>
                <details class="dropdown dropdown-end">
                  <summary class="btn m-1">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </summary>
                  <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="confirmAction(Refill.id)">Mark as Delivered</a></li>
                  </ul>
                </details>
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