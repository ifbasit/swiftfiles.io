<template>
  <div class="p-4 bg-white dark:bg-gray-900 rounded-lg shadow-md">
    <!-- Toolbar -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex gap-5">
        <button v-for="btn in listings.toolbar_buttons" 
                :key="btn.label"
                @click="btn.action"
                :disabled="btn.disabled"
                :class="listings.btn_class(btn.disabled)">
          <Icon :name="btn.icon" class="w-5 h-5" />
        </button>
      </div>

      <div class="flex gap-3">
        <button @click="listings.view = 'list'" :class="listings.toggle_btn_class(listings.view === 'list')">
          <Icon name="heroicons:list-bullet" class="w-5 h-5" />
        </button>
        <button @click="listings.view = 'grid'" :class="listings.toggle_btn_class(listings.view === 'grid')">
          <Icon name="heroicons:squares-2x2" class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- List View -->
  <div v-if="listings.view === 'list'" class="w-full">
    <!-- Header -->
    <div class="grid grid-cols-[2fr_100px_1.5fr_1fr_1fr] 
                text-xs font-medium text-gray-500 dark:text-gray-400 
                border-b border-gray-200 dark:border-gray-700 pb-2 mb-2">
      <div>Name</div>
      <div>Size</div>
      <div>Last Modified</div>
      <div>Type</div>
      <div>Permissions</div>
    </div>

    <!-- Loading -->
    <ul v-if="sidebar.is_loading" class="ml-4 mt-2 space-y-1">
      <li><span class="ml-2 animate-pulse text-xs text-gray-400">Loading...</span></li>
    </ul>

    <!-- Empty -->
    <ul v-if="sidebar.folders.length == 0 && !sidebar.is_loading" class="ml-4 mt-2 space-y-1">
      <li><span class="ml-2 animate-pulse text-xs text-gray-400">Nothing found here.</span></li>
    </ul>
    <div
    v-if="sidebar.folders.length !== 0 && !sidebar.is_loading"
    :class="['select-none grid hover:text-blue-500 grid-cols-[2fr_100px_1.5fr_1fr_1fr] items-center py-2 px-2 mt-1 rounded-md cursor-pointer transition']"
    >
     
    <div class="flex items-center gap-2">
        <Icon name="heroicons:folder" class="w-4 h-4" />
        <span class="text-sm truncate">...</span>
      </div>
    </div>
    <!-- Rows -->
    <div v-for="file in sidebar.folders" :key="file.name"
        @click="listings.toggle_select(file)"
        @dblclick="sidebar.treenode.toggle(file)"
        :class="['select-none grid grid-cols-[2fr_100px_1.5fr_1fr_1fr] items-center py-2 px-2 mt-1 rounded-md cursor-pointer transition',
          listings.selected.includes(file.name) 
            ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200' 
            : 'hover:text-blue-500']">
      <div class="flex items-center gap-2">
        <Icon :name="sidebar.treenode.icon(file)" class="w-4 h-4" />
        <span class="text-sm truncate">{{ file.name }}</span>
      </div>
      <div class="text-sm">{{ file.size || '-' }}</div>
      <div class="text-sm">{{ file.last_modified }}</div>
      <div class="text-sm">{{ file.file_type || 'Folder' }}</div>
      <div class="text-sm">{{ file.permissions }}</div>
    </div>
  </div>


    <!-- Grid View -->
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
      <div v-for="file in sidebar.folders" :key="file.name"
           @click="listings.toggle_select(file)"
           :class="['select-none flex flex-col items-center p-3 rounded-md cursor-pointer transition',
             listings.selected.includes(file.name) 
               ? 'bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200' 
               : 'hover:bg-gray-100 dark:hover:bg-gray-800']">
        <Icon :name="sidebar.treenode.icon(file)" class="w-[30px] h-[30px]" />
        <span class="text-sm m-1 truncate w-full text-center">{{ file.name }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { sidebar } from "~/store/sidebar"
import { listings } from "~/store/listings"


</script>
