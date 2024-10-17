<script setup>
import { RouterLink } from 'vue-router';
import Logo from '@/assets/logo.png';

defineEmits(['authToggle']);
</script>

<template>
  <!-- Heading -->
  <div class="text-center mb-8 flex flex-col justify-center items-center gap-2">
    <RouterLink to="/">
      <img :src="Logo" width="72" alt="logo" />
    </RouterLink>
    <h2 class="text-4xl font-bold text-gray-600">Welcome Back</h2>
    <p class="text-base font-medium text-gray-600">Please login to your account!</p>
  </div>

  <!-- Form -->
  <div class="mx-auto max-w-lg rounded-lg border">
    <div class="flex flex-col gap-4 p-4 md:p-8">

      <!-- Email -->
      <div class="mb-2 inline-block">
        <label for="email" class="text-sm text-gray-800 sm:text-base">Email</label>
        <input name="email" type="email"
          class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none primary-btn-ring transition duration-100 focus:ring" />
      </div>

      <!-- Password -->
      <div class="mb-2 inline-block">
        <label for="password" class="text-sm text-gray-800 sm:text-base">Password</label>
        <input name="password" type="password"
          class="w-full rounded border bg-gray-50 px-3 py-2 text-gray-800 outline-none primary-btn-ring transition duration-100 focus:ring" />
      </div>

      <!-- Login Button -->
      <button class="btn primary-btn-bg text-white">Log in</button>

      <!-- Redirection -->
      <div class="space-y-4">
        <div class="relative flex items-center justify-center">
          <span class="absolute inset-x-0 h-px bg-gray-300"></span>
          <span class="relative bg-white px-4 text-sm text-gray-400">Don't have an account?</span>
        </div>
        <button @click="$emit('authToggle')"
          class="btn btn-ghost border border-gray-300 hover:bg-white hover:border-gray-400 text-gray-500 w-full">Create
          an account</button>
      </div>
    </div>
  </div>
</template>