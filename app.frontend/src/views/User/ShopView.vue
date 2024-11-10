<script setup>
import { ref } from 'vue';
import DashboardLayout from '@/components/DashboardLayout.vue';

const quantity = ref(1);

const quantityValueControl = (action) => {
  if (action === 'decrement' && quantity.value > 1) {
    quantity.value--;
  } else if (action === 'increment') {
    quantity.value++;
  }
};
</script>

<template>
  <DashboardLayout>
    <div class="pt-4 lg:pt-8 lg:px-40 pb-10">
      <!-- Page Header -->
      <div class="flex items-center gap-2 mb-6">
        <svg class="w-[38px] h-[38px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">Browse Shop</h2>
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

      <!-- Main Shop List -->
      <div class="grid md:grid-cols-2 lg:grid-cols-2 2xl:grid-cols-4 gap-4">

        <div class="bg-white border rounded" v-for="data in 10">
          <div class="flex flex-col justify-between h-full">
            <div class="h-[10rem] w-full overflow-hidden rounded-t">
              <img class="h-full w-full object-cover object-top"
                src="http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg" alt="Water Gallons" />
            </div>

            <div class="flex-1 px-3">
              <p class="text-sm text-gray-700 mt-2">20 Liter Or 5Gallon Round Water Dispenser Colored With FREE Non Spill
                Half Cap With Cover</p>
              <p class="text-[#56a7dc] mt-2 font-medium">₱<span>150</span>.00</p>

              <!-- Quanity Control -->
              <p class="mt-4 mb-1 text-sm text-gray-500">Quantity</p>
              <div class="flex items-center flex-wrap gap-2">
                <div class="flex">
                  <div @click="quantityValueControl('decrement')"
                    class="border px-2 py-1 text-gray-500 font-bold text-base hover:bg-[#f5f8fa] transition ease-linear cursor-pointer">
                    -
                  </div>
                  <div class="border-t border-b px-3 py-1 text-gray-500 text-base text-center">
                    {{ quantity }}
                  </div>
                  <div @click="quantityValueControl('increment')"
                    class="border px-2 py-1 text-gray-500 font-bold text-base hover:bg-[#f5f8fa] transition ease-linear cursor-pointer">
                    +
                  </div>
                </div>
                <p class="text-sm text-gray-600">20 pcs available</p>
              </div>

            </div>

            <div class="px-3 pb-5 pt-7 flex flex-wrap gap-2">
              <button
                class="text-sm border border-[#56a7dc] bg-[#f5f8fa] py-2.5 px-4 rounded text-[#56a7dc] active:scale-105 transition-transform ease-linear w-full">Add
                To Cart</button>
              <button
                class="text-sm primary-btn-bg py-2.5 px-6 rounded text-white active:scale-105 transition-transform ease-linear w-full">Buy
                Now</button>
            </div>
          </div>
        </div>

      </div>

    </div>
  </DashboardLayout>
</template>