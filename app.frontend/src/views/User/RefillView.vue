<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useRefillStore } from '@/stores/refill';
import DashboardLayout from '@/components/DashboardLayout.vue';
import BookDeliveryModal from '@/components/BookDeliveryModal.vue';
import EventBus from '@/js/EventBus';
import Swal from 'sweetalert2';


const refillStore = useRefillStore();
const refillData = ref({});

const renderRefillData = async () => {
  const response = await refillStore.getBookRefills();
  console.log(response);
  return refillData.value = response.data;
};

const payRefillNow = async (data) => {
  const result = await Swal.fire({
    title: 'Pay Now',
    text: 'Proceed to pay the order via e-Payment?',
    icon: 'success',
    showCancelButton: true,
    confirmButtonText: 'Confirm',
    cancelButtonText: 'Cancel',
  });
  if (result.isConfirmed) {
    const response = await refillStore.payRefillNow(data);

    // Redirection to checkout
    if (response.data.attributes.checkout_url) {
      window.location.href = response.data.attributes.checkout_url;
    }
    console.log(response);
  }
};

const cancelRefill = async (id) => {
  const result = await Swal.fire({
    title: 'Cancel Order',
    text: 'Are you sure you want to cancel this refill order?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Confirm',
    cancelButtonText: 'Cancel',
  });

  if (result.isConfirmed) {
    const response = await refillStore.cancelRefill(id);
    renderRefillData();
    console.log(response);
  }
}

onMounted(() => {
  renderRefillData();
  EventBus.on('refillUpdated', renderRefillData);
});

</script>

<template>
  <DashboardLayout>
    <div class="pt-4 lg:pt-8 lg:px-40 pb-10">
      <!-- Page Header -->
      <div class="flex items-center gap-2 mb-6">
        <svg class="w-[38px] h-[38px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">Refill Water</h2>
      </div>

      <!-- Filter Section -->
      <div class="flex w-full flex-col-reverse lg:flex-row gap-3 justify-between">
        <div class="w-full xl:w-1/6">
          <select class="select select-bordered w-full">
            <option disabled selected>Filter sales</option>
            <option>Han Solo</option>
            <option>Greedo</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex items-center w-full lg:w-1/4">
          <input type="text" placeholder="Search..."
            class="px-4 py-[0.68rem] outline-none rounded-l-lg w-full border" />
          <button class="btn btn-square primary-btn-bg text-white rounded rounded-r-lg">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
          </button>
        </div>

      </div>

      <div class="divider"></div>

      <!-- Main Refill List -->
      <div class="space-y-7">
        <button onclick="bookDelivery.showModal()" class="btn primary-btn-bg text-white">
          <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M5 12h14m-7 7V5" />
          </svg>
          Book Delivery
        </button>

        <!-- Book Delivery Modal -->
        <dialog id="bookDelivery" class="modal">
          <BookDeliveryModal />
        </dialog>

        <!-- Refill List -->
        <div class="grid lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-5">

          <div v-if="refillData.length > 0" v-for="(data, index) in refillData" :key="index"
            class="bg-white border rounded hover:border-blue-200">
            <div class="flex flex-col justify-between h-[57vh] 2xl:h-[45vh] border-t-[0.4rem] border-[#56a7dc] rounded">
              <div class="px-4 pt-3 flex items-center justify-between">
                <p class="text-gray-700 font-medium text-base">Delivery ID: #{{ data.id }}</p>

                <!-- Action Button -->
                <div class="dropdown dropdown-end">
                  <div class="flex items-center hover:bg-gray-100 p-2 rounded cursor-pointer" tabindex="0"
                    role="button">
                    <svg class="w-[18px] h-[18px] text-gray-500" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg"
                      role="img">
                      <path
                        d="m3.771 1.712.3-.772A.692.692 0 0 1 4.719.5h.562a.692.692 0 0 1 .645.44l.3.772 1 .58.82-.124a.692.692 0 0 1 .7.338l.282.488a.693.693 0 0 1-.059.778l-.517.648v1.16l.517.648a.693.693 0 0 1 .059.778l-.282.488a.692.692 0 0 1-.7.338l-.82-.124-1 .58-.3.772a.692.692 0 0 1-.645.44h-.562a.692.692 0 0 1-.645-.44l-.3-.772-1-.58-.82.124a.692.692 0 0 1-.7-.338l-.292-.488a.693.693 0 0 1 .059-.778l.517-.648V4.42l-.517-.648a.693.693 0 0 1-.059-.778l.282-.488a.692.692 0 0 1 .7-.338l.82.124ZM3.75 5A1.25 1.25 0 1 0 5 3.75 1.25 1.25 0 0 0 3.75 5Z"
                        fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>

                    <svg class="w-[14px] h-[14px] text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                      width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path fill-rule="evenodd"
                        d="M18.425 10.271C19.499 8.967 18.57 7 16.88 7H7.12c-1.69 0-2.618 1.967-1.544 3.271l4.881 5.927a2 2 0 0 0 3.088 0l4.88-5.927Z"
                        clip-rule="evenodd" />
                    </svg>
                  </div>

                  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li :class="{ 'disabled': data.status !== 'Pending Payment' }" @click="payRefillNow(data)"><a>Pay
                        Now</a></li>
                    <li :class="{ 'disabled': data.status !== 'Pending Payment' }" @click="cancelRefill(data.id)">
                      <a>Cancel Order</a>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="flex-1 p-4">
                <h3 class="text-md font-medium text-gray-700 mb-2">{{ data.delivery_date }}, <span>{{ data.delivery_type
                    }}</span></h3>
                <ul class="space-y-1 text-gray-600 text-sm">
                  <li v-for="(gallon, index) in data.gallon_details" :key="index">
                    • {{ gallon.gallon_size }} - {{ gallon.no_of_gallon }} Gallon(s)
                  </li>
                </ul>
                <div class="mt-5 space-y-1">
                  <p class="font-medium text-base text-gray-500">Payment Info:</p>
                  <p class="text-gray-500 text-sm">Refill Fee: ₱{{ data.t_refill_fee }}.00</p>
                  <p class="text-gray-500 text-sm">Delivery Fee: ₱{{ data.t_delivery_fee }}.00</p>
                  <p class="text-blue-500 text-sm">Total Fee: ₱{{ data.t_overall_fee }}.00</p>
                  <p class="text-green-500 text-sm">Pay via {{ data.mop }}</p>
                </div>
              </div>
              <div class="border-t">
                <div class="p-4 flex items-center gap-2">
                  <span v-if="data.status === 'Pending Payment'"
                    class="text-base bg-orange-100 px-3 text-center rounded-full text-orange-600">{{ data.status
                    }}</span>
                  <span v-if="data.status === 'Waiting Delivery'"
                    class="text-base bg-yellow-100 px-3 text-center rounded-full text-yellow-600">{{ data.status
                    }}</span>
                  <span v-if="data.status === 'Visit Shop'"
                    class="text-base bg-yellow-100 px-3 text-center rounded-full text-yellow-600">{{ data.status
                    }}</span>
                  <span v-if="data.status === 'Completed Order'"
                    class="text-base bg-green-100 px-3 text-center rounded-full text-green-600">{{ data.status }}</span>
                  <span v-if="data.status === 'Cancelled Order'"
                    class="text-base bg-red-100 px-3 text-center rounded-full text-red-600">{{ data.status }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="text-sm text-gray-500 px-2" v-else>
            No Refill List
          </div>

        </div>

      </div>
    </div>
  </DashboardLayout>
</template>