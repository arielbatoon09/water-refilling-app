<script setup>
import { ref, onMounted } from 'vue';
import { useUsersStore } from '@/stores/users';
import AdminLayout from '@/components/AdminLayout.vue';
import Swal from 'sweetalert2';

const basicInfoData = ref({
  name: null,
  email: null,
  phone_number: null,
  addressLine: null,
  municipality: null,
  city: null,
  postalCode: null
});
const changePasswordData = ref({
  current_password: null,
  new_password: null,
  confirm_new_password: null,
});
const usersStore = useUsersStore();

const renderUserInfo = async () => {
  try {
    const response = await usersStore.getInformation();
    const data = response.data;
    basicInfoData.value.name = data.name;
    basicInfoData.value.email = data.email;
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const renderAddressInfo = async () => {
  const response = await usersStore.getAddressInformation();
  const data = response.data;
  if (response.data) {
    basicInfoData.value.addressLine = data.address;
    basicInfoData.value.municipality = data.municipality;
    basicInfoData.value.city = data.city;
    basicInfoData.value.phone_number = data.phone_number;
    basicInfoData.value.postalCode = data.postal_code;

    console.log(data);
  }
}

const changeInformation = async () => {
  try {
    const userInfo = {
      name: basicInfoData.value.name,
      email: basicInfoData.value.email,
      addressLine: basicInfoData.value.addressLine,
      municipality: basicInfoData.value.municipality,
      city: basicInfoData.value.city,
      postalCode: basicInfoData.value.postalCode,
      phone_number: basicInfoData.value.phone_number,
    };

    const response = await usersStore.changeUserInformation(userInfo);

  } catch (error) {
    console.log('Error in ' + error);
  }
}

const addAddress = async () => {
  try {
    const userInfo = {
      name: basicInfoData.value.name,
      email: basicInfoData.value.email,
      addressLine: basicInfoData.value.addressLine,
      municipality: basicInfoData.value.municipality,
      city: basicInfoData.value.city,
      postalCode: basicInfoData.value.postalCode,
      phone_number: basicInfoData.value.phone_number
    };

    await usersStore.addUserAddress(userInfo);
  } catch (error) {
    console.log('Error in ' + error);
  }
}

const changeAddress = async () => {
  try {
    const userInfo = {
      name: basicInfoData.value.name,
      email: basicInfoData.value.email,
      addressLine: basicInfoData.value.addressLine,
      municipality: basicInfoData.value.municipality,
      city: basicInfoData.value.city,
      postalCode: basicInfoData.value.postalCode,
      phone_number: basicInfoData.value.phone_number,
    };

    await usersStore.changeUserAddresss(userInfo);

  } catch (error) {
    console.log('Error in ' + error);
  }
}

const saveChanges = async () => {
  const userInfo = {
    name: basicInfoData.value.name,
    email: basicInfoData.value.email,
    addressLine: basicInfoData.value.addressLine,
    municipality: basicInfoData.value.municipality,
    city: basicInfoData.value.city,
    postalCode: basicInfoData.value.postalCode,
    phone_number: basicInfoData.value.phone_number,
  };

  changeInformation(userInfo);

  if (basicInfoData.value.addressLine == null && basicInfoData.value.municipality == null && basicInfoData.value.city == null && basicInfoData.value.postalCode == null) {
    addAddress(userInfo);
    Swal.fire('Success', 'Updated Basic Information Successfully!', 'success');
  } else {
    changeAddress(userInfo);
    Swal.fire('Success', 'Updated Basic Information Successfully!', 'success');
  }
}

const changePassword = async () => {
  const DataChangePassword = {
    current_password: changePasswordData.value.current_password,
    new_password: changePasswordData.value.new_password,
    confirm_new_password: changePasswordData.value.confirm_new_password
  };

  if (changePasswordData.value.new_password !== changePasswordData.value.confirm_new_password) {
    return Swal.fire('Error', 'New Password Mismatch!', 'error');
  }

  const response = await usersStore.changePassword(DataChangePassword);
  if (response.status === 200) {
    changePasswordData.value.current_password = null;
    changePasswordData.value.new_password = null;
    changePasswordData.value.confirm_new_password = null;
    Swal.fire('Success', response.message, 'success');
  } else {
    Swal.fire('Error', response.message, 'error');
  }

}

onMounted(() => {
  renderUserInfo();
  renderAddressInfo();
});
</script>

<template>
  <AdminLayout>
    <div class="mx-auto my-10 space-y-8 lg:px-48">
      <!-- Basic Information -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-5">Basic Information</h2>

        <div class="space-y-4">
          <!-- Full Name -->
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Full Name</label>
            <div class="relative flex items-center gap-2 rounded border border-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-3 text-gray-500" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
              </svg>
              <input type="text" v-model="basicInfoData.name" class="grow p-2 outline-none bg-transparent" placeholder="Enter Name" />
            </div>
          </div>

          <!-- Email Address -->
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Email Address</label>
            <div class="relative flex items-center gap-2 rounded border border-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-3 text-gray-500" fill="currentColor" viewBox="0 0 16 16">
                <path d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                <path d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
              </svg>
              <input type="email" v-model="basicInfoData.email" class="grow p-2 outline-none bg-transparent" placeholder="Enter Email" />
            </div>
          </div>
        </div>

        <h2 class="text-xl font-semibold text-gray-800 border-b pb-3 mt-8 mb-5">Contact Details</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Phone Number</label>
            <input type="text" v-model="basicInfoData.phone_number" class="w-full p-3 border rounded-lg" placeholder="Enter phone number" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Address Line</label>
            <input type="text" v-model="basicInfoData.addressLine" class="w-full p-3 border rounded-lg" placeholder="Enter Address Line" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Municipality</label>
            <input type="text" v-model="basicInfoData.municipality" class="w-full p-3 border rounded-lg" placeholder="Enter Municipality" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">City</label>
            <input type="text" v-model="basicInfoData.city" class="w-full p-3 border rounded-lg" placeholder="Enter City" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Postal Code</label>
            <input type="text" v-model="basicInfoData.postalCode" class="w-full p-3 border rounded-lg" placeholder="Enter Postal Code" />
          </div>
        </div>

        <button @click="saveChanges" class="btn md:btn-wide primary-btn-bg text-white mt-6">Save Changes</button>
      </div>

      <!-- Change Password -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 border-b pb-3 mb-5">Change Password</h2>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Current Password</label>
            <input type="password" v-model="changePasswordData.current_password" class="w-full p-3 border rounded-lg" placeholder="Enter Current Password" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">New Password</label>
            <input type="password" v-model="changePasswordData.new_password" class="w-full p-3 border rounded-lg" placeholder="Enter New Password" />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-600 mb-2">Confirm New Password</label>
            <input type="password" v-model="changePasswordData.confirm_new_password" class="w-full p-3 border rounded-lg" placeholder="Confirm New Password" />
          </div>
        </div>
        <button @click="changePassword" class="btn md:btn-wide primary-btn-bg text-white mt-6">Change Password</button>
      </div>
    </div>
  </AdminLayout>
</template>