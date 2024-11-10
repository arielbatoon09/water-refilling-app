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
            d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">My Cart</h2>
      </div>

      <!-- Cart Header -->
      <div class="flex justify-between bg-white px-8 py-5 rounded">
        <div class="flex items-center gap-3">
          <span class="text-gray-700 font-medium">Product</span>
        </div>

        <div class="flex items-center gap-14">
          <p class="text-gray-500">Unit Price</p>
          <p class="text-gray-500">Quantity</p>
          <p class="text-gray-500">Total Price</p>
          <p class="text-gray-500">Actions</p>
        </div>
      </div>

      <!-- Cart Data List -->
      <div class="bg-white mt-4 rounded space-y-4">

        <div class="flex items-center gap-2 border-b px-8 py-5">
          <input type="checkbox" class="checkbox" />
          <p>Select All</p>
        </div>

        <!-- Order Cart List -->
        <div class="border-b px-8 py-5 flex justify-between">
          <div class="flex items-center gap-3">
            <input type="checkbox" class="checkbox" />

            <div class="h-[3rem] w-[3rem] overflow-hidden rounded-t">
              <img class="h-full w-full object-cover object-center"
                src="http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg" />
            </div>

            <p>20 Liter Or 5Gallon Round Water Dispenser Colored With FREE Non Spill Half Cap With Cover</p>
          </div>

          <!-- Right -->
          <div class="flex items-center gap-14">
            <p>₱<span>599</span>.00</p>

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

            <p class="text-[#56a7dc] mt-2 font-medium">₱<span>150</span>.00</p>

            <button>Delete</button>
          </div>
        </div>

        <div class="px-8 py-5">
          <button
            class="text-sm primary-btn-bg py-2.5 px-6 rounded text-white active:scale-105 transition-transform ease-linear">Check
            Out</button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>