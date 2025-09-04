<template>
  <li class="relative pl-2 group">
  <!-- Vertical line for tree connector -->
  <span
    class="absolute left-0 top-0 h-full border-l border-gray-300 dark:border-gray-700 opacity-0 group-hover:opacity-50 transition-opacity duration-200"
    style="border-width: 0.5px;"
  ></span>

<div
  class="flex items-center gap-2 w-full text-left transition-colors relative z-10"
  :class="isActive ? 'text-blue-500' : 'text-gray-500 dark:text-gray-400 hover:text-black dark:hover-text-gray-100'"
>
  <!-- folder: clickable toggler -->
  <button
    v-if="isFolder"
    @click="onToggle"
    class="flex items-center gap-2 flex-1 py-1 text-sm"
    :aria-expanded="open"
  >
    <Icon
      :name="open ? 'heroicons:chevron-down' : 'heroicons:chevron-right'"
      class="w-4 h-4 transition-transform duration-150"
    />
    <div class="flex items-center gap-2 truncate"><Icon :name="iconName" class="w-4 h-4" />
    <span class="truncate">{{ node.name }}</span></div>
    
    <span v-if="loading" class="ml-2 animate-pulse text-xs text-gray-400">Loading...</span>
  </button>

  <!-- file: plain row -->
  <div
    v-else
    @click="$emit('set-active', props.node.path)"
    :class="{
    // highlight if this exact node is active
    'bg-blue-500 text-white': activePath === node.path,
    // keep parent highlighted if a child is active
    'bg-blue-100': activePath.startsWith(node.path + '/')
  }"
  >
  <div class="flex items-center gap-2 pb-2  truncate">
    <Icon :name="iconName" class="w-4 h-4" />
    <span class="truncate">{{ node.name }}</span>
  </div>
    
  </div>
</div>


    <!-- children (expand/collapse) -->
    <transition name="fade" mode="out-in">
      <ul
        v-if="isFolder && children && children.length && open"
        class="ml-4 mt-1 space-y-1 relative"
      >
       <TreeNode
          v-for="child in children"
          :key="child.path"
          :node="child"
          :root-path="''"
          :active-path="activePath"
          @toggle-load="$emit('toggle-load', $event)"
          @set-active="$emit('set-active', $event)"  
        />

      </ul>
    </transition>
  </li>
</template>
<script setup>
import { ref, computed, watch } from 'vue'
import axios from 'axios'
import { app } from '~/store/app.js'

const props = defineProps({
  node: { type: Object, required: true },
  activePath: { type: String, default: '' }
})

const emit = defineEmits(['toggle-load', 'set-active'])

const isFolder = computed(() => props.node.type === 'folder')
const open = ref(false)
const loading = ref(false)
const children = ref(props.node.children ?? null)

// active state check (only active if path matches AND folder is open, or if it's a file)
const isActive = computed(() => {
  const match = props.activePath === props.node.path
  return match && (!isFolder.value || open.value)
})

const iconName = computed(() => {
  if (props.node.type === 'folder') return 'heroicons:folder'

  const ext = (props.node.name || '').split('.').pop()?.toLowerCase()
  if (!ext) return 'heroicons:document'

  for (const group of app.extensions) {
    const key = Object.keys(group).find(k => k !== 'icon')
    if (key && group[key].includes(ext)) {
      return group.icon
    }
  }

  return 'heroicons:document'
})


// full path comes from server
function buildPath() {
  return props.node.path
}

async function loadChildren() {
  if (children.value !== null) return
  loading.value = true
  try {
    const fullPath = buildPath()
    const res = await axios.get(app.endpoint, {
      params: { request: 'tree', path: fullPath }
    })
    let items = []
    if (Array.isArray(res.data)) items = res.data
    else if (res.data && res.data.items) items = res.data.items

    children.value = items
    emit('toggle-load', { path: fullPath, items })
  } catch (err) {
    console.error('Error loading children for', props.node.name, err)
    children.value = []
  } finally {
    loading.value = false
  }
}

async function onToggle() {
  open.value = !open.value

  if (open.value) {
    await loadChildren()
    emit('set-active', props.node.path)
  } else if (props.activePath === props.node.path) {
    let parts = props.node.path.split('/').filter(Boolean)
    parts.pop()
    let parentPath = '/' + parts.join('/')
    if (parentPath === '') parentPath = '/'
    console.log('collapse → set-active:', parentPath)
    emit('set-active', parentPath)
  }

}


watch(() => props.node.children, (v) => {
  if (v) children.value = v
})
</script>

