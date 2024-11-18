<script setup>
import { ref, onMounted, computed } from 'vue';
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
const searchInput = ref(null);
const errorIndicator = ref(null);
const errorMsg = ref(null);
const selectedId = ref(null);
const activeModal = ref(null);
const filteredProducts = ref({});
const currentPage = ref(1);
const itemsPerPage = 10;

const handleActionModal = (id) => {
  const modal = document.getElementById(`modal_${id}`);

  if (!modal) return;

  if (activeModal.value === id) {
    modal.close();
    activeModal.value = null;
  } else {
    activeModal.value = id;
    modal.showModal();
  }
};

const actionModal = async (id) => {
  selectedId.value = id;
  handleActionModal(id);

  try {
    const response = await productStore.getSelectedProducts(selectedId.value);
    const data = response.data;

    FormData.value.product_name = data.item_name;
    FormData.value.description = data.item_description;
    FormData.value.stock = data.item_stocks;
    FormData.value.cost = data.item_price;
  } catch (error) {
    console.error('Error fetching products data:', error);
  }
};

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

const renderProductData = async () => {
  const response = await productStore.getProductsAdmin();
    products.value = response.data || {};
    filteredProducts.value = products.value;
};

const triggerSearch = () => {
  const query = searchInput.value.toLowerCase();

  filteredProducts.value = Object.fromEntries(
    Object.entries(products.value).filter(([key, product]) => {
      return (
        product.item_name.toLowerCase().includes(query) ||
        product.item_description.toLowerCase().includes(query) ||
        (product.flag === 1 && "active".includes(query)) ||
        (product.flag === 0 && "inactive".includes(query)) ||
        String(product.id).includes(query)
      );
    })
  );

  currentPage.value = 1;
};

const paginatedGallons = computed(() => {
  const productsArray = Object.values(filteredProducts.value);
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  
  return productsArray.slice(start, end);
});

const totalPages = computed(() => {
  const productsArray = Object.values(filteredProducts.value);
  
  return Math.ceil(productsArray.length / itemsPerPage);
});

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const updateProductStatus = async (id) => {
  const response = await productStore.updateProductStatus(id);

  if (response.status === 200) {
    Swal.fire('Success', response.message, 'success');
  } else {
    Swal.fire('Cancelled', 'Your action has been canceled', 'error');
  }
};

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
      updateProductStatus(id);
      renderProductData();
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

const updateProduct = async (id) => {
  try {
    const data = {
      product_name: FormData.value.product_name,
      stock: FormData.value.stock,
      product_image: FormData.value.product_image,
      description: FormData.value.description,
      cost: FormData.value.cost,
      id: selectedId.value,
    };

    const response = await productStore.updateProducts(data);

    console.log("Product: ", response);

    if (response.status == 200) {
      Swal.fire('Success', 'Successfully Update Product', 'success');
      handleActionModal(id);
      renderProductData();
    } else {
      Swal.fire('Error', 'Failed to Update Product', 'error');
      handleActionModal(id);
    }

  } catch (error) {
    console.log('Error in ' + error.message);
  }
};

onMounted(() => {
  renderProductData();
  EventBus.on('inventoryUpdated', renderProductData);
});
</script>


<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <div class="space-x-2 flex flex-nowrap">
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
        </div>

        <div class="flex items-center w-full lg:w-1/4">
          <input type="text" placeholder="Search..." class="px-4 py-3 rounded w-full" v-bind:value="searchInput"
            @input="searchInput = $event.target.value" />
          <button @click="triggerSearch" class="btn btn-square rounded-l primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <div class="w-full">
        <table class="w-full table-auto bg-white text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50 text-sm md:text-base">
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">ID</th>
              <th class="py-2 px-2 sm:py-4 sm:px-10 text-center">Image</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Product Name</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Product Description</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Stock</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Cost</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Status</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in paginatedGallons" :key="product.id" class="hover:bg-gray-50">
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ product.id }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-10 w-16 h-16">
                <img class="h-full w-full object-contain" :src="product.image_url" />
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ product.item_name }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ product.item_description }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ product.item_stocks }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">₱{{ product.item_price }}.00</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span
                  :class="['p-2 rounded', product.flag === 1 ? 'bg-green-200 text-green-700' : '', product.flag === 0 ? 'bg-red-200 text-red-700' : '']">
                  {{ product.flag === 1 ? 'Active' : 'Inactive' }}
                </span>
              </td>
              <td class="py-4 px-3 text-center">
                <div class="dropdown dropdown-end">
                  <button class="btn btn-xs sm:btn-sm">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                      width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                        d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </button>
                  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li @click="actionModal(product.id)"><a>Update</a></li>
                    <li v-if="product.flag === 1" @click="confirmAction(product.id)"><a>Disable</a></li>
                    <li v-if="product.flag === 0" @click="confirmAction(product.id)"><a>Enable</a></li>
                  </ul>
                </div>

                <!-- Update Gallon Modal -->
                <dialog :id="'modal_' + product.id" class="modal text-left">
                  <div class="modal-box">
                    <form method="dialog">
                      <button @click="actionModal(product.id)"
                        class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
                    </form>
                    <h3 class="text-left text-lg font-bold">Update Gallon</h3>
                    <p v-if="errorIndicator" class="text-red-500 flex items-center gap-1 mt-1">
                      <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        stroke="#e86868">
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
                            <input v-model="FormData.product_name" type="text" placeholder="Enter product name"
                              class="input input-bordered w-full" />
                          </div>
                        </div>
                        <div class="space-y-1 mt-4">
                          <span class="text-gray-700 font-medium">Product Stock</span>
                          <input v-model="FormData.stock" type="number" placeholder="Enter number"
                            class="input input-bordered w-full" />
                        </div>
                        <div class="space-y-1 mt-4">
                          <span class="text-gray-700 font-medium">Product Cost</span>
                          <input v-model="FormData.cost" type="number" placeholder="Enter number"
                            class="input input-bordered w-full" />
                        </div>
                        <div class="space-y-1 mt-4">
                          <span class="text-gray-700 font-medium">Product Description</span>
                          <textarea v-model="FormData.description" rows="10" cols="10"
                            placeholder="Enter a product description" class="input input-bordered w-full"></textarea>
                        </div>
                        <div class="space-y-1 mt-4">
                          <button class="btn w-full primary-btn-bg text-white" @click="updateProduct(product.id)">
                            Update Products
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <form method="dialog" class="modal-backdrop">
                    <button @click="actionModal(product.id)">Close</button>
                  </form>
                </dialog>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-4 flex justify-between z-1">
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div>
    </div>
  </AdminLayout>
</template>