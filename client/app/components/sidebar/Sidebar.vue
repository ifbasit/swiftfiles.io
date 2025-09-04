<template>
  <div class="flex flex-col h-dvh w-74 bg-white dark:bg-gray-900 shadow-lg transition-colors duration-300 ease-in-out">
    <!-- Header -->
    <div class="flex items-center justify-center h-18 border-b border-gray-200 dark:border-gray-700 flex-shrink-0 gap-2">
      <img :src="app.darkmode ? logoDark : logoLight" alt="SwiftFiles.io's Logo" class="h-12 w-auto object-contain" />
      <h3 class="text-md text-gray-900 dark:text-gray-100">SwiftFiles.io</h3>

    </div>

    <!-- Content -->
   <!-- Content -->
<div class="flex-1 overflow-auto p-4">
  <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Files</h2>
  <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
    <!-- make user the root TreeNode -->
    <TreeNode
      v-if="!loading && user"
      :node="{ name: user, type: 'folder', path: '/', children: folders }"
      :active-path="activePath"
      
      @set-active="setActive"
    />

    <ul v-else-if="loading" class="ml-4 mt-2 space-y-1">
      <li><span class="ml-2 animate-pulse text-xs text-gray-400">Loading...</span></li>
    </ul>

    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:user-group" class="w-4 h-4" /> Shared with me
    </li>
    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:clock" class="w-4 h-4" /> Recent
    </li>
    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:star" class="w-4 h-4" /> Starred
    </li>
    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:trash" class="w-4 h-4" /> Deleted files
    </li>
  </ul>
</div>


    <!-- Footer / Dark Mode Toggle -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0 flex items-center justify-center">
      <div @click="toggleDarkMode" class="w-14 h-7 flex items-center rounded-full cursor-pointer transition-colors duration-300 ease-in-out relative" :class="app.darkmode ? 'bg-gray-700' : 'bg-gray-300'">
        <Icon name="heroicons:sun" class="absolute left-1 w-4 h-4 text-yellow-500 opacity-70 transition-opacity duration-300" :class="app.darkmode ? 'opacity-30' : 'opacity-70'" />
        <Icon name="heroicons:moon" class="absolute right-1 w-4 h-4 text-gray-400 opacity-70 transition-opacity duration-300" :class="app.darkmode ? 'opacity-70' : 'opacity-30'" />
        <span class="w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out flex items-center justify-center" :class="app.darkmode ? 'translate-x-7' : 'translate-x-0'">
          <Icon :name="app.darkmode ? 'heroicons:moon' : 'heroicons:sun'" class="w-3 h-3 text-gray-600" />
        </span>
      </div>
    </div>
  </div>
</template>


<script setup>
import axios from "axios"
import { reactive, ref, onMounted } from "vue"
import { app } from '~/store/app.js'
import TreeNode from '~/components/sidebar/TreeNode.vue'
import logoLight from '~/assets/images/swiftfilesio-light.png'
import logoDark from '~/assets/images/swiftfilesio-dark.png'
const loading = ref(true)
const folders = reactive([])
const user = ref();

const activePath = ref('')
function setActive(path) {
  activePath.value = path
}

function toggleDarkMode() {
  app.darkmode = !app.darkmode
  document.documentElement.classList.toggle("dark", app.darkmode)
}

async function get_user() {
  try {
    const res = await axios.get(app.endpoint, {
      params: { request: 'user'}
    })
    user.value = res.data.current_user
    return res.data.current_user
  } catch (err) {
    console.error("Error fetching folder tree:", err)
  }
}

// Fetch root directories from server top-level
async function fetchFolders() {
  try {
    const res = await axios.get(app.endpoint, {
      params: { request: 'tree', path: '/' } // always start from top root
    })
    if (Array.isArray(res.data)) {
      folders.splice(0, folders.length, ...res.data)
    } else if (res.data && res.data.items) {
      folders.splice(0, folders.length, ...res.data.items)
    }
    loading.value = false
  } catch (err) {
    console.error("Error fetching folder tree:", err)
  }
}

onMounted(() => {
  get_user();
  fetchFolders()
})
</script>
