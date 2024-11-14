<script setup>
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useUsersStore } from '@/stores/users';
import DashboardLayout from '@/components/DashboardLayout.vue';
import Swal from 'sweetalert2';

const basicInfoData = ref({
  name: null,  
  email: null,
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
  try {
    const response = await usersStore.getAddressInformation();
    const data = response.data;
    basicInfoData.value.addressLine = data.address;
    basicInfoData.value.municipality = data.municipality;
    basicInfoData.value.city = data.city;
    basicInfoData.value.postalCode = data.postal_code;
  } catch (error) {
    console.log('Error in ' + error);
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
  };

  changeInformation(userInfo);

  if(basicInfoData.value.addressLine == null && basicInfoData.value.municipality == null && basicInfoData.value.city == null && basicInfoData.value.postalCode == null){
    addAddress(userInfo);
    Swal.fire('success', 'Address successfully added!', 'success');
  }else{
    changeAddress(userInfo);
    Swal.fire('success', 'Address Updated!', 'success');
  }
}

const changePassword = async () => {
  const DataChangePassword = {
    current_password: changePasswordData.value.current_password,
    new_password: changePasswordData.value.new_password,
    confirm_new_password: changePasswordData.value.confirm_new_password
  };

  if(changePasswordData.value.new_password === changePasswordData.value.confirm_new_password){
    const response = await usersStore.changePassword(DataChangePassword);
    console.log(response);
    Swal.fire('success', response.data.message, 'success');
  }else{
    alert('Password not match!');
  }


}

onMounted(() => {
  renderUserInfo();
  renderAddressInfo();
});
</script>

<template>
  <DashboardLayout>
    <div class="pt-4 lg:pt-8 lg:px-24 2xl:px-40 pb-10">
      <div class="flex items-center gap-2 mb-6">
        <svg class="w-[38px] h-[38px] text-gray-700" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
          <circle cx="12" cy="8" r="4"/>
          <path d="M12 14c-5.33 0-8 2.67-8 6v1h16v-1c0-3.33-2.67-6-8-6z"/>
        </svg>
        <h2 class="text-2xl font-semibold text-gray-700">My Account Setting</h2>
      </div>

      <!-- Basic Information -->
      <div class="bg-white mt-4 rounded space-y-4 overflow-x-auto">
        <div class="card border">
          <div class="card-body">
            <h2 class="card-title pb-3">Basic Information</h2>
            <span class="label-text">What is your name?</span>
            <label class="input mb-1 input-bordered flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4 opacity-70">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM12.735 14c.618 0 1.093-.561.872-1.139a6.002 6.002 0 0 0-11.215 0c-.22.578.254 1.139.872 1.139h9.47Z" />
              </svg>
              <input type="text" v-model="basicInfoData.name" class="grow" placeholder="Enter Name" />
            </label>
            <span class="label-text">What is your email?</span>
            <label class="input mb-1 input-bordered flex items-center gap-2">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 16 16"
                fill="currentColor"
                class="h-4 w-4 opacity-70">
                <path
                  d="M2.5 3A1.5 1.5 0 0 0 1 4.5v.793c.026.009.051.02.076.032L7.674 8.51c.206.1.446.1.652 0l6.598-3.185A.755.755 0 0 1 15 5.293V4.5A1.5 1.5 0 0 0 13.5 3h-11Z" />
                <path
                  d="M15 6.954 8.978 9.86a2.25 2.25 0 0 1-1.956 0L1 6.954V11.5A1.5 1.5 0 0 0 2.5 13h11a1.5 1.5 0 0 0 1.5-1.5V6.954Z" />
              </svg>
              <input type="text" v-model="basicInfoData.email" class="grow" placeholder="Email" />
            </label>

            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-1">
                  <span class="label-text">Address Line</span>
                  <label class="input mb-1 input-bordered flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                          <circle cx="12" cy="9" r="2.5"/>
                      </svg>
                      <input type="text" v-model="basicInfoData.addressLine" class="grow" placeholder="Enter Address Line" />
                  </label>
              </div>

              <div class="col-span-1">
                  <span class="label-text">Municipality</span>
                  <label class="input mb-1 input-bordered flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                      <circle cx="12" cy="9" r="2.5"/>
                    </svg>
                    <input type="text" v-model="basicInfoData.municipality" class="grow" placeholder="Enter Municipality" />
                  </label>
              </div>

              <div class="col-span-1">
                  <span class="label-text">City</span>
                  <label class="input mb-1 input-bordered flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                          <circle cx="12" cy="9" r="2.5"/>
                      </svg>
                      <input type="text" v-model="basicInfoData.city" class="grow" placeholder="Enter City" />
                  </label>
              </div>

              <div class="col-span-1">
                  <span class="label-text">Postal Code</span>
                  <label class="input mb-1 input-bordered flex items-center gap-2">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                          <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/>
                          <circle cx="12" cy="9" r="2.5"/>
                      </svg>
                      <input type="text" v-model="basicInfoData.postalCode" class="grow" placeholder="Enter Postal Code" />
                  </label>
              </div>
            </div>

            <button class="btn btn-wide" @click="saveChanges">Save Changes</button>
          </div>
        </div>
      </div>

      <!-- Change Password -->
      <div class="bg-white mt-4 rounded space-y-4 overflow-x-auto">
        <div class="card border">
          <div class="card-body">
            <h2 class="card-title pb-3">Change Password</h2>
            <span class="label-text">What is your current password?</span>
            <label class="input mb-1 input-bordered flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <rect x="5" y="10" width="14" height="10" rx="2"/>
                <path d="M8 10V7a4 4 0 1 1 8 0v3"/>
                <circle cx="12" cy="15" r="1"/>
                <rect x="11.5" y="15.5" width="1" height="3" rx="0.5"/>
              </svg>
              <input type="password" v-model="changePasswordData.current_password" class="grow" placeholder="Enter Current Password" />
            </label>
            <span class="label-text">What is your new password?</span>
            <label class="input mb-1 input-bordered flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <rect x="5" y="10" width="14" height="10" rx="2"/>
                <path d="M8 10V7a4 4 0 1 1 8 0v3"/>
                <circle cx="12" cy="15" r="1"/>
                <rect x="11.5" y="15.5" width="1" height="3" rx="0.5"/>
              </svg>
              <input type="password" v-model="changePasswordData.new_password" class="grow" placeholder="Enter New Password" />
            </label>
            <span class="label-text">Confirm new password?</span>
            <label class="input mb-1 input-bordered flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <rect x="5" y="10" width="14" height="10" rx="2"/>
                <path d="M8 10V7a4 4 0 1 1 8 0v3"/>
                <circle cx="12" cy="15" r="1"/>
                <rect x="11.5" y="15.5" width="1" height="3" rx="0.5"/>
              </svg>
              <input type="password" v-model="changePasswordData.confirm_new_password" class="grow" placeholder="Confirm New Password" />
            </label>
            <button class="btn btn-wide" @click="changePassword">Change Password</button>
          </div>
        </div>
      </div>

    </div>
  </DashboardLayout>
</template>