<script setup>
import { ref, computed, onMounted } from 'vue';
import { useUsersStore } from '@/stores/users';
import AdminLayout from '@/components/AdminLayout.vue';
import SampleImage from '@/assets/inventory/water-galloons.jpg';
import Swal from 'sweetalert2';

const users = ref([]);
const userStore = useUsersStore();
const searchQuery = ref('');
const searchInput = ref('');
const errorIndicator = ref(null);
const currentTab = ref('All Items');
const currentPage = ref(1);
const itemsPerPage = 4;

const tabs = ['All Items', 'In Stock', 'Out of Stock', 'Low Stock'];

// Trigger search when button is clicked
const performSearch = () => {
  searchQuery.value = searchInput.value;
};

const filteredusers = computed(() => {
  let filtered = users.value;

  if (currentTab.value !== 'All Items') {
    filtered = filtered.filter(product => product.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filtered.slice(start, end);
});

const totalPages = computed(() => {
  let filtered = users.value;

  if (currentTab.value !== 'All Items') {
    filtered = filtered.filter(product => product.status === currentTab.value);
  }

  if (searchQuery.value) {
    filtered = filtered.filter(product =>
      product.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
  }

  return Math.ceil(filtered.length / itemsPerPage);
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

// Start
const selectedId = ref(null);
const isModalOpen = ref(false);

const userUpdate = ref({
  name: null,
  email : null,
  user_role: null,
  flag: null,
});

const renderUsers = async () => {
  try {
    const response = await userStore.getAllUser();
    users.value = response.data;
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const renderSelectedUser = async (id) => {
  selectedId.value = id;
  isModalOpen.value = true;
  try {
    const response = await userStore.getAllUserById(id);
    const data = response.data;

    userUpdate.value.name = data.name;
    userUpdate.value.email = data.email;
    userUpdate.value.user_role = data.user_role;
    userUpdate.value.flag = data.flag;
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const closeModal = () => {
  isModalOpen.value = false; 
  selectedId.value = null; 

  userUpdate.value.name = null;
  userUpdate.value.email = null;
  userUpdate.value.user_role = null;
  userUpdate.value.flag = null;
}

const updateUser = async () => {
  try {
    const datas = {
      name: userUpdate.value.name,
      email: userUpdate.value.email,
      user_role: userUpdate.value.user_role,
      flag: userUpdate.value.flag,
      id: selectedId.value,
    };

    const response = await userStore.updateUserWithId(datas);

    if(response.status == 200){
      Swal.fire('Confirmed!', 'Successfully Updated!!.', 'success');
    }else{
      Swal.fire('Error', 'Failed to Updated!.', 'error');
    }

  } catch (error) {
    console.log('Error in ' + error);
  }
}


const confirmAction = async (id) => {
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
      removeUser(id);
    } else {
      Swal.fire('Cancelled', 'Your action has been canceled', 'error');
    }
  });
}

const removeUser = async (id) => {
  try {
    const response = await userStore.removeUserById(id);

    if(response.status == 200){
      Swal.fire('Confirmed!', 'Successfully Removed!.', 'success');
    }else{
      Swal.fire('Error', 'Failed to Removed!.', 'error');
    }
  } catch (error) {
    console.log('Error in ' + error); 
  }
}

onMounted(() => {
  renderUsers();
});
</script>


<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="w-full lg:w-1/6">
          <select class="select select-bordered w-full">
            <option disabled selected>Filter feedbacks</option>
            <option>Han Solo</option>
            <option>Greedo</option>
          </select>
        </div>

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
              <th class="py-2 px-4 w-1/12 whitespace-nowrap">User ID</th>
              <th class="py-2 px-4">Username</th>
              <th class="py-2 px-4">Email</th>
              <th class="py-2 px-4">Role</th>
              <th class="py-2 px-4">Status</th>
              <th class="py-2 px-4">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <td class="py-4 px-14">{{ user.id }}</td>
              <td class="py-4 px-4">{{ user.name }}</td>
              <td class="py-4 px-4">{{ user.email }}</td>
              <td class="py-4 px-4">{{ user.user_role }}</td>
              <td class="py-4 px-4">{{ user.flag == 1 ? 'Active' : 'Inactive/Removed' }}</td>
              <td class="py-4 px-4">
                <details class="dropdown dropdown-end">
                  <summary class="btn m-1">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </summary>
                  <ul class="menu dropdown-content bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="renderSelectedUser(user.id)">Update</a></li>
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
                            <span class="text-gray-700 font-medium">User Name</span>
                            <div class="relative">
                              <input v-model="userUpdate.name" type="text" placeholder="Enter product name" class="input input-bordered w-full" />
                            </div>
                          </div>
                          <div class="space-y-1 mt-4">
                            <span class="text-gray-700 font-medium">Email Address</span>
                            <input v-model="userUpdate.email" type="email" placeholder="Enter number" class="input input-bordered w-full" />
                          </div>
                          <div class="space-y-1 mt-4">
                            <span class="text-gray-700 font-medium">User Role</span>
                            <select v-model="userUpdate.user_role" class="select select-bordered w-full max-w-xs">
                              <option value="user">User</option>
                              <option value="admin">Admin</option>
                            </select>
                          </div>
                          <div class="space-y-1 mt-4">
                            <span class="text-gray-700 font-medium">Flag</span>
                            <select v-model="userUpdate.flag" class="select select-bordered w-full max-w-xs">
                              <option value="1">Active</option>
                              <option value="0">Inactive / Removed</option>
                            </select>
                          </div>
                          <div class="space-y-1 mt-4">
                            <button class="btn w-full primary-btn-bg text-white" @click="updateUser">
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

      <!-- Pagination -->
      <div class="mt-4 flex justify-between">
        <button @click="prevPage" :disabled="currentPage === 1"
          class="btn px-4 py-2 bg-gray-200 rounded">Previous</button>
        <button @click="nextPage" :disabled="currentPage === totalPages"
          class="btn px-4 py-2 bg-gray-200 rounded">Next</button>
      </div>
    </div>
  </AdminLayout>
</template>