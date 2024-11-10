<script setup>
import { onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useRefillStore } from '@/stores/refill';
import Swal from 'sweetalert2';

const route = useRoute();
const router = useRouter();
const refillStore = useRefillStore();
const source = route.query.source;
const id = route.query.id;
const mop = route.query.mop;
const deliveryType = route.query.deliveryType;

const checkSuccessRefill = async () => {
  if (source === 'refill') {
    const response = await refillStore.paidRefill(id, mop, deliveryType);
    console.log(response);
    if (response.status === 200) {
      Swal.fire({
        title: 'Perfect',
        text: 'Paid via online successfully!',
        icon: 'success',
        confirmButtonText: 'Confirm'
      });
      router.push('/refill');
    }
  } else {
    return;
  }
};

onMounted(() => {
  checkSuccessRefill();
});
</script>

<template>
  <div>
    <!-- <h1>Success Page</h1>
    <p>Source: {{ source }}</p>
    <p>ID: {{ id }}</p> -->
  </div>
</template>
