<script setup>
import AdminLayout from '@/components/AdminLayout.vue';

</script>

<template>
  <AdminLayout>
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-3">
      <div class="card bg-base-100 border">
        <div class="card-body">
          <h2 class="card-title">Card title!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
        </div>
      </div>

      <div class="card bg-base-100 border">
        <div class="card-body">
          <h2 class="card-title">Card title!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
        </div>
      </div>

      <div class="card bg-base-100 border">
        <div class="card-body">
          <h2 class="card-title">Card title!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
        </div>
      </div>

      <div class="card bg-base-100 border">
        <div class="card-body">
          <h2 class="card-title">Card title!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[75%_24%] gap-3">
      <div class="card bg-base-100 border h-[40vh] mt-4">
        <div class="card-body">
          <h2 class="card-title">Card title!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
        </div>
      </div>

      <div class="card bg-base-100 border h-[40vh] mt-4">
        <div class="card-body">
          <h2 class="card-title">Card title!</h2>
          <p>If a dog chews shoes whose shoes does he choose?</p>
        </div>
      </div>
    </div>

    <div class="card bg-base-100 border h-[25vh] mt-4">
      <div class="card-body">
        <h2 class="card-title">Card title!</h2>
        <p>If a dog chews shoes whose shoes does he choose?</p>
      </div>
    </div>
  </AdminLayout>
</template>