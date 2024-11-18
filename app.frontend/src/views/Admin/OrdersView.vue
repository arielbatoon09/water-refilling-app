<script setup>
import AdminLayout from '@/components/AdminLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { useProductStore } from '@/stores/product';
import Swal from 'sweetalert2';

const productStore = useProductStore();
const orders = ref([]);
const searchInput = ref('');
const activeModal = ref(null);
const currentTab = ref('All');
const currentPage = ref(1);
const searchQuery = ref('');
const itemsPerPage = 10;

const tabs = ['All', 'To Pay', 'To Receive', 'Delivered', 'Completed', 'Rated'];

const performSearch = () => {
  searchQuery.value = searchInput.value.trim();
  currentPage.value = 1;
};

const filteredOrders = computed(() => {
  const query = searchQuery.value.toLowerCase();
  
  let filtered = orders.value.filter((order) => {
    const matchesTab = currentTab.value === 'All' || order.status === currentTab.value;
    const matchesQuery = query
      ? [
          order.refid,
          order.user.name,
          order.user_role === 'user'
            ? `${order.address.address} ${order.address.municipality} ${order.address.city} ${order.address.postal_code} ${order.address.phone_number}`
            : 'Alexa Water Refilling Station',
          `₱${order.total_price}.00 - ${order.total_items} item(s)`,
          order.status,
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
  const filtered = orders.value.filter((order) => {
    const matchesTab = currentTab.value === 'All' || order.status === currentTab.value;
    const matchesQuery = query
      ? [
          order.refid,
          order.user.name,
          order.user_role === 'user'
            ? `${order.address.address} ${order.address.municipality} ${order.address.city} ${order.address.postal_code} ${order.address.phone_number}`
            : 'Alexa Water Refilling Station',
          `₱${order.total_price}.00 - ${order.total_items} item(s)`,
          order.status,
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

const handleActionModal = (id) => {
  const modal = document.getElementById(`modal_${id}`);

  if (!modal) return;

  if (activeModal.value === id) {
    modal.close();
    activeModal.value = null;
  } else {
    activeModal.value = id;
    modal.showModal();
  }
};

const actionModal = async (id, order) => {
  handleActionModal(id);
};

const getAllOrders = async () => {
  try {
    const response = await productStore.getAllOrder();
    orders.value = response.data;
    console.log(response);
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
      getAllOrders();
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

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
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Ref. ID</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Name</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">MOP</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Delivery Type</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Address</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Phone</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Orders</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Status</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in filteredOrders" :key="order.refid" class="hover:bg-gray-50">
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ order.refid }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ order.user.name }}</td>
              <td class="ppy-2 px-2 sm:py-4 sm:px-4 text-center">{{ order.mop }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ order.deliveryType }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span v-if="order.user_role == 'user'">
                  {{ order.address.address }}, {{ order.address.municipality }} <br> {{ order.address.city }}, {{
                  order.address.postal_code }}
                </span>
                <span v-else>
                  Alexa Water Refilling Station
                </span>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span v-if="order.user_role == 'user'">
                  0{{ order.address.phone_number }}
                </span>
                <span v-else>
                  N/A
                </span>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <p>₱{{ order.total_price }}.00 - {{ order.total_items }} item(s)</p>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span :class="[
                  'p-2 rounded',
                  order.status === 'Completed' ? 'bg-green-200 text-green-700' : '',
                  order.status === 'To Pay' ? 'bg-orange-200 text-orange-700' : '',
                  order.status === 'Delivered' ? 'bg-cyan-200 text-cyan-700' : '',
                  order.status === 'To Receive' ? 'bg-yellow-200 text-yellow-700' : '',
                  order.status === 'Rated' ? 'text-yellow-500 font-medium' : ''
                ]">
                  <span v-if="order.status === 'Rated'" class="flex items-center justify-center gap-1">
                    <svg class="w-[24px] h-[24px] text-yellow-500 -mt-1" aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path
                        d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
                    </svg>
                    {{ order.status }}
                  </span>

                  <span v-else>
                    {{ order.status }}
                  </span>
                </span>
              </td>
              <td class="py-4 px-3 text-center">
                <div class="dropdown dropdown-end">
                  <button class="btn btn-xs sm:btn-sm">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                      width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                        d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </button>
                  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="actionModal(order.refid)">View Orders</a></li>
                    <li :class="{ 'disabled': order.status !== 'To Receive' }">
                      <a @click="confirmAction(order.refid)">Mark as Delivered</a>
                    </li>
                  </ul>
                </div>

                <!-- Orders Details Modal -->
                <dialog :id="'modal_' + order.refid" class="modal">
                  <div class="modal-box">
                    <form method="dialog">
                      <button @click="actionModal(order.refid)"
                        class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
                    </form>
                    <h3 class="text-left text-lg font-bold">Order Details - {{ order.refid }}</h3>

                    <div class="w-full text-left mt-4 flex flex-col gap-4">
                      <!-- {{ cartViewData }} -->
                      <div v-for="carts in order.orders" :key="carts.cart_id">
                        <div class="flex items-center gap-3">
                          <div class="h-[3rem] w-[3rem] overflow-hidden rounded-t">
                            <img class="h-full w-full object-cover object-center" :src="carts.product_imageurl"
                              alt="Product Image" />
                          </div>
                          <div>
                            <p class="text-sm text-gray-700 w-full max-w-sm font-medium">{{ carts.product_name }} - {{
                              carts.order_quantity }} item(s)
                              (₱{{ carts.total_item_price }}.00)</p>
                            <p class="text-sm text-gray-700 w-full max-w-sm font-normal">{{ carts.product_description }}
                            </p>
                          </div>
                        </div>
                      </div>

                      <p>Order Total ({{ order.total_items }} item): <span class="font-bold text-[#56a7dc]">₱{{
                          order.total_price
                          }}.00</span>
                      </p>

                      <button @click="actionModal(order.refid)"
                        class="btn primary-btn-bg text-white w-full">Close</button>
                    </div>
                  </div>
                  <form method="dialog" class="modal-backdrop">
                    <button @click="actionModal(order.refid)">Close</button>
                  </form>
                </dialog>
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