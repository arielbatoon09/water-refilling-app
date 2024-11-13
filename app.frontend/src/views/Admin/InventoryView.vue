<script setup>
import { ref, onMounted } from 'vue';
import { useProductStore } from '@/stores/product';
import AdminLayout from '@/components/AdminLayout.vue';
import NewProductModal from '@/components/NewProductModal.vue';
import EventBus from '@/js/EventBus';
import Swal from 'sweetalert2';

const products = ref({});

const FormData = ref({
  product_name: null,
  stock: null,
  product_image: null,
  description: null,
  cost: null,
});

const productStore = useProductStore();
const searchQuery = ref('');
const searchInput = ref(null);
const errorIndicator = ref(null);
const errorMsg = ref(null);
const selectedId = ref(null);
const isModalOpen = ref(false);

const tabs = ['All Items', 'In Stock', 'Out of Stock', 'Low Stock'];

const openModal = async (id) => {
  selectedId.value = id; 
  isModalOpen.value = true; 
  try {
    const response = await productStore.getSelectedProducts(selectedId.value);
    const data = response.data;

    FormData.value.product_name = data.item_name;
    FormData.value.description = data.item_description;
    FormData.value.stock = data.item_stocks;
    FormData.value.cost = data.item_price;

  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

function closeModal() {
  isModalOpen.value = false; 
  selectedId.value = null; 

  FormData.value.product_name = null;
  FormData.value.description = null;
  FormData.value.stock = null;
  FormData.value.cost = null;
}

const uploadImage = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const imageDataUrl = e.target.result;
    FormData.value.product_image = imageDataUrl;
  };

  reader.readAsDataURL(file);
};

const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const renderProductData = async () => {
  const response = await productStore.getProducts();
  return products.value = response.data;
};

const removeProduct = async (id) => {
  const response = await productStore.removeProduct(id);

  if(response.status === 200){
    Swal.fire('Success', 'Successfully Removed Product', 'success');
  }else{
    Swal.fire('Cancelled', 'Your action has been canceled', 'error');
  }
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
      removeProduct(id);
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

const updateProduct = async () => {
  try {
    const datas = {
      product_name: FormData.value.product_name,
      stock: FormData.value.stock,
      product_image: FormData.value.product_image,
      description: FormData.value.description,
      cost: FormData.value.cost,
      id: selectedId.value,
    };

    const response = await productStore.updateProducts(datas);

    if(response.status == 200){
      Swal.fire('Success', 'Successfully Update Product', 'success');
    }else{
      Swal.fire('Error', 'Failedd to Update Product', 'error');
    }

  } catch (error) {
    console.log('Error in ' + error.message);
  }
}

onMounted(() => {
  renderProductData();
  EventBus.on('inventoryUpdated', renderProductData);
});
</script>


<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
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

      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
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
                <details class="dropdown dropdown-end">
                  <summary class="btn m-1">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </summary>
                  <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="openModal(product.id)">Update</a></li>
                    <li><a @click="confirmAction(product.id)">Remove</a></li>
                  </ul>
                  <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="modal-box">
                      <form method="dialog">
                        <button ref="closeModal" @click="closeModal" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
                      </form>
                      <h3 class="text-lg font-bold">New Product</h3>
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
                        Error: Make sure to fill up all the product details
                      </p>
                      <div class="w-full">
                        <div class="slide-1">
                          <div class="space-y-1 mt-5">
                            <span class="text-gray-700 font-medium">Product Image</span>
                            <div class="relative">
                              <input type="file" @change="uploadImage" class="file-input file-input-bordered w-full" />
                            </div>
                          </div>
                          <div class="space-y-1 mt-5">
                            <span class="text-gray-700 font-medium">Product Name</span>
                            <div class="relative">
                              <input v-model="FormData.product_name" type="text" placeholder="Enter product name" class="input input-bordered w-full" />
                            </div>
                          </div>
                          <div class="space-y-1 mt-4">
                            <span class="text-gray-700 font-medium">Product Stock</span>
                            <input v-model="FormData.stock" type="number" placeholder="Enter number" class="input input-bordered w-full" />
                          </div>
                          <div class="space-y-1 mt-4">
                            <span class="text-gray-700 font-medium">Product Cost</span>
                            <input v-model="FormData.cost" type="number" placeholder="Enter number" class="input input-bordered w-full" />
                          </div>
                          <div class="space-y-1 mt-4">
                            <span class="text-gray-700 font-medium">Product Description</span>
                            <textarea v-model="FormData.description" rows="10" cols="10" placeholder="Enter a product description" class="input input-bordered w-full" ></textarea>
                          </div>
                          <div class="space-y-1 mt-4">
                            <button class="btn w-full primary-btn-bg text-white" @click="updateProduct">
                              Update Products
                            </button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </details>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AdminLayout>
</template>