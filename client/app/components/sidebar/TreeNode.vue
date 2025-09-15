<template>
  <li class="relative pl-2 group">
    <span
      class="absolute left-0 top-0 h-full border-l border-gray-300 dark:border-gray-700 opacity-0 group-hover:opacity-50 transition-opacity duration-200"
      style="border-width: 0.5px;"
    ></span>

    <div
      class="flex items-center gap-2 w-full text-left transition-colors relative z-10"
      :class="app.treenode.isActive(node) ? 'text-blue-500' : 'text-gray-500 dark:text-gray-400 hover:text-black dark:hover-text-gray-100'"
    >
      <!-- folder -->
      <button
        v-if="node.type === 'folder'"
        @click="app.treenode.onToggle(node)"
        class="flex items-center gap-2 flex-1 py-1 text-sm"
        :aria-expanded="!!node._open"
      >
        <Icon
          :name="node._open ? 'heroicons:chevron-down' : 'heroicons:chevron-right'"
          class="w-4 h-4 transition-transform duration-150"
        />
        <div class="flex items-center gap-2 truncate">
          <Icon :name="app.treenode.iconName(node)" class="w-4 h-4" />
          <span class="truncate">{{ node.name }}</span>
        </div>

        <span v-if="node._loading" class="ml-2 animate-pulse text-xs text-gray-400">
          Loading...
        </span>
      </button>

      <!-- file -->
      <div
        v-else
        @click="app.sidebar.setActive(node.path)"
        :class="{
          'bg-blue-500 text-white': app.sidebar.activePath === node.path,
          'bg-blue-100': app.sidebar.activePath && app.sidebar.activePath.startsWith(node.path + '/')
        }"
      >
        <div class="flex items-center gap-2 pb-2 truncate">
          <Icon :name="app.treenode.iconName(node)" class="w-4 h-4" />
          <span class="truncate">{{ node.name }}</span>
        </div>
      </div>
    </div>

    <!-- children -->
    <transition name="fade" mode="out-in">
      <ul
        v-if="node.type === 'folder' && node.children && node.children.length && node._open"
        class="ml-4 mt-1 space-y-1 relative"
      >
        <TreeNode v-for="child in node.children" :key="child.path" :node="child" />
      </ul>
    </transition>
  </li>
</template>

<script setup>
import { app } from "~/store/app.js"

defineProps({
  node: { type: Object, required: true }
})
</script>
