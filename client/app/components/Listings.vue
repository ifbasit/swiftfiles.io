<template>
  <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow-md">
    <!-- Toolbar -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex gap-2">
        <button v-for="btn in toolbarButtons" 
                :key="btn.label"
                @click="btn.action"
                :disabled="btn.disabled"
                :class="btnClass(btn.disabled)">
          <Icon :name="btn.icon" class="w-5 h-5" />
        </button>
      </div>

      <div class="flex gap-1">
        <button @click="view = 'list'" :class="toggleBtn(view === 'list')">
          <Icon name="heroicons:list-bullet" class="w-5 h-5" />
        </button>
        <button @click="view = 'grid'" :class="toggleBtn(view === 'grid')">
          <Icon name="heroicons:squares-2x2" class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- List View -->
    <div v-if="view === 'list'" class="w-full">
      <div class="grid grid-cols-5 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700 pb-2 mb-2">
        <div>Name</div>
        <div>Size</div>
        <div>Last Modified</div>
        <div>Type</div>
        <div>Permissions</div>
      </div>
      <div v-for="file in files" :key="file.name"
           @click="toggleSelect(file)"
           :class="['grid grid-cols-5 items-center py-2 px-2 mt-1 rounded-md cursor-pointer transition',
             selected.includes(file.name) 
               ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200' 
               : 'hover:text-blue-500']">
        <div class="flex items-center gap-2">
          <Icon :name="file.icon" class="w-5 h-5" />
          <span class="text-sm truncate">{{ file.name }}</span>
        </div>
        <div class="text-sm">{{ file.size }}</div>
        <div class="text-sm">{{ file.modified }}</div>
        <div class="text-sm">{{ file.type }}</div>
        <div class="text-sm">{{ file.permissions }}</div>
      </div>
    </div>

    <!-- Grid View -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-4">
      <div v-for="file in files" :key="file.name"
           @click="toggleSelect(file)"
           :class="['flex flex-col items-center p-3 rounded-md cursor-pointer transition',
             selected.includes(file.name) 
               ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200' 
               : 'hover:bg-gray-100 dark:hover:bg-gray-800']">
        <Icon :name="file.icon" class="w-8 h-8 mb-1" />
        <span class="text-xs truncate w-full text-center">{{ file.name }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";

const files = ref([
  { name: "Documents", icon: "heroicons:folder", size: "-", modified: "2024-08-01", type: "Folder", permissions: "drwxr-xr-x" },
  { name: "report.pdf", icon: "heroicons:document", size: "2 MB", modified: "2024-08-12", type: "PDF", permissions: "-rw-r--r--" },
  { name: "photo.jpg", icon: "heroicons:photo", size: "1.5 MB", modified: "2024-08-20", type: "Image", permissions: "-rw-r--r--" },
  { name: "music.mp3", icon: "heroicons:musical-note", size: "5 MB", modified: "2024-08-25", type: "Audio", permissions: "-rw-r--r--" },
  { name: "video.mp4", icon: "heroicons:play", size: "20 MB", modified: "2024-08-28", type: "Video", permissions: "-rw-r--r--" },
  { name: "app.js", icon: "heroicons:code-bracket", size: "12 KB", modified: "2024-08-30", type: "Script", permissions: "-rw-r--r--" },
  { name: "app.js", icon: "heroicons:code-bracket", size: "12 KB", modified: "2024-08-30", type: "Script", permissions: "-rw-r--r--" },
  { name: "app.js", icon: "heroicons:code-bracket", size: "12 KB", modified: "2024-08-30", type: "Script", permissions: "-rw-r--r--" },
]);

const view = ref("list");
const selected = ref([]);

const toggleSelect = (file) => {
  if (selected.value.includes(file.name)) {
    selected.value = selected.value.filter(f => f !== file.name);
  } else {
    selected.value.push(file.name);
  }
};

const selectAll = () => {
  selected.value = files.value.map(f => f.name);
};

const unselectAll = () => {
  selected.value = [];
};

const toolbarButtons = computed(() => [
  { label: "Home", icon: "heroicons:home", action: () => {}, disabled: false },
  { label: "Level Up", icon: "heroicons:arrow-up", action: () => {}, disabled: false },
  { label: "Back", icon: "heroicons:arrow-left", action: () => {}, disabled: false },
  { label: "Forward", icon: "heroicons:arrow-right", action: () => {}, disabled: false },
  { label: "Refresh", icon: "heroicons:arrow-path", action: () => {}, disabled: false },
  { label: "Select All", icon: "heroicons:check", action: selectAll, disabled: false },
  { label: "Unselect All", icon: "heroicons:x-mark", action: unselectAll, disabled: selected.value.length === 0 },
]);

const btnClass = (disabled) =>
  `p-2 rounded-md transition ${
    disabled
      ? "text-gray-400 dark:text-gray-600 cursor-not-allowed"
      : "text-gray-600 dark:text-gray-300 hover:text-blue-500"
  }`;

const toggleBtn = (active) =>
  `p-2 rounded-md transition ${
    active
      ? "text-blue-500  dark:text-white"
      : "text-gray-500 hover:text-blue-500"
  }`;
</script>
