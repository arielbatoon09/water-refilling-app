<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useProductStore } from '@/stores/product';
import DashboardLayout from '@/components/DashboardLayout.vue';
import Swal from 'sweetalert2';

const productStore = useProductStore();
const router = useRouter();
const cartData = ref([]);
const quantities = ref({});
const selectedData = ref([]);
const mop = ref('Please select');
const deliveryType = ref('Select delivery type');
const closeModal = ref(null);
const errorIndicator = ref(false);
const errorMsg = ref('Make sure to select your order/s.');

const initializeQuantity = (id, order_quantity) => {
  if (!(id in quantities.value)) {
    quantities.value[id] = order_quantity;
  }
};

const quantityValueControl = async (action, data) => {
  initializeQuantity(data.cart_id, data.order_quantity);

  if (action === 'decrement' && quantities.value[data.cart_id] > 1) {
    quantities.value[data.cart_id]--;

    // update backend quantity
    await productStore.updateCartOrderQuantity(data.cart_id, quantities.value[data.cart_id]);
  } else if (action === 'increment' && quantities.value[data.cart_id] < data.product_stocks) {
    quantities.value[data.cart_id]++;

    // update backend quantity
    await productStore.updateCartOrderQuantity(data.cart_id, quantities.value[data.cart_id]);
  }
};

const renderCartData = async () => {
  const response = await productStore.getAllUIDCart();
  console.log(response.data);
  cartData.value = response.data;
};

const handleSelectData = (data, isChecked) => {
  if (isChecked) {
    if (!selectedData.value.includes(data)) {
      selectedData.value.push(data);
      console.log(selectedData.value);
    }
  } else {
    selectedData.value = selectedData.value.filter(item => item !== data);
    console.log(selectedData.value);
  }
};

const allItemsTotalPrice = computed(() => {
  return selectedData.value.reduce((total, item) => {
    return total + item.unit_price * item.order_quantity;
  }, 0);
});

const handleRemoveCartId = async (id) => {
  const response = await productStore.removeCartOrderById(id);
  console.log(response);
  selectedData.value = [];
  renderCartData();
};

const handleCheckout = async () => {
  if (selectedData.value.length === 0) return;

  const response = await productStore.checkoutCart(selectedData.value, mop.value, deliveryType.value);
  console.log(response);

  if (response.status === 200) {
    selectedData.value = [];
    mop.value = 'Please select';
    closeModal.value.click();

    Swal.fire({
      title: 'Perfect!',
      text: response.message,
      icon: 'success',
      confirmButtonText: 'Confirm'
    });

    router.push('/purchase');
  } else if(response.status == 409){
    errorIndicator.value = true;
    errorMsg.value = 'Make sure you have an addresses!';
  }
  
};

onMounted(() => {
  renderCartData();
});
</script>

