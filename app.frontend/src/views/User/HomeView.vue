<script setup>
import dayjs from 'dayjs';
import { ref, onMounted  } from 'vue';
import DashboardLayout from '@/components/DashboardLayout.vue';
import { useUsersStore } from '@/stores/users';

const useUser = useUsersStore();
const name = ref(null);
const email = ref(null);
const email_verified_at = ref(null);
const user_role = ref(null);
const flag = ref(null);
const created_at = ref(null);
const updated_at = ref(null);
const latestRefill = ref(null);
const latestOrder = ref(null);

const userInformation = async () => {
  try {
    const response = await useUser.getInformation();
    const data = response.data;

    name.value = data.name;
    email.value = data.email;
    email_verified_at.value = data.email_verified_at;
    user_role.value = data.user_role;
    flag.value = data.flag;
    created_at.value = data.created_at;
    updated_at.value = data.updated_at;

  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

const date = (date) => {
  return dayjs(date).format('MMMM DD, YYYY');
}

const userLatestRefill = async () => {
  try {
    const response = await useUser.getLatestRefill();
    const data = response.data;

    latestRefill.value = 'You booked a water delivery kindly with a Refill Id: #' + data.id;

  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

const userLatestOrder = async () => {
  try {
    const response = await useUser.getLatestOrder();
    const data = response.data;

    const itemsLength = data.length || 0;

    latestOrder.value = 'You purchase '+itemsLength+' item(s) with a ' + data[0].refid;

  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}


onMounted(() => {
  userInformation();
  userLatestRefill();
  userLatestOrder();
});

</script>

<template>
  <DashboardLayout>
    <div class="pt-4 lg:pt-8 lg:px-24 2xl:px-40 pb-10">
      <div class="flex items-center gap-4">
        <div class="bg-gray-400 w-20 h-20 rounded-full overflow-hidden">
          <p class="w-full h-full text-white font-bold text-3xl text-center flex justify-center items-center">{{ name ? name.charAt(0) : 'A' }}</p>
        </div>

        <div class="space-y-1">
          <h2 class="font-bold text-gray-700 text-xl lg:text-2xl">Welcome back, {{ name }}!</h2>
          <p class="text-gray-500 text-base">We're glad to see you again</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-10 px-4">
        <!-- Profile Information -->
        <div class="border p-7 rounded-xl bg-white space-y-3">
          <h3 class="font-bold text-gray-600 text-xl">Profile Information</h3>

          <div class="text-base">
            <p class="text-gray-500">Email</p>
            <p class="text-gray-600 font-medium">{{ email }}</p>
          </div>

          <div class="text-base">
            <p class="text-gray-500">Last Logged in</p>
            <p class="text-gray-600 font-medium">{{ date(updated_at) }}</p>
          </div>

          <div class="text-base">
            <p class="text-gray-500">Member Since</p>
            <p class="text-gray-600 font-medium">{{ date(created_at) }}</p>
          </div>

        </div>

        <!-- Recent Activity -->
        <div class="border p-7 rounded-xl bg-white space-y-3">
          <h3 class="font-bold text-gray-600 text-xl">Recent Activity</h3>

          <div class="text-base">
            <p class="text-gray-500">-> {{ latestRefill }}</p>
            <p class="text-gray-500">-> {{ latestOrder }}</p>
          </div>
        </div>

        <!-- Recent Activity -->
        <div class="border p-7 rounded-xl bg-white space-y-3">
          <h3 class="font-bold text-gray-600 text-xl">Quick Actions</h3>

          <div class="text-base space-y-3">
            <p class="text-gray-600 font-medium hover:underline cursor-pointer">Book Refill</p>
            <p class="text-gray-600 font-medium hover:underline cursor-pointer">Browse Shop</p>
            <p class="text-gray-600 font-medium hover:underline cursor-pointer">Edit Profile</p>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>