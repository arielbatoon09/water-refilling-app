<script setup>
import { shallowRef } from 'vue';
import AuthLayout from '@/components/auth/Layout.vue';
import Login from '@/components/auth/Login.vue';
import Register from '@/components/auth/Register.vue';

const currentAuthUI = shallowRef(Login);

function toggleAuthCMP() {
    currentAuthUI.value = currentAuthUI.value === Login ? Register : Login;
}
</script>

<template>
  <AuthLayout>
    <!-- Render Either Login or Register UI -->
    <component :is="currentAuthUI" @authToggle="toggleAuthCMP"></component>
  </AuthLayout>
</template>