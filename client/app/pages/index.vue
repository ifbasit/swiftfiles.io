<template>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <div class="border-r border-gray-200 dark:border-gray-700 flex-shrink-0">
      <Sidebar />
    </div>

    <!-- Main area -->
    <div class="flex flex-col flex-1 min-w-0">
      <!-- Toolbar -->
      <div class="">
        <Toolbar />
      </div>

      <!-- Content -->
      <div class="flex-1 overflow-y-auto p-6 text-gray-700 dark:text-gray-300">
        <listings />
      </div>
    </div>
    <Toast
    v-if="app.toast.state"
    :message="app.toast.message"
    :type="app.toast.type"
    :duration="app.toast.duration"
  />


    

  </div>
</template>

<script setup>
import Toast from '~/components/ui/Toast.vue';
import { app } from '~/store/app';


</script>