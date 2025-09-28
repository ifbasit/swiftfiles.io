import { reactive } from "vue"
import { sidebar } from "./sidebar";

const listings = reactive({
    view: 'list',
    selected: [],
    toggle_select: (file) => {
      let selected = listings.selected;
       if (selected.includes(file.name)) {
        selected = selected.filter(f => f !== file.name);
      } else {
        selected.push(file.name);
      }
    },
    select_all: () => {
      listings.selected = sidebar.folders.map(f => f.name);
    },
    unselect_all: () => {
      listings.selected = [];
    },
    toolbar_buttons: computed(() => { return [
        { label: "Home", icon: "heroicons:home", action: () => {}, disabled: false },
        { label: "Back", icon: "heroicons:arrow-left", action: () => { console.log('x') }, disabled: false },
        { label: "Forward", icon: "heroicons:arrow-right", action: () => { console.log('y') }, disabled: false },
        { label: "Refresh", icon: "heroicons:arrow-path", action: () => {}, disabled: false },
        { label: "Select All", icon: "heroicons:check", action: listings.select_all, disabled: false },
        { label: "Unselect All", icon: "heroicons:x-mark", action: listings.unselect_all, disabled: listings.selected.length === 0 },
      ]
    }),
    btn_class: (disabled) => {
      return `p-2 rounded-md transition ${
          disabled
            ? "text-gray-400 dark:text-gray-600 cursor-not-allowed"
            : "text-gray-600 dark:text-gray-300 hover:text-blue-500"
        }`;
    },
    toggle_btn_class: (active) => {
      return `p-2 rounded-md transition ${
        active
          ? "text-blue-500  dark:text-white"
          : "text-gray-500 hover:text-blue-500"
      }`;
    }
})

export { listings }