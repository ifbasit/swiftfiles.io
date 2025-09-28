import { reactive } from "vue"
import { helper } from './helper'
import { request } from "./request"

const sidebar = reactive({
    collapsed: false,
    folders: [],
    is_loading: true,
    // root is a reactive object inside app so adding node props (_open/_loading) will be reactive
    root: { name: null, type: 'folder', path: '/', children: [] },

    load_folders: async () => {
      const res = await request.get({ request: 'tree', path: '/' })
      let items = []
      if (Array.isArray(res)) items = res
      else if (res && res.items) items = res.items
      else items = []

      // update folders array (reactive)
      sidebar.folders.splice(0, sidebar.folders.length, ...items)

      // ensure root.children references the reactive folders array
      sidebar.root.children = sidebar.folders

      sidebar.is_loading = false
    },
    active_path: '',
    set_active_path: (path) => {
      sidebar.active_path = path
    },

    treenode: {
        icon(node) {
            if (node.type === "folder") return "heroicons:folder"
            const ext = (node.name || "").split('.').pop()?.toLowerCase()
            if (!ext) return "heroicons:document"

            for (const group of helper.extensions || []) {
                const key = Object.keys(group).find(k => k !== 'icon')
                if (key && group[key].includes(ext)) {
                return group.icon
                }
            }
            return "heroicons:document"
        },

        // load children for a specific node (attaches node.children)
        async load_children(node) {
            // if server already gave children, don't re-load
            if (node.children != null) {
                sidebar.folders = node.children
                return
            }
            node._loading = true
            try {
                const res = await request.get({ request: 'tree', path: node.path })
                let items = []
                if (Array.isArray(res)) items = res
                else if (res && res.items) items = res.items
                else items = []

                // attach children to the node
                node.children = items
                sidebar.folders = items
            } catch (err) {
                console.error("Error loading children for", node.path, err)
                node.children = []
            } finally {
                node._loading = false
            }
        },

        // toggle per-node open state
        async toggle(node) {
            if (node.type !== 'folder') return

            node._open = !node._open

            if (node._open) {
                await sidebar.treenode.load_children(node)
                let path = node.path
                const parts = path.split('/').filter(Boolean)
                if (parts.length === 1 && path.startsWith('/')) {
                path = '//' + parts[0]
                }
                sidebar.set_active_path(path)
            } else {
                await sidebar.treenode.load_children(node)
                if (sidebar.active_path === node.path) {
                const parts = node.path.split('/').filter(Boolean)
                parts.pop()
                let parentPath = '/' + parts.join('/')
                if (parentPath === '') parentPath = '/'
                if (parentPath.split('/').filter(Boolean).length === 1) {
                    parentPath = '//' + parentPath.replace('/', '')
                }
                sidebar.set_active_path(parentPath)
                }
            }

        },

        // active check uses node._open (per node)
        is_active(node) {
            const match = sidebar.active_path === node.path
            const folder = node.type === "folder"
            return match && (!folder || node._open)
        }
    }
})

export { sidebar }