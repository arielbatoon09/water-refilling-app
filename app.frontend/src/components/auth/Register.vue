<script setup>
import { ref } from 'vue';
import { RouterLink, useRouter } from 'vue-router';
import Logo from '@/assets/logo.png';
import { useAuthStore } from '../../stores/auth';
import Swal from 'sweetalert2';

defineEmits(['authToggle']);

const authStore = useAuthStore();
const router = useRouter();

const FormData = ref({
  name: null,
  email: null,
  password: null,
});

const handleRegister = async () => {
  const response = await authStore.register(FormData.value);
  console.log(response);

  if (response.status === 'success') {
    FormData.value.name = null;
    FormData.value.email = null;
    FormData.value.password = null;

    router.push('/home');

    Swal.fire({
      title: 'Perfect',
      text: 'Successfully Created Account!',
      icon: 'success',
      confirmButtonText: 'Confirm'
    });
  } else if (response.status === 'error') {
    Swal.fire({
      title: 'Error!',
      text: response.message,
      icon: 'error',
      confirmButtonText: 'Confirm'
    });
  }
};
</script>

<template>
  <!-- Heading -->
  <div class="-mt-10 lg:mt-0 text-center mb-8 flex flex-col justify-center items-center gap-2">
    <RouterLink to="/">
      <img :src="Logo" width="72" alt="logo" />
    </RouterLink>
    <h2 class="text-4xl font-bold text-gray-600">Create Account</h2>
    <p class="text-base font-medium text-gray-600">Let's get you setup with a new account!</p>
  </div>

  <!-- Form -->
  <div class="mx-auto max-w-lg rounded-lg border">
    <div class="flex flex-col gap-2 p-4 md:p-8">

      <!-- Fullname -->
      <div class="space-y-2 mb-2 inline-block">
        <label for="fullname" class="text-sm text-gray-800 sm:text-base">Full Name</label>
        <input v-model="FormData.name" name="fullname" type="text"
          class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none primary-btn-ring transition duration-100 focus:ring" />
      </div>

      <!-- Email -->
      <div class="space-y-2 mb-2 inline-block">
        <label for="email" class="text-sm text-gray-800 sm:text-base">Email</label>
        <input v-model="FormData.email" name="email" type="email"
          class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none primary-btn-ring transition duration-100 focus:ring" />
      </div>

      <!-- Password -->
      <div class="space-y-2 mb-2 inline-block">
        <label for="password" class="text-sm text-gray-800 sm:text-base">Password</label>
        <input v-model="FormData.password" name="password" type="password"
          class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none primary-btn-ring transition duration-100 focus:ring" />
      </div>

      <!-- Login Button -->
      <button @click="handleRegister" class="btn primary-btn-bg text-white">Create account</button>

      <!-- Redirection -->
      <div class="space-y-4">
        <div class="relative flex items-center justify-center">
          <span class="absolute inset-x-0 h-px bg-gray-300"></span>
          <span class="relative bg-white px-4 text-sm text-gray-400">Already have an account?</span>
        </div>
        <button @click="$emit('authToggle')"
          class="btn btn-ghost border border-gray-300 hover:bg-white hover:border-gray-400 text-gray-500 w-full">Sign in
          to existing account</button>
      </div>
    </div>
  </div>
</template>