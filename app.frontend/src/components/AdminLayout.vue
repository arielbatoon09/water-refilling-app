<script setup>
import Sidebar from './Sidebar.vue'
import AdminHeader from './AdminHeader.vue'
</script>

<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <Sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64 overflow-x-hidden">
      <!-- Header -->
      <AdminHeader />
      
      <!-- Content -->
      <div class="w-full p-4">
        <slot />
      </div>
    </div>
  </div>
</template>