<template>
  <DashboardLayout>
    <div class="pt-4 lg:pt-8 lg:px-24 2xl:px-40 pb-10">
      <!-- Page Header -->
      <div class="flex items-center gap-2 mb-6">
        <svg class="w-[38px] h-[38px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">My Cart</h2>
      </div>

      <!-- Cart Data List -->
      <div class="bg-white mt-4 rounded space-y-4 overflow-x-auto">

        <table class="w-full border-collapse">
          <!-- Table Header -->
          <thead>
            <tr class="border-b">
              <th class="px-8 py-5 text-left text-gray-700 font-medium">Product</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Unit Price</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Quantity</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Total Price</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Actions</th>
            </tr>
          </thead>

          <tbody>
            <!-- Product Column -->
            <tr class="border-b" v-for="data in cartData" :key="data.cart_id">
              <td class="px-8 py-5 flex items-center gap-3">
                <input @change="handleSelectData(data, $event.target.checked)" type="checkbox" class="checkbox" />
                <div class="h-[3rem] w-[3rem] overflow-hidden rounded-t">
                  <img class="h-full w-full object-cover object-center" :src="data.product_imageurl"
                    alt="Product Image" />
                </div>
                <div>
                  <p class="text-sm text-gray-700 w-full font-medium max-w-sm">{{ data.product_name }}</p>
                  <p class="text-sm text-gray-700 w-full font-normal max-w-sm">{{ data.product_description }}</p>
                </div>
              </td>

              <!-- Unit Price Column -->
              <td class="px-8 py-5 text-center">
                ₱<span>{{ data.unit_price }}</span>.00
              </td>

              <!-- Quantity Column -->
              <td class="px-8 py-5">
                <div class="flex items-center justify-center">
                  <div @click="quantityValueControl('decrement', data)"
                    class="border px-2 py-1 text-gray-500 font-bold text-base hover:bg-[#f5f8fa] transition ease-linear cursor-pointer">
                    -
                  </div>
                  <div class="border-t border-b px-3 py-1 text-gray-500 text-base text-center">
                    {{ quantities[data.cart_id] ?? data.order_quantity }}
                  </div>
                  <div @click="quantityValueControl('increment', data)"
                    class="border px-2 py-1 text-gray-500 font-bold text-base hover:bg-[#f5f8fa] transition ease-linear cursor-pointer">
                    +
                  </div>
                </div>
              </td>

              <!-- Total Price Column -->
              <td class="px-8 py-5 text-center text-[#56a7dc] font-medium">
                ₱<span>{{ (quantities[data.cart_id] || data.order_quantity) * data.unit_price }}</span>.00
              </td>

              <!-- Actions Column -->
              <td class="px-8 py-5 text-center">
                <button @click="handleRemoveCartId(data.cart_id)"
                  class="text-red-500 font-medium hover:underline">Remove</button>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="px-8 py-5 space-x-4">
          <button onclick="checkoutModal.showModal()"
            class="text-sm primary-btn-bg py-2.5 px-6 rounded text-white active:scale-105 transition-transform ease-linear">Check
            Out</button>

          <span class="text-gray-600">Total ({{ selectedData.length }} item): ₱<span>{{ allItemsTotalPrice
              }}</span>.00</span>
        </div>

        <!-- Checkout Modal Modal -->
        <dialog id="checkoutModal" class="modal">
          <div class="modal-box">
            <form method="dialog">
              <button ref="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
            </form>
            <h3 class="text-lg font-bold">Mode of Payment</h3>
            <!-- Error Indicator -->
            <p v-show="selectedData.length === 0" class="text-red-500 flex items-center gap-1 mt-1">
              <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#e86868">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <circle cx="12" cy="17" r="1" fill="#e86868"></circle>
                  <path d="M12 10L12 14" stroke="#e86868" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                  </path>
                  <path
                    d="M3.44722 18.1056L10.2111 4.57771C10.9482 3.10361 13.0518 3.10362 13.7889 4.57771L20.5528 18.1056C21.2177 19.4354 20.2507 21 18.7639 21H5.23607C3.7493 21 2.78231 19.4354 3.44722 18.1056Z"
                    stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
              </svg>
              Error: Make sure to select your order/s.
            </p>

            <p v-show="errorIndicator" class="text-red-500 flex items-center gap-1 mt-1">
              <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#e86868">
                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                <g id="SVGRepo_iconCarrier">
                  <circle cx="12" cy="17" r="1" fill="#e86868"></circle>
                  <path d="M12 10L12 14" stroke="#e86868" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                  </path>
                  <path
                    d="M3.44722 18.1056L10.2111 4.57771C10.9482 3.10361 13.0518 3.10362 13.7889 4.57771L20.5528 18.1056C21.2177 19.4354 20.2507 21 18.7639 21H5.23607C3.7493 21 2.78231 19.4354 3.44722 18.1056Z"
                    stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </g>
              </svg>
              Error: {{ errorMsg }}
            </p>

            <!-- Field -->
            <div class="space-y-2 mt-5">
              <span class="text-gray-700 font-medium ml-1">Select Payment Method</span>
              <select v-model="mop" class="select select-bordered w-full">
                <option disabled selected>Please select</option>
                <option>COD</option>
                <option>Online Pay</option>
              </select>
            </div>

            <div class="space-y-2 mt-3">
              <span class="text-gray-700 font-medium ml-1">Delivery Type</span>
              <select v-model="deliveryType" class="select select-bordered w-full">
                <option disabled selected>Select delivery type</option>
                <option>Walk-In</option>
                <option>Door-To-Door</option>
              </select>
            </div>

            <!-- Proceed Button -->
            <div class="space-y-2 mt-6">
              <button @click="handleCheckout" class="btn primary-btn-bg text-white w-full">Proceed</button>
            </div>
          </div>
          <form method="dialog" class="modal-backdrop">
            <button>close</button>
          </form>
        </dialog>
      </div>
    </div>
  </DashboardLayout>
</template>