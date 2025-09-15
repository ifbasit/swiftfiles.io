<template>
  <transition
    appear
    enter-active-class="transform ease-out duration-300"
    enter-from-class="translate-y-2 opacity-0"
    enter-to-class="translate-y-0 opacity-100"
    leave-active-class="transform ease-in duration-200"
    leave-from-class="opacity-100 translate-y-0"
    leave-to-class="opacity-0 translate-y-2"
    @after-leave="app.clear_toast"
  >
    <div
      v-if="visible"
      class="fixed bottom-4 right-4 w-80 flex items-center gap-3 rounded-xl shadow-lg px-4 py-3 text-sm font-medium
             bg-white text-neutral-800 dark:bg-neutral-900 dark:text-neutral-100 border"
      :class="statusClasses"
    >
      <Icon :name="iconName" class="w-5 h-5 shrink-0" :class="iconClasses" />
      <p class="flex-1">{{ message }}</p>
      <button @click="close" class="ml-2 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300">
        <Icon name="heroicons:x-mark" class="w-4 h-4" />
      </button>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, nextTick, computed } from "vue"
import { app } from "~/store/app"

const props = defineProps({
  message: { type: String, required: true },
  type: { type: String, default: "success" },
  duration: { type: Number, default: 3000 }
})

const visible = ref(false)
let timer

const iconName = computed(() =>
  props.type === "error"
    ? "heroicons:x-circle"
    : "heroicons:check-circle"
)

const statusClasses = props.type === "error"
  ? "border-red-200 dark:border-red-700"
  : "border-green-200 dark:border-green-700"

const iconClasses = props.type === "error"
  ? "text-red-500 dark:text-red-400"
  : "text-green-500 dark:text-green-400"

onMounted(async () => {
  await nextTick()
  visible.value = true
  timer = setTimeout(() => (visible.value = false), props.duration)
})

function close() {
  clearTimeout(timer)
  visible.value = false
}
</script>
