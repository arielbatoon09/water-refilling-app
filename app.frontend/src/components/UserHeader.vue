<script setup>
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/auth';
import Swal from 'sweetalert2';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = async () => {
  const response = await authStore.logout();
  console.log(response);
  if (response.status === 'success') {
    Swal.fire({
      title: 'Bye Bye',
      text: 'Logout from the system!',
      icon: 'success',
      confirmButtonText: 'Confirm'
    });

    router.push('/auth');
  }
};
</script>

<template>
  <header class="bg-white shadow-sm p-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold ml-12 lg:ml-0"></h1>

    <!-- User profile dropdown -->
    <div class="relative">
      <button @click="handleLogout" class="btn btn-ghost">
        <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
          height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
        </svg>
        <span>Logout</span>
      </button>
    </div>
  </header>
</template>
