<script setup>
import { ref } from 'vue';
import DashboardLayout from '@/components/DashboardLayout.vue';

const currentTab = ref('All');

const tabs = ['All', 'To Receive', 'Completed', 'Rated', 'Cancelled'];
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
        <h2 class="text-2xl font-semibold text-gray-700">My Purchase</h2>
      </div>

      <!-- Filter Tabs -->
      <div class="space-x-2 overflow-x-auto flex flex-nowrap">
        <button v-for="tab in tabs" :key="tab" @click="currentTab = tab"
          :class="['px-4 py-2 rounded whitespace-nowrap', currentTab === tab ? 'primary-btn-bg text-white' : 'bg-gray-200']">
          {{ tab }}
        </button>
      </div>

      <!-- Cart Data List -->
      <div class="bg-white mt-4 rounded space-y-4 overflow-x-auto" v-for="data in 3">

        <table class="w-full border-collapse">
          <!-- Table Header -->
          <thead>
            <tr class="border-b">
              <th class="px-8 py-5 text-left text-gray-700 font-medium">Ref#123</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Quantity</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Total Price</th>
              <th class="px-8 py-5 text-gray-500 font-normal">Actions</th>
            </tr>
          </thead>

          <tbody>
            <!-- Product Column -->
            <tr class="border-b">
              <td class="px-8 py-5 flex items-center gap-3">
                <div class="h-[3rem] w-[3rem] overflow-hidden rounded-t">
                  <img class="h-full w-full object-cover object-center"
                    src="http://127.0.0.1:5173/src/assets/inventory/water-galloons.jpg" alt="Product Image" />
                </div>
                <p class="text-sm text-gray-700 w-full max-w-sm">
                  20 Liter Or 5 Gallon Round Water Dispenser Colored With FREE Non Spill Half Cap With Cover
                </p>
              </td>

              <!-- Quantity Column -->
              <td class="py-5 text-center text-sm text-gray-700">
                <span>1</span>
              </td>

              <!-- Total Price Column -->
              <td class="px-8 py-5 text-center text-sm text-gray-700">
                ₱<span>150</span>.00
              </td>

              <!-- Actions Column -->
              <td class="px-8 py-5 text-center dropdown dropdown-end ml-12 -mt-12">
                <button class="btn btn-ghost" tabindex="0" role="button">
                  <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                      d="M6 12h.01m6 0h.01m5.99 0h.01" />
                  </svg>
                </button>

                <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                  <li><a>Rate Order</a></li>
                  <li><a>Cancel Order</a></li>
                </ul>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Status -->
        <div class="px-8 py-5 flex items-center gap-1 text-gray-700">
          <!-- <button class="text-sm primary-btn-bg py-2.5 px-12 rounded text-white active:scale-105 transition-transform ease-linear">Rate</button> -->
          <p>Status Here | Order Total:</p>
          <p class="font-bold text-[#56a7dc]">₱279.00</p>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>