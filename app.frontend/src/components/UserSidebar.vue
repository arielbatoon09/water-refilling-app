<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { useSidebarStore } from '@/stores/sidebar';
import Logo from '@/assets/logo.png';

const router = useRouter();
const sidebarStore = useSidebarStore();

// Define the navigation links
const navLinks = ref([
  { name: 'Home', route: '/home' },
  { name: 'Refill Water', route: '/refill' },
  { name: 'Shop', route: '/shop' },
  { name: 'Cart', route: '/cart' },
  { name: 'My Purchase', route: '/purchase' },
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

      <div class="flex justify-between items-center px-4 py-4 bg-white">
        <!-- Panel Logo -->
        <div class="flex items-center gap-2">
          <img width="50" :src="Logo" />
          <h2 class="text-base lg:text-xl font-bold text-[#4a97ca]">Alexa Water Refilling Station</h2>
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
            <!-- Home Icon -->
            <svg v-if="link.name === 'Home'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
            </svg>

            <!-- Refill Icon -->
            <svg v-if="link.name === 'Refill Water'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M4 10h16m-8-3V4M7 7V4m10 3V4M5 20h14a1 1 0 0 0 1-1V7a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Zm3-7h.01v.01H8V13Zm4 0h.01v.01H12V13Zm4 0h.01v.01H16V13Zm-8 4h.01v.01H8V17Zm4 0h.01v.01H12V17Zm4 0h.01v.01H16V17Z" />
            </svg>

            <!-- Shop Icon -->
            <svg v-if="link.name === 'Shop'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312" />
            </svg>

            <!-- Cart Icon -->
            <svg v-if="link.name === 'Cart'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
            </svg>

            <!-- Purchase Icon -->
            <svg v-if="link.name === 'My Purchase'" class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.path === link.route }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z" />
            </svg>

            <!-- Router Name -->
            <span>{{ link.name }}</span>
          </li>

          <!-- <li class="text-gray-500 px-4 pt-4 pb-2.5 border-t font-medium">Other</li>
          <li @click="navigateTo('/admin/settings')"
            class="block py-3 px-6 border-r-[3px] border-transparent transition duration-200 hover:bg-[#edf4f9] text-gray-700 font-medium flex items-center gap-4 cursor-pointer"
            :class="{ 'bg-[#edf4f9] border-r-[3px] primary-border-clr': $route.name === 'Account Settings' }">
            <svg class="w-[24px] h-[24px] text-gray-700"
              :class="{ 'text-[#094b76]': $route.name === 'Account Settings' }" aria-hidden="true"
              xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
              <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round" stroke-width="1.5"
                d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>Other 1</span>
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
            <span>Other 2</span>
          </li> -->
        </ul>
      </nav>

      <!-- Avatar -->
      <div class="text-gray-700 border-t">
        <div class="p-4 -ml-8">
          <p class="text-gray-500 text-sm text-center">Version 1.0.0</p>
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