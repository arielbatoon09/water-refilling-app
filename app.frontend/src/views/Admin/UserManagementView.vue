<script setup>
import { ref, computed, onMounted } from 'vue';
import { useUsersStore } from '@/stores/users';
import AdminLayout from '@/components/AdminLayout.vue';
import Swal from 'sweetalert2';

const users = ref([]);
const userStore = useUsersStore();
const searchInput = ref('');
const searchQuery = ref('');
const selectedRole = ref('');
const activeModal = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;
const selectedId = ref(null);
const user_role = ref(null);

const userUpdate = ref({
  name: null,
  email: null,
  user_role: null,
  flag: null,
});

const performSearch = () => {
  searchQuery.value = searchInput.value.trim();
  currentPage.value = 1;
};

const userInformation = async () => {
  try {
    const response = await userStore.getInformation();
    const data = response.data;
    console.log(data.user_role);
    user_role.value = data.user_role;
  } catch (error) {
    console.error('Error fetching user information:', error);
  }
};

const filteredUsers = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const role = selectedRole.value;
  const filtered = users.value.filter((user) => {
    const matchesSearch =
      user.name.toLowerCase().includes(search) ||
      user.email.toLowerCase().includes(search) ||
      user.id.toString().includes(search);
    const matchesRole = !role || user.user_role === role;
    return matchesSearch && matchesRole;
  });

  // Pagination logic
  const startIndex = (currentPage.value - 1) * itemsPerPage;
  const endIndex = startIndex + itemsPerPage;
  return filtered.slice(startIndex, endIndex);
});

const totalPages = computed(() => {
  const search = searchQuery.value.toLowerCase();
  const totalFiltered = users.value.filter((user) => {
    const matchesSearch =
      user.name.toLowerCase().includes(search) ||
      user.email.toLowerCase().includes(search) ||
      user.id.toString().includes(search);
    const matchesRole = !selectedRole.value || user.user_role === selectedRole.value;
    return matchesSearch && matchesRole;
  });
  return Math.ceil(totalFiltered.length / itemsPerPage);
});

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
    const response = await userStore.getAllUserById(id);
    const data = response.data;

    userUpdate.value.name = data.name;
    userUpdate.value.email = data.email;
    userUpdate.value.user_role = data.user_role;
    userUpdate.value.flag = data.flag;
  } catch (error) {
    console.log('Error in ' + error);
  }
};

const renderUsers = async () => {
  try {
    const response = await userStore.getAllUser();
    console.log(response);
    users.value = response.data;
  } catch (error) {
    console.log('Error in ' + error);
  }
};

const updateUser = async (id) => {
  try {
    const datas = {
      name: userUpdate.value.name,
      email: userUpdate.value.email,
      user_role: userUpdate.value.user_role,
      flag: userUpdate.value.flag,
      id: selectedId.value,
    };

    const response = await userStore.updateUserWithId(datas);

    if (response.status == 200) {
      Swal.fire('Confirmed!', 'Successfully Updated!', 'success');
      renderUsers();
      handleActionModal(id);
    } else {
      Swal.fire('Error', 'Failed to Updated!.', 'error');
      handleActionModal(id);
    }
  } catch (error) {
    console.log('Error in ' + error);
  }
};

// Pagination controls
const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value += 1;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value -= 1;
  }
};

