<script setup>
import { ref, onMounted, computed } from 'vue';
import { useGallonStore } from '@/stores/gallons';
import AdminLayout from '@/components/AdminLayout.vue';
import BookDeliveryModal from '@/components/GallonModal.vue';
import EventBus from '@/js/EventBus';
import Swal from 'sweetalert2';

const gallonStore = useGallonStore();
const gallonData = ref({});
const updateData = ref({});
const searchInput = ref(null);
const errorIndicator = ref(null);
const errorMsg = ref(null);
const selectedId = ref(null);
const isModalOpen = ref(false);
const filteredGallons = ref({});
const currentPage = ref(1);
const itemsPerPage = 5;

const renderGallon = async () => {
  const response = await gallonStore.getAllGallon();
  gallonData.value = response.data || {};
  filteredGallons.value = gallonData.value;
};

const FormUpdateData = ref({
  gallon_image: null,
  gallon_size: null,
  gallon_price: null,
  delivery_fee: null,
});


const triggerSearch = () => {
  filteredGallons.value = Object.fromEntries(
    Object.entries(gallonData.value).filter(([key, gallon]) =>
      gallon.gallon_size.toLowerCase().includes(searchInput.value.toLowerCase()) ||
      String(gallon.id).includes(searchInput.value)
    )
  );
  currentPage.value = 1;
};

const paginatedGallons = computed(() => {
  const gallonsArray = Object.values(filteredGallons.value);
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return gallonsArray.slice(start, end);
});

const totalPages = computed(() => {
  const gallonsArray = Object.values(filteredGallons.value);
  return Math.ceil(gallonsArray.length / itemsPerPage);
});

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const noResultsFound = computed(() => {
  return Object.keys(filteredGallons.value).length === 0;
});

