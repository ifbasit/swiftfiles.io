<template>
  <div class="flex items-center gap-4 h-[60px] border-b border-gray-200 dark:border-gray-700 px-4 py-2">
    <button class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-blue-500 text-sm">
      <Icon name="heroicons:document-plus" class="h-[17px] w-5" />
      Add file
    </button>
    <button class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-blue-500 text-sm">
      <Icon name="heroicons:folder-plus" class="h-[17px] w-5" />
      Add folder
    </button>
    <button class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-blue-500 text-sm">
      <Icon name="heroicons:arrow-up-tray" class="h-[17px] w-5" />
      Upload
    </button>

    <div class="h-5 w-px bg-gray-300 dark:bg-gray-600"></div>

    <button class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-blue-500 text-sm">
      <Icon name="heroicons:clipboard" class="h-[17px] w-5" />
      Copy
    </button>
    <button class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-blue-500 text-sm">
      <Icon name="heroicons:arrow-right-on-rectangle" class="h-[17px] w-5" />
      Move
    </button>
    <button class="flex items-center gap-1 text-gray-600 dark:text-gray-300 hover:text-blue-500 text-sm">
      <Icon name="heroicons:arrow-down-tray" class="h-[17px] w-5" />
      Download
    </button>
  </div>
</template>

<script setup>
// no logic needed, pure UI toolbar
</script>
