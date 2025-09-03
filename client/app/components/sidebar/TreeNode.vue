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
    <Icon :name="iconName" class="w-4 h-4" />
    <span class="truncate">{{ node.name }}</span>
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
    <Icon :name="iconName" class="w-4 h-4" />
    <span class="truncate">{{ node.name }}</span>
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

// icon map
const iconMap = {
  folder: 'heroicons:folder',
  code: 'heroicons:code-bracket',
  image: 'heroicons:photo',
  audio: 'heroicons:musical-note',
  video: 'heroicons:play',
  text: 'heroicons:document-text',
  data: 'heroicons:document',
  json: 'heroicons:document',
  archive: 'heroicons:archive-box',
  pdf: 'heroicons:document',
  presentation: 'heroicons:document',
  spreadsheet: 'heroicons:table-cells',
  config: 'heroicons:cog-6-tooth',
  markdown: 'heroicons:document-text',
  default: 'heroicons:document',
  db: 'heroicons:circle-stack',
  terminal: 'heroicons:command-line'
}

const codeExtensions = ['vue','html','js','css','php','json','xml']
const dataExtensions = ['csv','xls']
const terminalExtensions = ['sh']
const dbExtensions = ['sql','db','sqlite','mdb','accdb','tsv','yaml','yml','plist']
const imageExtensions = ['png','jpg','jpeg','gif','svg','webp','bmp','ico','tiff','heic','avif']
const audioExtensions = ['mp3','wav','ogg','flac','aac','m4a','wma']
const videoExtensions = ['mp4','mov','avi','mkv']
const textExtensions = ['txt','md','log']
const archiveExtensions = ['zip','rar','7z','tar','gz','bz2','xz','iso']

const iconName = computed(() => {
  if (props.node.type === 'folder') return iconMap.folder

  const ext = (props.node.name || '').split('.').pop()?.toLowerCase()

  if (codeExtensions.includes(ext)) return iconMap.code
  if (imageExtensions.includes(ext)) return iconMap.image
  if (audioExtensions.includes(ext)) return iconMap.audio
  if (videoExtensions.includes(ext)) return iconMap.video
  if (textExtensions.includes(ext)) return iconMap.text
  if (dbExtensions.includes(ext)) return iconMap.db
  if (archiveExtensions.includes(ext)) return iconMap.archive
  if (dataExtensions.includes(ext)) return iconMap.spreadsheet
  if (terminalExtensions.includes(ext)) return iconMap.terminal

  return iconMap[props.node.fileType] || iconMap.default
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

