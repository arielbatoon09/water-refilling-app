<script setup>
import UserSidebar from './UserSidebar.vue';
import UserHeader from './UserHeader.vue';
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <UserSidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64 overflow-x-hidden">
      <!-- Header -->
      <UserHeader />
      
      <!-- Content -->
      <div class="w-full p-4">
        <slot />
      </div>
    </div>
  </div>
</template>