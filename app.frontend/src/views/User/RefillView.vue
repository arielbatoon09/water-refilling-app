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
    icon: 'warning',
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

const completeRefill = async (id) => {
  const result = await Swal.fire({
    title: 'Refill Complete',
    text: 'Is the water refill completed?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Confirm',
    cancelButtonText: 'Cancel',
  });

  if (result.isConfirmed) {
    const response = await refillStore.completeRefill(id);
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
    <div class="pt-4 lg:pt-8 lg:px-24 2xl:px-40 pb-10">
      <!-- Page Header -->
      <div class="flex items-center gap-2 mb-6">
        <svg class="w-[38px] h-[38px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">Refill Water</h2>
      </div>

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

        <!-- Notice for Walk-In -->
        <div class="mt-5 mb-7 flex items-center gap-2 bg-[#56a7dc] rounded py-2 px-3">
          <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
              d="M12 13V8m0 8h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
          </svg>
          <span class="text-white">Walk-In Delivery should visit to <a class="font-medium hover:underline"
              target="_blank"
              href="https://www.google.com/maps/place/Alexa+Water+Refilling+Station/@10.2547228,123.9449681,980m/data=!3m2!1e3!4b1!4m6!3m5!1s0x33a99bbbfa5944d9:0xa7c9355a5c134578!8m2!3d10.2547228!4d123.947543!16s%2Fg%2F11j5lbq8lp?entry=ttu&g_ep=EgoyMDI0MTEwNi4wIKXMDSoASAFQAw%3D%3D">Alexa
              Water Refilling Station.</a></span>
        </div>

        <!-- Refill List -->
        <div class="grid lg:grid-cols-2 xl:grid-cols-2 2xl:grid-cols-3 gap-5">

          <div v-if="refillData.length > 0" v-for="(data, index) in refillData" :key="index"
            class="bg-white border rounded hover:border-blue-200">
            <div class="flex flex-col justify-between h-[57vh] 2xl:h-[45vh] border-t-[0.4rem] border-[#56a7dc] rounded">
              <div class="px-4 pt-3 flex items-center justify-between">
                <p class="text-gray-700 font-medium text-base">Refill ID: #{{ data.id }}</p>

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
                    <li :class="{ 'disabled': data.status !== 'Pending Payment' }" @click="payRefillNow(data)">
                      <a>Pay Now</a>
                    </li>

                    <li :class="{ 'disabled': data.status !== 'Delivered' }" @click="completeRefill(data.id)">
                      <a>Mark as Completed</a>
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
                  <span v-if="data.status === 'Pending Payment'" @click="payRefillNow(data)"
                    class="text-base bg-orange-100 px-3 text-center rounded-full text-orange-600 cursor-pointer hover:bg-orange-200">
                    {{ data.status }}
                  </span>

                  <span v-if="data.status === 'Delivered'" @click="completeRefill(data.id)"
                    class="text-base bg-cyan-100 px-3 text-center rounded-full text-cyan-600 cursor-pointer hover:bg-cyan-200">
                    {{ data.status }}
                  </span>

                  <span v-if="data.status === 'Completed'"
                    class="text-base bg-green-100 px-3 text-center rounded-full text-green-600">
                    {{ data.status }}
                  </span>

                  <span v-if="data.status === 'Waiting Delivery'"
                    class="text-base bg-yellow-100 px-3 text-center rounded-full text-yellow-600">
                    {{ data.status }}
                  </span>

                  <span v-if="data.status === 'For Pick-up'"
                    class="text-base bg-yellow-100 px-3 text-center rounded-full text-yellow-600">
                    {{ data.status }}
                  </span>
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