<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useProductStore } from '@/stores/product';
import DashboardLayout from '@/components/DashboardLayout.vue';

const productStore = useProductStore();
const activeModal = ref(null);
const router = useRouter();
const productData = ref([]);
const quantities = ref({});
const selectedData = ref([]);
const mop = ref('Please select');
const errorIndicator = ref(false);
const errorMsg = ref(null);
const deliveryType = ref('Select delivery type');
const isModalOpen = ref(false);
import Swal from 'sweetalert2';

const FormData = ref({
  product_id: null,
  order_quantity: null,
  unit_price: null
});

const initializeQuantity = (id) => {
  if (!(id in quantities.value)) {
    quantities.value[id] = 1;
  }
};

const quantityValueControl = (action, data) => {
  initializeQuantity(data.id);

  if (action === 'decrement' && quantities.value[data.id] > 1) {
    quantities.value[data.id]--;
  } else if (action === 'increment' && quantities.value[data.id] < data.item_stocks) {
    quantities.value[data.id]++;
  }
};

const handleAddToCart = async (data) => {
  FormData.value.product_id = data.id;
  FormData.value.order_quantity = quantities.value[data.id] ?? 1;
  FormData.value.unit_price = data.item_price;

  const response = await productStore.addToCart(FormData.value);
  console.log(response);

  if (response.status === 200) {
    quantities.value[data.id] = 1;
    FormData.value.product_id = null;
    FormData.value.order_quantity = null;
    FormData.value.unit_price = null;

    router.push('/cart');
  }
};

const buynow = async (data) => {

  const transformedData = {
    ...data,
    unit_price: data.item_price,
    product_id: data.id,
    order_quantity: quantities.value[data.id] ?? 1,
  };
  // openModal();
  selectedData.value.push(transformedData);
  // document.getElementById('checkoutModal').showModal();

  const modal = document.getElementById(`modal_${data.id}`);
  if(!modal) return;

  if(activeModal.value === data.id){
    modal.close();
    activeModal.value = null;
  }else{
    activeModal.value = data.id;
    modal.showModal();
  }
}

const actionModal = (id) => {
  const modal = document.getElementById(`modal_${id}`);
  if(!modal) return;

  if(activeModal.value === id){
    modal.close();
    activeModal.value = null;
  }else{
    activeModal.value = id;
    modal.showModal();
  }
}

const handleBuyNow = async () => {
  const data = selectedData.value;
  try {
    FormData.value.product_id = data[0].id;
    FormData.value.order_quantity = quantities.value[data[0].id] ?? 1;
    FormData.value.unit_price = data[0].item_price;

    const response = await productStore.addToCart(FormData.value);
    console.log(response);

    if (response.status === 200) {
      purchaseNow(response.id, data);
    } else if(response.status === 409){
      errorIndicator.value = true;
      errorMsg.value = 'Make sure you have an addresses!';
    }
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const purchaseNow = async (id, data) => {
  try {
    const response = await productStore.purchaseNow(id, data, mop.value, deliveryType.value);
    console.log(response);

    if (response.status === 200) {
      closeModal();

      Swal.fire({
        title: 'Perfect!',
        text: response.message,
        icon: 'success',
        confirmButtonText: 'Confirm'
      });
      router.push('/purchase');
    }
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const renderProductData = async () => {
  const response = await productStore.getProducts();
  productData.value = response.data;
};

const openModal = () => {
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
};

onMounted(() => {
  renderProductData();
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
            d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">Browse Shop</h2>
      </div>

      <!-- Main Shop List -->
      <div class="grid md:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-4 gap-4">

        <div class="bg-white border rounded" v-for="data in productData" :key="data.id">
          <div class="flex flex-col justify-between h-full">
            <div class="h-[12rem] w-full overflow-hidden rounded-t">
              <img class="h-full w-full object-cover object-top" :src="data.image_url" alt="Water Gallons" />
            </div>

            <div class="flex-1 px-3">
              <p class="text-sm text-gray-700 mt-2 font-medium">{{ data.item_name }}</p>
              <p class="text-sm text-gray-700 mt-2 font-normal">{{ data.item_description }}</p>
              <p class="text-[#56a7dc] mt-2 font-medium">₱<span>{{ data.item_price }}</span>.00</p>

              <!-- Quanity Control -->
              <p class="mt-4 mb-1 text-sm text-gray-500">Quantity</p>
              <div class="flex items-center flex-wrap gap-2">
                <div class="flex">
                  <div @click="quantityValueControl('decrement', data)"
                    class="border px-2 py-1 text-gray-500 font-bold text-base hover:bg-[#f5f8fa] transition ease-linear cursor-pointer">
                    -
                  </div>
                  <div class="border-t border-b px-3 py-1 text-gray-500 text-base text-center">
                    {{ quantities[data.id] ?? 1 }}
                  </div>
                  <div @click="quantityValueControl('increment', data)"
                    class="border px-2 py-1 text-gray-500 font-bold text-base hover:bg-[#f5f8fa] transition ease-linear cursor-pointer">
                    +
                  </div>
                </div>
                <p v-if="data.item_stocks >= 1" class="text-sm text-gray-600">{{ data.item_stocks }} pcs available</p>
                <p v-if="data.item_stocks === 0" class="text-sm text-red-500">Out of stocks</p>
              </div>
            </div>

            <div class="px-3 pb-5 pt-7 flex flex-wrap gap-2">
              <button @click="handleAddToCart(data)" :class="{ 'disabled': data.item_stocks === 0 }"
                class="text-sm border border-[#56a7dc] bg-[#f5f8fa] py-2.5 px-4 rounded text-[#56a7dc] active:scale-105 transition-transform ease-linear w-full">Add
                To Cart</button>
              <button @click="buynow(data)" :class="{ 'disabled': data.item_stocks === 0 }"
                class="text-sm primary-btn-bg py-2.5 px-6 rounded text-white active:scale-105 transition-transform ease-linear w-full">Buy
                Now</button>
            </div>
          </div>
          
          <!-- Modal -->
          <dialog :id="'modal_' + data.id" class="modal" >
            <div class="modal-box">
              <button @click="actionModal(data.id)" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
              <h3 class="text-lg font-bold">Mode of Payment</h3>
              <p v-if="errorIndicator" class="text-red-500 flex items-center gap-1 mt-1">
                <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#e86868">
                  <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                  <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                  <g id="SVGRepo_iconCarrier">
                    <circle cx="12" cy="17" r="1" fill="#e86868"></circle>
                    <path d="M12 10L12 14" stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                    <path
                      d="M3.44722 18.1056L10.2111 4.57771C10.9482 3.10361 13.0518 3.10362 13.7889 4.57771L20.5528 18.1056C21.2177 19.4354 20.2507 21 18.7639 21H5.23607C3.7493 21 2.78231 19.4354 3.44722 18.1056Z"
                      stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                  </g>
                </svg>
                Error: {{ errorMsg }}
              </p>
              <!-- Payment Method -->
              <div class="space-y-2 mt-5">
                <span class="text-gray-700 font-medium ml-1">Select Payment Method</span>
                <select v-model="mop" class="select select-bordered w-full">
                  <option disabled selected>Please select</option>
                  <option>COD</option>
                  <option>Online Pay</option>
                </select>
              </div>

              <!-- Delivery Type -->
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
                <button @click="handleBuyNow" class="btn primary-btn-bg text-white w-full">Proceed</button>
              </div>
            </div>

            <!-- Modal Backdrop -->
            <form method="dialog" class="modal-backdrop" @click="actionModal(data.id)"></form>
          </dialog>
        </div>

      </div>

    </div>
  </DashboardLayout>
</template>