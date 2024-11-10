<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useSidebarStore } from '@/stores/sidebar';
import Logo from '@/assets/logo.png';

const router = useRouter();
const sidebarStore = useSidebarStore();

// Define the navigation links
const navLinks = ref([
  { name: 'Dashboard', route: '/admin/dashboard' },
  { name: 'Orders', route: '/admin/order' },
  { name: 'Delivery', route: '/admin/delivery' },
  { name: 'Inventory', route: '/admin/inventory' },
  { name: 'Sales', route: '/admin/sales' },
  { name: 'Feedback', route: '/admin/feedback' },
]);

onMounted(() => {
  // Event listener for viewport changes
  const mediaQuery = window.matchMedia('(min-width: 1024px)');

  const handleMediaQueryChange = (event) => {
    if (event.matches) {
      sidebarStore.openSidebar();
    } else {
      sidebarStore.closeSidebar();
    }
  };

  mediaQuery.addEventListener('change', handleMediaQueryChange);

  // Initial check
  if (!mediaQuery.matches) {
    sidebarStore.closeSidebar();
  }

  onBeforeUnmount(() => {
    mediaQuery.removeEventListener('change', handleMediaQueryChange);
  });
});

// Navigate Page
const navigateTo = (route) => {
  router.push(route);
};
</script>


<template>
  <div class="flex h-screen">
    <!-- Sidebar for Desktop and Mobile -->
    <div :class="[
      'fixed inset-y-0 left-0 z-50 w-64 bg-white text-gray-100 transition-transform duration-300 shadow flex flex-col justify-between',
      sidebarStore.sidebarOpen ? 'translate-x-0' : '-translate-x-full'
    ]">

      <div class="flex justify-between items-center px-4 py-4 bg-[#094b76]">
        <!-- Panel Logo -->
        <div class="flex items-center gap-2">
          <img width="50" :src="Logo" />
          <h2 class="text-base lg:text-xl font-bold">Alexa <span class="font-normal">Water Refilling Station</span></h2>
        </div>
        <!-- Close Button for Mobile -->
        <button class="lg:hidden text-gray-300" @click="closeSidebar">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="flex-1">
        <ul class="space-y-2">
          <li class="text-gray-500 px-4 pt-6 pb-2.5 font-medium">Main Menu</li>
          <li v-for="link in navLinks" :key="link.route" @click="navigateTo(link.route)"
            class="block py-3 px-6 border-r-[3px] border-transparent transition duration-200 hover:bg-[#edf4f9] text-gray-700 font-medium flex items-center gap-4 cursor-pointer"
            :class="{ 'bg-[#edf4f9] border-r-[3px] primary-border-clr': $route.path === link.route }">
            <!-- Dashboard Icon -->
            <svg v-if="link.name === 'Dashboard'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9.143 4H4.857A.857.857 0 0 0 4 4.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 10 9.143V4.857A.857.857 0 0 0 9.143 4Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286A.857.857 0 0 0 20 9.143V4.857A.857.857 0 0 0 19.143 4Zm-10 10H4.857a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286A.857.857 0 0 0 9.143 14Zm10 0h-4.286a.857.857 0 0 0-.857.857v4.286c0 .473.384.857.857.857h4.286a.857.857 0 0 0 .857-.857v-4.286a.857.857 0 0 0-.857-.857Z" />
            </svg>

            <!-- Order Icon -->
            <svg v-if="link.name === 'Orders'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
            </svg>

            <!-- Delivery Icon -->
            <svg v-if="link.name === 'Delivery'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
            </svg>

            <!-- Inventory Icon -->
            <svg v-if="link.name === 'Inventory'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 6h8m-8 6h8m-8 6h8M4 16a2 2 0 1 1 3.321 1.5L4 20h5M4 5l2-1v6m-2 0h4" />
            </svg>

            <!-- Sales Icon -->
            <svg v-if="link.name === 'Sales'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z" />
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M13.5 3c-.169 0-.334.014-.5.025V11h7.975c.011-.166.025-.331.025-.5A7.5 7.5 0 0 0 13.5 3Z" />
            </svg>

            <!-- Feedback Icon -->
            <svg v-if="link.name === 'Feedback'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M7.556 8.5h8m-8 3.5H12m7.111-7H4.89a.896.896 0 0 0-.629.256.868.868 0 0 0-.26.619v9.25c0 .232.094.455.26.619A.896.896 0 0 0 4.89 16H9l3 4 3-4h4.111a.896.896 0 0 0 .629-.256.868.868 0 0 0 .26-.619v-9.25a.868.868 0 0 0-.26-.619.896.896 0 0 0-.63-.256Z" />
            </svg>

            <!-- User Management Icon -->
            <svg v-if="link.name === 'User Management'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
            </svg>

            <!-- Router Name -->
            <span>{{ link.name }}</span>
          </li>

          <li class="text-gray-500 px-4 pt-4 pb-2.5 border-t font-medium">Other</li>
          <li @click="navigateTo('/admin/settings')"
            class="block py-3 px-6 border-r-[3px] border-transparent transition duration-200 hover:bg-[#edf4f9] text-gray-700 font-medium flex items-center gap-4 cursor-pointer"
            :class="{ 'bg-[#edf4f9] border-r-[3px] primary-border-clr': $route.name === 'Account Settings' }">
            <svg class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.name === 'Account Settings' }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5"
                d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>Account Settings</span>
          </li>

          <li @click="navigateTo('/admin/management')"
            class="block py-3 px-6 border-r-[3px] border-transparent transition duration-200 hover:bg-[#edf4f9] text-gray-700 font-medium flex items-center gap-4 cursor-pointer"
            :class="{ 'bg-[#edf4f9] border-r-[3px] primary-border-clr': $route.name === 'User Management' }">
            <svg class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.name === 'User Management' }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5"
                d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
            </svg>
            <span>User Management</span>
          </li>
        </ul>
      </nav>

      <!-- Avatar -->
      <div class="text-gray-700 border-t">
        <div class="p-4 flex items-center gap-4">
          <div class="avatar online">
            <div class="w-12 rounded-full">
              <img src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
            </div>
          </div>
          <div>
            <h3 class="text-gray-700 font-bold text-base">John Doe</h3>
            <p class="-mt-1 font-normal text-gray-600">Administrator</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Toggle Sidebar -->
    <div class="flex-1 absolute top-4 left-0">
      <div class="lg:hidden">
        <button @click="sidebarStore.openSidebar" class="text-gray-600 focus:outline-none btn btn-ghost">
          <svg class="w-[34px] h-[34px] text-gray-800 -ml-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M5 7h14M5 12h14M5 17h10" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Overlay to Close Sidebar -->
    <div v-if="sidebarStore.sidebarOpen" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
      @click="sidebarStore.closeSidebar"></div>
  </div>
</template>