<script setup>
import { ref, onMounted  } from 'vue';
import ApexCharts from 'apexcharts';
import VueApexCharts from 'vue3-apexcharts';
import AdminLayout from '@/components/AdminLayout.vue';
import { useSalesStore } from '@/stores/sales';
import { useUsersStore } from '@/stores/users';

const useSales = useSalesStore(); 
const useUser = useUsersStore(); 
const user = ref(null);
const role = ref(null);

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
    categories: ['Waiting for Delivery', 'Pending Payment', 'Delivered', 'Completed', 'Rated'],
  },
  title: {
    text: 'Refills Status',
    align: 'center',
    style: { fontSize: '16px', fontWeight: '400' },
  },
});

const refillSeries = ref([
  { name: 'Refills', data: [0, 0, 0, 0, 0] },
]);

const tallyRefill = async () => {
  try {
    const response = await useSales.getAllTallySuccessSales();
    const data = response.data;

    const statusWise = data.refills.status_wise;
    const refillsStatusCounts = [
      statusWise['Waiting for Delivery'].total_amount,
      statusWise['Pending Payment'].total_amount,
      statusWise['Delivered'].total_amount,
      statusWise['Completed'].total_amount,
      statusWise['Rated'].total_amount,
    ];

    refillSeries.value = [
      { name: 'Refills', data: refillsStatusCounts }, 
    ];
  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
};

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
  { name: 'Orders', data: [0, 0, 0, 0, 0] },
]);

const tallyOrders = async () => {
  try {
    const response = await useSales.getAllTallySuccessSales();
    const data = response.data;

    const statusWise = data.orders.status_wise;
    const ordersStatusCounts = [
      statusWise['To Pay'].total_amount,
      statusWise['To Receive'].total_amount,
      statusWise['Delivered'].total_amount,
      statusWise['Completed'].total_amount,
      statusWise['Rated'].total_amount,
    ];

    orderSeries.value = [
      { name: 'Orders', data: ordersStatusCounts }, 
    ];
  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
};

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
  { name: 'Sales', data: [0, 0, 0, 0] },
]);

const tallySales = async () => {
  try {
    const response = await useSales.getAllSuccessSalesData();
    const data = response.data;

    const statusWise = data;
    const salesStatusCounts = [
      statusWise['daily_sales'],
      statusWise['monthly_sales'],
      statusWise['yearly_sales'],
      statusWise['total_sales_amount'],
    ];

    salesSeries.value = [
      { name: 'Sales', data: salesStatusCounts }, 
    ];
  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

const userInformation = async () => {
  try {
    const response = await useUser.getInformation();
    const data = response.data;
    user.value = data.name;
    role.value = data.user_role;
  } catch (error) {
    console.error('Error fetching gallon data:', error);
  }
}

onMounted(() => {
  tallyRefill();
  tallySales();
  userInformation();
  tallyOrders();
});

</script>

<template>
  <AdminLayout>
    <div class="mb-5 mt-10">
      <div class="mb-5 px-3 space-y-2">
        <h2 class="text-gray-700 font-bold text-2xl"><span class="font-medium">Hi</span>, {{ user }}<span
            class="font-medium">👋</span></h2>
        <p class="font-medium text-base">Logged in as
          <span class="text-cyan-700 capitalize">{{ role }}</span>!
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
