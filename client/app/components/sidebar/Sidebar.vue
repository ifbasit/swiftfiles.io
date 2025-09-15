<template>
<div
  :class="['flex flex-col h-dvh bg-white dark:bg-gray-900 shadow-lg transition-all duration-300 ease-in-out relative  min-w-0', app.sidebar.collapsed ? 'w-18' : 'w-64']"
>    
    <!-- Header -->
    <div class="flex items-center pl-2 h-[60px] border-b border-gray-200 dark:border-gray-700 flex-shrink-0 gap-2" :class="app.sidebar.collapsed ? 'justify-center' : ''">
      <img :src="app.darkmode ? logoDark : logoLight" alt="SwiftFiles.io's Logo" class="h-3/4 w-auto object-contain" />
      <h3 v-if="!app.sidebar.collapsed" class="text-md text-gray-900 dark:text-gray-100">SwiftFiles.io</h3>
      


    </div>

    <!-- Content -->
   <!-- Content -->
<div class="flex-1 overflow-auto p-4" v-show="!app.sidebar.collapsed">
  <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Files</h2>
  <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
    <!-- make user the root TreeNode -->
    <TreeNode
  v-if="!app.sidebar.is_loading && app.user"
  :node="app.sidebar.root"
/>


    <ul v-else-if="app.sidebar.is_loading" class="ml-4 mt-2 space-y-1">
      <li><span class="ml-2 animate-pulse text-xs text-gray-400">Loading...</span></li>
    </ul>

    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:clock" class="w-4 h-4" /> Recent
    </li>
    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:star" class="w-4 h-4" /> Starred
    </li>
    <li class="flex items-center gap-2 text-gray-500 hover:text-blue-500 transition">
      <Icon name="heroicons:trash" class="w-4 h-4" /> Trash
    </li>
  </ul>
</div>
<div class="flex-1  p-4" v-show="app.sidebar.collapsed">
  <ul class="space-y-4 text-sm text-gray-600 dark:text-gray-300">
    
    <li @click="app.sidebar.collapsed = !app.sidebar.collapsed" class="flex items-center justify-center gap-4 cursor-pointer text-gray-500 hover:text-blue-500 transition relative group">
      <Icon name="heroicons:folder" class="text-xl " />
      <span class="absolute left-full  px-2 py-1 rounded-md text-xs 
                  bg-gray-700 text-white dark:bg-gray-700 dark:text-gray-100 
                  opacity-0 group-hover:opacity-100 transition 
                  pointer-events-none whitespace-nowrap z-100">
        Files
      </span>
    </li>


    <li class="flex items-center justify-center gap-4 cursor-pointer text-gray-500 hover:text-blue-500 transition relative group">
      <Icon name="heroicons:clock" class="text-xl " />
      <span class="absolute left-full px-2 py-1 rounded-md text-xs 
                   bg-gray-700 text-white dark:bg-gray-700 dark:text-gray-100 
                   opacity-0 group-hover:opacity-100 transition 
                   pointer-events-none whitespace-nowrap">
        Recent
      </span>
    </li>

    <li class="flex items-center justify-center gap-2 cursor-pointer text-gray-500 hover:text-blue-500 transition relative group">
      <Icon name="heroicons:star" class="text-xl " />
      <span class="absolute left-full px-2 py-1 rounded-md text-xs 
                   bg-gray-700 text-white dark:bg-gray-700 dark:text-gray-100 
                   opacity-0 group-hover:opacity-100 transition 
                   pointer-events-none whitespace-nowrap">
        Starred
      </span>
    </li>

    <li class="flex items-center justify-center gap-2 cursor-pointer text-gray-500 hover:text-blue-500 transition relative group">
      <Icon name="heroicons:trash" class="text-xl " />
      <span class="absolute left-full px-2 py-1 rounded-md text-xs 
                   bg-gray-700 text-white dark:bg-gray-700 dark:text-gray-100 
                   opacity-0 group-hover:opacity-100 transition 
                   pointer-events-none whitespace-nowrap">
        Trash
      </span>
    </li>
  </ul>
</div>



    <!-- Footer / Dark Mode Toggle -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0 flex items-center justify-between" :class="app.sidebar.collapsed ? 'flex-col gap-3' : ''">
      <div v-if="!app.sidebar.collapsed" @click="app.helper.toggle_dark_mode()" class="w-14 h-7 flex items-center rounded-full cursor-pointer transition-colors duration-300 ease-in-out relative" :class="app.darkmode ? 'bg-gray-700' : 'bg-gray-300'">
        <Icon name="heroicons:sun" class="absolute left-1 w-4 h-4 text-yellow-500 opacity-70 transition-opacity duration-300" :class="app.darkmode ? 'opacity-30' : 'opacity-70'" />
        <Icon  name="heroicons:moon" class="absolute right-1 w-4 h-4 text-gray-400 opacity-70 transition-opacity duration-300" :class="app.darkmode ? 'opacity-70' : 'opacity-30'" />
        <span class="w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 ease-in-out flex items-center justify-center" :class="app.darkmode ? 'translate-x-7' : 'translate-x-0'">
          <Icon :name="app.darkmode ? 'heroicons:moon' : 'heroicons:sun'" class="w-3 h-3 text-gray-600" />
        </span>
      </div>
      <div v-else @click="app.helper.toggle_dark_mode()" class="h-7 flex items-center rounded-full cursor-pointer transition-colors duration-300 ease-in-out relative" >
        <span class="w-6 h-6 bg-white rounded-full shadow-md  flex items-center justify-center  transition-colors duration-200 hover:bg-gray-50 dark:hover:bg-gray-800" :class="app.darkmode ? 'border-[0.5px] border-gray-700' : 'border-[0.5px] border-gray-300'">
          <Icon :name="app.darkmode ? 'heroicons:moon' : 'heroicons:sun'" class="w-3 h-3 text-gray-600" />
        </span>
      </div>
      <div 
      @click="app.sidebar.collapsed = !app.sidebar.collapsed"
        class="flex items-center justify-center h-[23px] w-8 rounded-md border-[0.5px] 
              border-gray-300 dark:border-gray-700 
              bg-white dark:bg-gray-900
              cursor-pointer transition-colors duration-200
              hover:bg-gray-50 dark:hover:bg-gray-800">
        <Icon 
         
          :name="app.sidebar.collapsed ? 'heroicons:arrow-long-right' : 'heroicons:arrow-long-left'" 
          class="h-4 w-4 text-gray-600 dark:text-gray-300 transition-colors duration-200" 
        />
      </div>
    </div>
  </div>
</template>


<script setup>
import { onMounted } from "vue"
import { app } from '~/store/app.js'
import TreeNode from '~/components/sidebar/TreeNode.vue'
import logoLight from '~/assets/images/swiftfilesio-light.png'
import logoDark from '~/assets/images/swiftfilesio-dark.png'

onMounted (async () => {
  app.user = await app.request.get({ request: 'user' }).then(r => r?.current_user).catch(() => null)

  // ensure root references the reactive folders array
  app.sidebar.root.name = app.user
  app.sidebar.root.children = app.sidebar.folders

  // load folders from server
  app.sidebar.load_folders()
})
</script>