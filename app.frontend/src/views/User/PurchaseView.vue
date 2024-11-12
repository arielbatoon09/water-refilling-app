<script setup>
import { ref, onMounted, computed } from 'vue';
import { useProductStore } from '@/stores/product';
import { useRouter } from 'vue-router';
import Swal from 'sweetalert2';
import DashboardLayout from '@/components/DashboardLayout.vue';

const productStore = useProductStore();
const router = useRouter();
const purchaseData = ref([]);
const selectedData = ref([]);
const errorIndicator = ref(false);
const closeModal = ref(null);
const currentTab = ref('All');
const tabs = ['All', 'To Pay', 'To Receive', 'Completed', 'Rated'];

const FormData = ref({
  resource: null,
  resource_ref: null,
  orders: null,
  rate: 1,
  comment: null,
});

const renderPurchaseData = async () => {
  const response = await productStore.getAllPurchases();
  console.log(response.data);
  purchaseData.value = response.data;
};

const filteredPurchaseData = computed(() => {
  if (currentTab.value === 'All') {
    return purchaseData.value;
  }
  return purchaseData.value.filter(item => item.status === currentTab.value);
});

const handlePayNow = async (data) => {
  const result = await Swal.fire({
    title: 'Pay Now',
    text: 'Proceed to pay the order via e-Payment?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Confirm',
    cancelButtonText: 'Cancel',
  });
  if (result.isConfirmed) {
    const response = await productStore.handlePayNow(data);
    console.log(response.data.data);

    // Redirection to checkout
    if (response.data.data.attributes.checkout_url) {
      window.location.href = response.data.data.attributes.checkout_url;
    }
  }
};

const handleRemoveOrder = async (id) => {
  const response = await productStore.removeOrderByCartId(id);
  console.log(response);

  if (response.status === 200) {
    renderPurchaseData();
  }
};

const setSelectedData = (data) => {
  selectedData.value = data;
};

// Handle Send Review Feedback
const handleReview = async () => {
  FormData.value.resource = "shop";
  FormData.value.resource_ref = selectedData.value.refid;
  FormData.value.orders = selectedData.value.orders.map(order => ({
    product_name: order.product_name,
    order_quantity: order.order_quantity,
  }));

  if (
    !FormData.value.resource ||
    !FormData.value.resource_ref ||
    !FormData.value.orders ||
    !FormData.value.rate ||
    !FormData.value.comment
  ) {
    errorIndicator.value = true;
  } else {
    errorIndicator.value = false;

    const response = await productStore.sendReviewFeedback(FormData.value);
    if (response.status === 200) {
      FormData.value.resource = null;
      FormData.value.resource_ref = null;
      FormData.value.orders = null;
      FormData.value.rate = 1;
      FormData.value.comment = null;

      Swal.fire({
        title: 'Perfect!',
        text: response.message,
        icon: 'success',
        confirmButtonText: 'Confirm'
      });

      closeModal.value.click(); 
      renderPurchaseData();
    }
    console.log(response);
  }
};

const completeOrder = async (refid) => {
  const result = await Swal.fire({
    title: 'Complete Order',
    text: 'Did you already received the order?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Confirm',
    cancelButtonText: 'Cancel',
  });

  if (result.isConfirmed) {
    const response = await productStore.updateOrderPaid(refid, 'Completed');
    console.log(response);

    renderPurchaseData();
  }
};

