<!-- resources/js/Layouts/AppLayout.vue -->
<script setup>
import Sidebar from '@/Components/Sidebar.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const isAuthenticated = computed(() => page.props.auth.user !== null);
const userName = computed(() => page.props.auth.user?.name);
const userRole = computed(() => page.props.auth.user?.role);
const userAvatar = computed(() => page.props.auth.user?.avatar);
</script>

<template>
  <div class="flex h-screen bg-mint-50">
    <Sidebar
      v-if="isAuthenticated"
      :is-authenticated="isAuthenticated"
      :user-name="userName"
      :user-role="userRole"
      :user-avatar="userAvatar"
    />
    <main class="flex-1 overflow-y-auto p-4">
      <slot></slot>
    </main>
  </div>
</template>