onMounted(() => {
  renderUsers();
  userInformation();
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto mt-10">
      <!-- Add Order & Search Button -->
      <div class="mb-4 mt-7 flex flex-wrap gap-4 justify-between">
        <!-- Filter Tabs -->
        <div class="w-full lg:w-1/6">
          <select v-model="selectedRole" class="select select-bordered w-full" @change="renderUsers">
            <option value="">All Role</option>
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="staff">Staff</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex items-center w-full lg:w-1/4">
          <input v-model="searchInput" type="text" placeholder="Search..." class="px-4 py-3 rounded w-full" />
          <button @click="performSearch" class="btn btn-square rounded-l primary-btn-bg text-white">
            <svg class="w-[24px] h-[24px] text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
              height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="w-full">
        <table class="w-full table-auto bg-white text-left border-collapse">
          <thead>
            <tr class="border-b border-gray-200 bg-gray-50 text-sm md:text-base">
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center whitespace-nowrap w-20">User ID</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Full Name</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Email</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Success Refills</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Success Order</th>
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Role</th> 
              <th class="py-2 px-2 sm:py-4 sm:px-4 text-center">Status</th>
              <th v-if="user_role === 'admin'" class="py-2 px-2 sm:py-4 sm:px-4 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in filteredUsers" :key="user.id" class="hover:bg-gray-50">
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.id }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.name }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.email }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.successRefill }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">{{ user.successOrders }}</td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span v-if="user.user_role === 'user'" class="p-2 rounded bg-cyan-200 text-cyan-700 capitalize">{{
                  user.user_role }}</span>
                <span v-if="user.user_role === 'admin'" class="p-2 rounded bg-purple-200 text-purple-700 capitalize">{{
                  user.user_role }}</span>
                <span v-if="user.user_role === 'staff'" class="p-2 rounded bg-blue-200 text-blue-700 capitalize">{{
                  user.user_role }}</span>
              </td>
              <td class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <span v-if="user.flag == 1" class="p-2 rounded bg-green-200 text-green-700">Active</span>
                <span v-else class="p-2 rounded bg-red-200 text-red-700">Disabled</span>
              </td>
              <td v-if="user_role === 'admin'" class="py-2 px-2 sm:py-4 sm:px-4 text-center">
                <div class="dropdown dropdown-end">
                  <button class="btn btn-xs sm:btn-sm">
                    <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                      width="24" height="24" fill="none" viewBox="0 0 24 24">
                      <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                        d="M6 12h.01m6 0h.01m5.99 0h.01" />
                    </svg>
                  </button>
                  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-52 p-2 shadow">
                    <li><a @click="actionModal(user.id)">Update Details</a></li>
                  </ul>
                </div>

                <!-- Update User Modal -->
                <dialog :id="'modal_' + user.id" class="modal">
                  <div class="modal-box">
                    <form method="dialog">
                      <button @click="actionModal(user.id)"
                        class="btn btn-sm btn-circle btn-ghost absolute right-4 top-6">✕</button>
                    </form>
                    <h3 class="text-left text-lg font-bold">Update User Details</h3>

                    <div class="w-full text-left">
                      <div class="slide-1">
                        <div class="space-y-1 mt-5">
                          <span class="text-gray-700 font-medium">User Name</span>
                          <div class="relative">
                            <input v-model="userUpdate.name" type="text" placeholder="Enter product name"
                              class="input input-bordered w-full" />
                          </div>
                        </div>
                        <div class="space-y-1 mt-4">
                          <span class="text-gray-700 font-medium">Email Address</span>
                          <input v-model="userUpdate.email" type="email" placeholder="Enter number"
                            class="input input-bordered w-full" />
                        </div>
                        <div class="space-y-1 mt-4">
                          <span class="text-gray-700 font-medium">User Role</span>
                          <select v-model="userUpdate.user_role" class="select select-bordered w-full">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                          </select>
                        </div>
                        <div class="space-y-1 mt-4">
                          <span class="text-gray-700 font-medium">Flag</span>
                          <select v-model="userUpdate.flag" class="select select-bordered w-full">
                            <option value="1">Active</option>
                            <option value="0">Inactive / Removed</option>
                          </select>
                        </div>
                        <div class="space-y-1 mt-4">
                          <button class="btn w-full primary-btn-bg text-white" @click="updateUser(user.id)">
                            Update Details
                          </button>
                        </div>
                      </div>
                    </div>

                  </div>
                  <form method="dialog" class="modal-backdrop">
                    <button @click="actionModal(user.id)">Close</button>
                  </form>
                </dialog>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
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