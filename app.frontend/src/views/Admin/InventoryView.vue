<script setup>
import { ref, onMounted } from 'vue';
import { useProductStore } from '@/stores/product';
import AdminLayout from '@/components/AdminLayout.vue';
import NewProductModal from '@/components/NewProductModal.vue';
import EventBus from '@/js/EventBus';

const products = ref({});

const productStore = useProductStore();
const searchQuery = ref('');
const searchInput = ref(null);

const tabs = ['All Items', 'In Stock', 'Out of Stock', 'Low Stock'];

const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const renderProductData = async () => {
  const response = await productStore.getProducts();
  return products.value = response.data;
};

onMounted(() => {
  renderProductData();
  EventBus.on('inventoryUpdated', renderProductData);
});
</script>


<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Item Button -->
      <button onclick="newProduct.showModal()" class="btn primary-btn-bg text-white">
        <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M5 12h14m-7 7V5" />
        </svg>
        New Product
      </button>

      <dialog id="newProduct" class="modal">
        <newProductModal />
      </dialog>

      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <!-- <div class="space-x-2 overflow-x-auto flex flex-nowrap">
          <button v-for="tab in tabs" :key="tab" @click="currentTab = tab"
            :class="['px-4 py-2 rounded whitespace-nowrap', currentTab === tab ? 'primary-btn-bg text-white' : 'bg-gray-200']">
            {{ tab }}
          </button>
        </div> -->

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
      <div class="overflow-x-auto w-100 mt-4">
        <table class="min-w-full bg-white text-left">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="py-2 px-4 w-16 text-center">Item ID</th>
              <th class="py-2 px-4 w-20 text-center">Image</th>
              <th class="py-2 px-4 text-center">Product Name</th>
              <th class="py-2 px-4 text-center">Product Description</th>
              <th class="py-2 px-4 text-center">Stock</th>
              <th class="py-2 px-4 text-center">Cost</th>
              <th class="py-2 px-4 text-center">Status</th>
              <th class="py-2 px-4 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(product, index) in products" :key="product.id">
              <td class="py-4 px-10 text-center">{{ index+1 }}</td>
              <td class="py-4 px-4 w-20 text-center">
                <img :src="product.image_url" />
              </td>
              <td class="py-4 px-4 text-center">{{ product.item_name }}</td>
              <td class="py-4 px-4 text-center">{{ product.item_description }}</td>
              <td class="py-4 px-4 text-center">{{ product.item_stocks }}</td>
              <td class="py-4 px-4 text-center">{{ product.item_price }}</td>
              <td class="py-4 px-4 text-center">
                <span :class="[ 
                  'p-2 rounded', 
                  product.item_stocks >= 50 ? 'bg-green-200 text-green-700 whitespace-nowrap' : '', 
                  product.item_stocks >= 50 ? 'bg-orange-200 text-orange-700 whitespace-nowrap' : '', 
                  product.item_stocks >= 50 ? 'bg-red-200 text-red-700 whitespace-nowrap' : '' 
                ]">
                  {{ product.item_stocks >= 50 ? 'High Stock' : 'Low Stock' }}
                </span>
              </td>
              <td class="py-4 px-3 text-center">
                <button class="btn btn-ghost">
                  <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                      d="M6 12h.01m6 0h.01m5.99 0h.01" />
                  </svg>
                </button>
              </td>

            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <!-- <div class="mt-4 flex justify-between">
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div> -->
    </div>
  </AdminLayout>
</template>