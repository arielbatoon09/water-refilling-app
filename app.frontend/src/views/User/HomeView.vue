<script setup>
import DashboardLayout from '@/components/DashboardLayout.vue';

</script>

<template>
  <DashboardLayout>
    <div class="pt-4 lg:pt-8 lg:px-24 2xl:px-40 pb-10">
      <div class="flex items-center gap-4">
        <div class="bg-gray-400 py-4 px-6 rounded-full">
          <p class="text-white font-bold text-3xl text-center">A</p>
        </div>

        <div class="space-y-1">
          <h2 class="font-bold text-gray-700 text-xl lg:text-2xl">Welcome back, User Account!</h2>
          <p class="text-gray-500 text-base">We're glad to see you again</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mt-10 px-4">
        <!-- Profile Information -->
        <div class="border p-7 rounded-xl bg-white space-y-3">
          <h3 class="font-bold text-gray-600 text-xl">Profile Information</h3>

          <div class="text-base">
            <p class="text-gray-500">Email</p>
            <p class="text-gray-600 font-medium">user@gmail.com</p>
          </div>

          <div class="text-base">
            <p class="text-gray-500">Last Logged in</p>
            <p class="text-gray-600 font-medium">Null</p>
          </div>

          <div class="text-base">
            <p class="text-gray-500">Member Since</p>
            <p class="text-gray-600 font-medium">January 1, 2024</p>
          </div>

        </div>

        <!-- Recent Activity -->
        <div class="border p-7 rounded-xl bg-white space-y-3">
          <h3 class="font-bold text-gray-600 text-xl">Recent Activity</h3>

          <div class="text-base">
            <p class="text-gray-500">No recent activity to show.</p>
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