onMounted(() => {
  renderPurchaseData();
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
        <h2 class="text-2xl font-semibold text-gray-700">My Purchase</h2>
      </div>

      <!-- Filter Tabs -->
      <div class="space-x-2 overflow-x-auto flex flex-nowrap">
        <button v-for="tab in tabs" :key="tab" @click="currentTab = tab"
          :class="['px-4 py-2 rounded whitespace-nowrap', currentTab === tab ? 'primary-btn-bg text-white' : 'bg-gray-200']">
          {{ tab }}
        </button>
      </div>

      <!-- Notice for Walk-In -->
      <div class="mt-5 mb-7 flex items-center gap-2 bg-[#56a7dc] rounded py-2 px-3">
        <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        <span class="text-white">Walk-In Delivery should visit to <a class="font-medium hover:underline" target="_blank"
            href="https://www.google.com/maps/place/Alexa+Water+Refilling+Station/@10.2547228,123.9449681,980m/data=!3m2!1e3!4b1!4m6!3m5!1s0x33a99bbbfa5944d9:0xa7c9355a5c134578!8m2!3d10.2547228!4d123.947543!16s%2Fg%2F11j5lbq8lp?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D">Alexa
            Water Refilling Station.</a></span>
      </div>

      <!-- Purchase Data List -->
      <div class="bg-white mt-4 rounded space-y-4 overflow-x-auto" v-for="list in filteredPurchaseData"
        :key="list.refid">
        <table class="w-full border-collapse">
          <!-- Table Header -->
          <thead>
            <tr class="border-b">
              <th class="px-8 py-5 text-left text-gray-700 font-medium">{{ list.refid }} - {{ list.mop }}, {{
                list.deliveryType }}</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Quantity</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Total Price</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr class="border-b" v-for="data in list.orders" :key="data.cart_id">
              <td class="px-8 py-5 flex items-center gap-3">
                <div class="h-[3rem] w-[3rem] overflow-hidden rounded-t">
                  <img class="h-full w-full object-cover object-center" :src="data.product_imageurl"
                    alt="Product Image" />
                </div>
                <div>
                  <p class="text-sm text-gray-700 w-full max-w-sm font-medium">{{ data.product_name }}</p>
                  <p class="text-sm text-gray-700 w-full max-w-sm font-normal">{{ data.product_description }}</p>
                </div>
              </td>
              <td class="py-5 text-center text-sm text-gray-700">
                <span>{{ data.order_quantity }}</span>
              </td>
              <td class="px-8 py-5 text-center text-sm text-gray-700">
                ₱<span>{{ data.total_item_price }}</span>.00
              </td>
              <td class="px-8 py-5 text-center">
                <button :class="{ 'disabled': list.status !== 'To Pay' }" @click="handleRemoveOrder(data.cart_id)"
                  class="text-red-500 font-medium hover:underline">Remove</button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Status -->
        <div class="px-8 py-5 flex justify-between gap-1 text-gray-700">
          <div>
            <p>Order Total ({{ list.total_items }} item): <span class="font-bold text-[#56a7dc]">₱{{ list.total_price
                }}.00</span></p>
            <p v-if="list.status === 'To Pay'" class="bg-orange-100 text-orange-600 text-center mt-2 rounded">{{
              list.status }}</p>
            <p v-if="list.status === 'To Receive'" class="bg-yellow-100 text-yellow-600 text-center mt-2 rounded">{{
              list.status }}</p>
            <p v-if="list.status === 'Completed'" class="bg-green-100 text-green-600 text-center mt-2 rounded">{{
              list.status }}</p>
            <p v-if="list.status === 'Rated'" class="text-yellow-500 font-medium mt-2 rounded flex items-center gap-1">
              <svg class="w-[24px] h-[24px] text-yellow-500 -mt-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path
                  d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z" />
              </svg>
              {{ list.status }}
            </p>
          </div>
          <div class="pr-10">
            <!-- Pay Now -->
            <button @click="handlePayNow(list)" v-if="list.status === 'To Pay'"
              class="text-sm border border-[#56a7dc] bg-[#f5f8fa] py-2.5 px-4 rounded text-[#56a7dc] active:scale-105 transition-transform ease-linear">Pay
              Now</button>

            <!-- Review Button -->
            <button onclick="reviewModal.showModal()" @click="setSelectedData(list)" v-if="list.status === 'Completed'"
              class="text-sm border border-[#56a7dc] bg-[#f5f8fa] py-2.5 px-4 rounded text-[#56a7dc] 
                active:scale-105 transition-transform ease-linear">
              Review
            </button>

            <!-- Complete Button -->
            <button @click="completeOrder(list.refid)" v-if="list.status === 'To Receive'"
              class="text-sm border border-[#56a7dc] bg-[#f5f8fa] py-2.5 px-4 rounded text-[#56a7dc] 
                active:scale-105 transition-transform ease-linear">
              Order Received
            </button>
          </div>
        </div>
      </div>

      <!-- Review Modal -->
      <dialog id="reviewModal" class="modal">
        <div class="modal-box">
          <form method="dialog">
            <button ref="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
          </form>
          <h3 class="text-lg font-bold">Write Review</h3>
          <!-- Error Indicator -->
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
            Error: Make sure to fill the field/s.
          </p>

          <!-- Field -->
          <div class="space-y-2 mt-3">
            <p class="text-gray-700 font-medium ml-1">Rate a star</p>
            <div class="rating">
              <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" v-model="FormData.rate"
                :value="1" />
              <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" v-model="FormData.rate"
                :value="2" />
              <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" v-model="FormData.rate"
                :value="3" />
              <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" v-model="FormData.rate"
                :value="4" />
              <input type="radio" name="rating-2" class="mask mask-star-2 bg-orange-400" v-model="FormData.rate"
                :value="5" />
            </div>
          </div>

          <div class="space-y-2 mt-3">
            <p class="text-gray-700 font-medium ml-1">Add Comments</p>
            <textarea v-model="FormData.comment" class="textarea textarea-bordered w-full"
              placeholder="Enter review..."></textarea>
          </div>

          <!-- Review Button -->
          <div class="space-y-2 mt-6">
            <button @click="handleReview" class="btn primary-btn-bg text-white w-full">Send Review</button>
          </div>
        </div>
        <form method="dialog" class="modal-backdrop">
          <button>close</button>
        </form>
      </dialog>
    </div>
  </DashboardLayout>
</template>