const openModal = async (id) => {
  selectedId.value = id; 
  isModalOpen.value = true; 
  try {
    const response = await gallonStore.getSelectedGallon(id); 
    const data = response.data; 

    FormUpdateData.value.gallon_size = data.gallon_size || null;
    FormUpdateData.value.gallon_price = data.gallon_price || null;
    FormUpdateData.value.delivery_fee = data.delivery_fee || null;
  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

const deleteGallon = async (id) => {
  const response = await gallonStore.deleteGallonById(id); 

  if(response.status == 200){
    Swal.fire('Confirmed!', 'Successfully Removed!.', 'success');
  }else{
    Swal.fire('Cancelled!', 'Failed to Removed!.', 'error');
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
      deleteGallon(id);
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
};

function closeModal() {
  isModalOpen.value = false; 
  selectedId.value = null; 

  FormUpdateData.value.gallon_image = null;
  FormUpdateData.value.gallon_size = null;
  FormUpdateData.value.gallon_price = null;
  FormUpdateData.value.delivery_fee = null;
}

const uploadImage = (event) => {
  const file = event.target.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    const imageDataUrl = e.target.result;
    FormUpdateData.value.gallon_image = imageDataUrl;
  };

  reader.readAsDataURL(file);
};

const updateGallon = async () =>{
  try {
    
    const data = {
      id: selectedId.value,
      gallon_image: FormUpdateData.value.gallon_image,
      gallon_size: FormUpdateData.value.gallon_size,
      gallon_price: FormUpdateData.value.gallon_price,
      delivery_fee: FormUpdateData.value.delivery_fee,
    };

    const response = await gallonStore.updateGallonById(data);

    if(response.status == 200){
      Swal.fire('Success', 'Successfully Updated!', 'success');
      closeModal();
    }else{
      Swal.fire('Cancelled', 'Something is Wrong!', 'error');
    }

  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

onMounted(() => {
  renderGallon();
  EventBus.on('gallonUpdated', renderGallon);
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <div class="space-x-2 overflow-x-auto flex flex-nowrap">
          <button onclick="bookDelivery.showModal()" class="btn primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M5 12h14m-7 7V5" />
            </svg>
            Add Gallon Type
          </button>

          <dialog id="bookDelivery" class="modal">
            <BookDeliveryModal />
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

      <div class="overflow-x-auto w-100">
        <table class="min-w-full bg-white text-left">
          <thead>
            <tr class="border-b border-gray-200">
              <th class="py-4 px-4 w-14 whitespace-nowrap text-center">ID</th>
              <th class="py-4 px-10 text-center">Thumbnail</th>
              <th class="py-4 px-4 text-center">Gallon Name</th>
              <th class="py-4 px-4 text-center">Price</th>
              <th class="py-4 px-4 text-center">Delivery Fee</th>
              <th class="py-4 px-4 text-center">Status</th>
              <th class="py-4 px-1 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="gallon in paginatedGallons" :key="gallon.id">
              <td class="py-4 px-10 text-center">{{ gallon.id }}</td>
              <td class="py-4 px-10 w-[4rem] h-[7rem] overflow-hidden text-center">
                <img class="h-full w-full object-cover" :src="gallon.gallon_image" />
              </td>
              <td class="py-4 px-4 text-center">{{ gallon.gallon_size }}</td>
              <td class="py-4 px-4 text-center">₱{{ gallon.gallon_price }}.00</td>
              <td class="py-4 px-4 text-center">₱{{ gallon.delivery_fee }}.00</td>
              <td class="py-4 px-4 text-center">
                <span
                  :class="['p-2 rounded', gallon.flag === 1 ? 'bg-green-200 text-green-700' : '', gallon.flag === 0 ? 'bg-orange-200 text-orange-700' : '']">
                  {{ gallon.flag === 1 ? 'Active' : 'Disabled' }}
                </span>
              </td>
              <td class="text-center">
                <details class="dropdown dropdown-end">
                  <summary class="btn m-1">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </summary>
                  <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="openModal(gallon.id)">Update</a></li>
                    <li><a @click="confirmAction(gallon.id)">Remove</a></li>
                  </ul>
                  <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="modal-box">
                      <form method="dialog">
                          <button @click="closeModal()" class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
                      </form>
                      <h3 class="text-lg font-bold">Update Gallon</h3>
                      <p v-if="errorIndicator" class="text-red-500 flex items-center gap-1 mt-1">
                          <svg class="w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="#e86868">
                              <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                              <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                              <g id="SVGRepo_iconCarrier">
                                  <circle cx="12" cy="17" r="1" fill="#e86868"></circle>
                                  <path d="M12 10L12 14" stroke="#e86868" stroke-width="2" stroke-linecap="round"stroke-linejoin="round">
                                  </path>
                                  <path d="M3.44722 18.1056L10.2111 4.57771C10.9482 3.10361 13.0518 3.10362 13.7889 4.57771L20.5528 18.1056C21.2177 19.4354 20.2507 21 18.7639 21H5.23607C3.7493 21 2.78231 19.4354 3.44722 18.1056Z" stroke="#e86868" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                              </g>
                          </svg>
                          Error: {{ errorMsg }}
                      </p>
                      
                      <div class="w-full">
                          <div class="space-y-1 mt-4">
                              <span class="text-gray-700 font-medium">Upload Image</span>
                              <input @change="uploadImage" type="file" class="file-input file-input-bordered file-input-md w-full" />
                          </div>

                          <div class="space-y-1 mt-4">
                              <span class="text-gray-700 font-medium">Gallon Size</span>
                              <input v-model="FormUpdateData.gallon_size" type="text" placeholder="Type here" class="input input-bordered w-full" />
                          </div>

                          <div class="space-y-1 mt-4">
                              <span class="text-gray-700 font-medium">Price Per Gallon</span>
                              <input v-model="FormUpdateData.gallon_price" type="text" placeholder="Type here" class="input input-bordered w-full" />
                          </div>

                          <div class="space-y-1 mt-4">
                              <span class="text-gray-700 font-medium">Delivery Fee Per Gallon</span>
                              <input v-model="FormUpdateData.delivery_fee" type="text" placeholder="Type here" class="input input-bordered w-full" />
                          </div>

                          <div class="space-y-2 mt-6">
                              <button @click="updateGallon" class="btn primary-btn-bg text-white w-full">Update Gallon</button>
                          </div>

                      </div>
                    </div>
                  </div>
                </details>
              </td>
            </tr>
          </tbody>
        </table>

        <p v-if="noResultsFound" class="bg-white text-center py-10 text-xl text-gray-600">Search Not Found</p>
      </div>

      <div class="mt-4 flex justify-between">
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <span>Page {{ currentPage }} of {{ totalPages }}</span>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div>
    </div>
  </AdminLayout>
</template>