<script setup>
import { ref } from 'vue';
import ApexCharts from 'apexcharts';
import VueApexCharts from 'vue3-apexcharts';
import AdminLayout from '@/components/AdminLayout.vue';

// Define chart options and data for "Refills Report"
const refillOptions = ref({
  chart: {
    height: '100%',
    type: 'bar',
    toolbar: { show: false },
  },
  plotOptions: {
    bar: { horizontal: false, endingShape: 'rounded' },
  },
  dataLabels: { enabled: false },
  xaxis: {
    categories: ['Waiting for Delivery', 'Pending Payment', 'Delivered', 'Completed'],
  },
  title: {
    text: 'Refills Status',
    align: 'center',
    style: { fontSize: '16px', fontWeight: '400' },
  },
});
const refillSeries = ref([
  { name: 'Refills', data: [2, 2, 3, 3] },
]);

// Define chart options and data for "Orders Report"
const orderOptions = ref({
  chart: {
    height: '100%',
    type: 'bar',
    toolbar: { show: false },
  },
  plotOptions: {
    bar: { horizontal: false, endingShape: 'rounded' },
  },
  dataLabels: { enabled: false },
  xaxis: {
    categories: ['To Pay', 'To Receive', 'Delivered', 'Completed', 'Rated'],
  },
  title: {
    text: 'Orders Status',
    align: 'center',
    style: { fontSize: '16px', fontWeight: '400' },
  },
});
const orderSeries = ref([
  { name: 'Orders', data: [2, 2, 3, 3, 3] },
]);

// Define chart options and data for "Sales Report"
const salesOptions = ref({
  chart: {
    height: '100%',
    type: 'bar',
    toolbar: { show: false },
  },
  plotOptions: {
    bar: { horizontal: false, endingShape: 'rounded' },
  },
  dataLabels: { enabled: false },
  xaxis: {
    categories: ['Daily Sales', 'Monthly Sales', 'Yearly Sales', 'Overall Sales'],
  },
  title: {
    text: 'Sales Overview',
    align: 'center',
    style: { fontSize: '16px', fontWeight: '400' },
  },
});
const salesSeries = ref([
  { name: 'Sales', data: [2, 2, 3, 3] },
]);


</script>

<template>
  <AdminLayout>
    <!-- Main Grid Layout -->
    <div class="mb-5 mt-10">
      <div class="mb-5 px-3 space-y-2">
        <h2 class="text-gray-700 font-bold text-2xl"><span class="font-medium">Hi</span>, Admin Account<span
            class="font-medium">👋</span></h2>
        <p class="font-medium text-base">Logged in as
          <span class="text-cyan-700 capitalize">Admin</span>!
        </p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Refills Report Card with ApexChart -->
        <div class="card bg-white border rounded-lg hover:shadow transition-shadow duration-300">
          <div class="card-body p-6">
            <h2 class="card-title text-xl font-bold text-[#094b76] mb-4">Refills Report</h2>
            <apexchart class="w-full h-60" type="bar" :options="refillOptions" :series="refillSeries"></apexchart>
          </div>
        </div>

        <!-- Orders Report Card with ApexChart -->
        <div class="card bg-white border rounded-lg hover:shadow transition-shadow duration-300">
          <div class="card-body p-6">
            <h2 class="card-title text-xl font-bold text-[#094b76] mb-4">Orders Report</h2>
            <apexchart class="w-full h-60" type="bar" :options="orderOptions" :series="orderSeries"></apexchart>
          </div>
        </div>

        <!-- Sales Report Card with ApexChart -->
        <div class="card bg-white border rounded-lg hover:shadow transition-shadow duration-300">
          <div class="card-body p-6">
            <h2 class="card-title text-xl font-bold text-[#094b76] mb-4">Sales Report</h2>
            <apexchart class="w-full h-60" type="bar" :options="salesOptions" :series="salesSeries"></apexchart>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
