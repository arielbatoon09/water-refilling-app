<script setup>
import AdminLayout from '@/components/AdminLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { useProductStore } from '@/stores/product';
import Swal from 'sweetalert2';

const selectedCarts = ref(null);
const isModalOpen = ref(false);
const productStore = useProductStore();
const delivery = ref({});
const searchQuery = ref('');
const searchInput = ref('');
const currentTab = ref('All Delivery');
const currentPage = ref(1);
const itemsPerPage = 4;

const cartViewData = ref({
  cart_id: null,
  created_at: null,
  order_quantity: null,
  product_description: null,
  product_imageurl: null,
  product_name: null,
  total_item_price: null,
  updated_at: null
});

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
  // return filtered.slice(start, end);
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

const getAllOrders = async () => {
  try {
    const response = await productStore.getAllOrder();
    delivery.value = response.data;
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const updateStatus = async (id) => {
  const response = await productStore.updateAllOrder(id);
  console.log(response);
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
      updateStatus(id);
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

const openModal = async (id) => {
  cartViewData.value = id;
  isModalOpen.value = true; 
}

function closeModal() {
  isModalOpen.value = false; 
  cartViewData.value = null; 

}

onMounted(() => {
  getAllOrders();
});
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
              <th class="py-2 px-4">Address Delivery</th>
              <th class="py-2 px-4">Phone Number</th>
              <th class="py-2 px-4">Total Order Price</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-1">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in delivery" :key="order.id">
              <td class="py-4 px-10">{{ order.refid }}</td>
              <td class="py-4 px-10">{{ order.user.name }}</td>
              <td class="py-4 px-4"> 0{{order.address.phone_number}} </td>
              <td class="py-4 px-4">{{ order.address.address }}, {{ order.address.municipality }} <br> {{ order.address.city }}, {{ order.address.postal_code }}</td>
              <td class="py-4 px-4"> {{ order.total_price }} </td>
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
                <details class="dropdown dropdown-end">
                  <summary class="btn m-1">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </summary>
                  <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="openModal(order.orders)">View</a></li>
                    <li><a @click="confirmAction(order.refid)">Mark as Delivered</a></li>
                  </ul>
                  <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="modal-box">
                      <form method="dialog">
                        <button ref="closeModal" @click="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
                      </form>
                      <div class="bg-white mt-4 rounded space-y-4 overflow-x-auto">
                        <table class="w-full border-collapse">
                          <tbody>
                            <tr class="border-b" v-for="cart in cartViewData" :key="cart.cart_id">
                              <td class="px-8 py-5 flex items-center gap-3">
                                <div class="h-[3rem] w-[3rem] overflow-hidden rounded-t">
                                  <img class="h-full w-full object-cover object-center" :src="cart.product_imageurl"
                                    alt="Product Image" />
                                </div>
                                <div>
                                  <p class="text-sm text-gray-700 w-full max-w-sm font-medium">{{ cart.product_name }}</p>
                                  <p class="text-sm text-gray-700 w-full max-w-sm font-normal">{{ cart.product_description }}</p>
                                </div>
                              </td>
                              <td class="py-5 text-center text-sm text-gray-700">
                                <span>{{ cart.order_quantity }}</span>
                              </td>
                              <td class="px-8 py-5 text-center text-sm text-gray-700">
                                ₱<span>{{ cart.total_item_price }}</span>.00
                              </td>
                              <td class="px-8 py-5 text-center">
                                <button :class="{ 'disabled': cart.status !== 'To Pay' }" @click="handleRemoveOrder(data.cart_id)"
                                  class="text-red-500 font-medium hover:underline">Remove</button>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <div class="px-8 py-5 flex justify-between gap-1 text-gray-700">
                          
                        </div>
                      </div>
                    </div>
                  </div>
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