<template>
  <header class="bg-white shadow-sm p-4 flex justify-between items-center">
    <h1 class="text-xl font-semibold ml-12 lg:ml-0">{{ $route.name }}</h1>

    <!-- User profile dropdown -->
    <div class="relative">
      <button class="btn btn-ghost">
        <svg class="w-[24px] h-[24px] text-gray-700" aria-hidden="true"
          xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M20 12H8m12 0-4 4m4-4-4-4M9 4H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h2" />
        </svg>
        <span>Logout</span>
      </button>
    </div>
  </header>
</template>
