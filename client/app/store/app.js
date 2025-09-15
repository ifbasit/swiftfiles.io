import { reactive } from "vue"
import axios from "axios"

const app = reactive({
  endpoint: 'http://localhost:8888/swiftfilesio/server/',
  darkmode: false,
  toast: {
    state: false,
    message: null,
    duration: 0,
    type: null
  },
  clear_toast: () => {
    app.set_toast({
      state: false,
      message: null,
      duration: 0,
      type: null
    })
  },
  set_toast: (toast) => {
    const { state, message, duration, type } = toast;
    app.toast.state = state;
    app.toast.message = message;
    app.toast.duration = duration;
    app.toast.type = type;
  },
  user: null,

  // Sidebar
  sidebar: {
    collapsed: false,
    folders: [],
    is_loading: true,
    // root is a reactive object inside app so adding node props (_open/_loading) will be reactive
    root: { name: null, type: 'folder', path: '/', children: [] },

    load_folders: async () => {
      const res = await app.request.get({ request: 'tree', path: '/' })
      let items = []
      if (Array.isArray(res)) items = res
      else if (res && res.items) items = res.items
      else items = []

      // update folders array (reactive)
      app.sidebar.folders.splice(0, app.sidebar.folders.length, ...items)

      // ensure root.children references the reactive folders array
      app.sidebar.root.children = app.sidebar.folders

      app.sidebar.is_loading = false
    },

    activePath: "",
    setActive(path) {
      app.sidebar.activePath = path
    }
  },

  // Treenode logic — PER NODE state (no global open/loading/children)
  treenode: {
    iconName(node) {
      if (node.type === "folder") return "heroicons:folder"
      const ext = (node.name || "").split('.').pop()?.toLowerCase()
      if (!ext) return "heroicons:document"

      for (const group of app.extensions || []) {
        const key = Object.keys(group).find(k => k !== 'icon')
        if (key && group[key].includes(ext)) {
          return group.icon
        }
      }
      return "heroicons:document"
    },

    // load children for a specific node (attaches node.children)
    async loadChildren(node) {
      // if server already gave children, don't re-load
      if (node.children != null) return
      node._loading = true
      try {
        const res = await app.request.get({ request: 'tree', path: node.path })
        let items = []
        if (Array.isArray(res)) items = res
        else if (res && res.items) items = res.items
        else items = []

        // attach children to the node
        node.children = items
      } catch (err) {
        console.error("Error loading children for", node.path, err)
        node.children = []
      } finally {
        node._loading = false
      }
    },

    // toggle per-node open state
    async onToggle(node) {
      if (node.type !== 'folder') return

      // toggle this node only
      node._open = !node._open

      if (node._open) {
        await app.treenode.loadChildren(node)
        app.sidebar.setActive(node.path)
      } else {
        // if closing and it was active, set active to parent
        if (app.sidebar.activePath === node.path) {
          const parts = node.path.split('/').filter(Boolean)
          parts.pop()
          let parentPath = '/' + parts.join('/')
          if (parentPath === '') parentPath = '/'
          app.sidebar.setActive(parentPath)
        }
      }
    },

    // active check uses node._open (per node)
    isActive(node) {
      const match = app.sidebar.activePath === node.path
      const folder = node.type === "folder"
      return match && (!folder || node._open)
    }
  },

  helper: {
    toggle_dark_mode: () => {
      app.darkmode = !app.darkmode
      document.documentElement.classList.toggle("dark", app.darkmode)
    }
  },

  request: {
    get: async (params) => {
      try {
        const res = await axios.get(app.endpoint, { params: params })
        return res.data
      } catch (err) {
        app.set_toast({
          state: true,
          message: err.message,
          type: 'error',
          duration: 4000
        })
        console.error("Error fetching", err)
      }
    }
  },

  extensions: [
    { code: ['vue','html','js','css','php','json','xml','webmanifest','cpp','py'], icon: 'heroicons:code-bracket' },
    { data: ['csv','xls','xlsx'], icon: 'heroicons:table-cells' },
    { terminal: ['sh'], icon: 'heroicons:command-line' },
    { db: ['sql','db','sqlite','mdb','accdb','tsv','yaml','yml','plist'], icon: 'heroicons:circle-stack' },
    { image: ['png','jpg','jpeg','gif','svg','webp','bmp','ico','tiff','heic','avif'], icon: 'heroicons:photo' },
    { audio: ['mp3','wav','ogg','flac','aac','m4a','wma'], icon: 'heroicons:musical-note' },
    { video: ['mp4','mov','avi','mkv'], icon: 'heroicons:play' },
    { text: ['txt','md','log'], icon: 'heroicons:document-text' },
    { archive: ['zip','rar','7z','tar','gz','bz2','xz','iso'], icon: 'heroicons:archive-box' }
  ]
})

export { app }
