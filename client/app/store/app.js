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
    sidebar: {
        collapsed: false,
        folders: [],
        is_loading: true,
        load_folders: async () => {
            const res = await app.request.get({ request: 'tree', path: '/' })
            if (Array.isArray(res.data)) {
                app.sidebar.folders.splice(0, app.sidebar.folders.length, ...res.data)
            } else if (res.data && res.data.items) {
                app.sidebar.folders.splice(0, app.sidebar.folders.length, ...res.data.items)
            } else {
                app.sidebar.folders.splice(0) // clear
            }
            app.sidebar.is_loading = false
        },
        activePath: ref(""),
        setActive(path, folder) {
        app.sidebar.activePath.value = path
        }
    },
    treenode: {
        open: ref(false),
        loading: ref(false),
        children: ref(null),
        isActive(node) {
        const match = app.sidebar.activePath.value === node.path
        const folder = node.type === "folder"
        return match && (!folder || app.treenode.open.value)
        },
        iconName(node) {
        if (node.type === "folder") return "heroicons:folder"
        const ext = (node.name || "").split(".").pop()?.toLowerCase()
        if (!ext) return "heroicons:document"
        for (const group of app.extensions || []) {
            const key = Object.keys(group).find(k => k !== "icon")
            if (key && group[key].includes(ext)) {
            return group.icon
            }
        }
        return "heroicons:document"
        },
        async loadChildren(path, node) {
        if (app.treenode.children.value !== null) return
        app.treenode.loading.value = true
        try {
            const res = await app.request
            .get({ request: "tree", path })
            .then(r => r)
            .catch(() => null)
            let items = []
            if (Array.isArray(res)) items = res
            else if (res && res.items) items = res.items
            app.treenode.children.value = items
        } catch (err) {
            console.error("Error loading children for", node.name, err)
            app.treenode.children.value = []
        } finally {
            app.treenode.loading.value = false
        }
        },
        async onToggle(node) {
        const folder = node.type === "folder"
        app.treenode.open.value = !app.treenode.open.value

        if (folder) {
            try {
            const res = await axios.get(app.endpoint, {
                params: { request: "tree", path: node.path }
            })
            console.log(res.data)
            } catch (err) {
            console.error("Error loading children for", node.name, err)
            app.treenode.children.value = []
            } finally {
            app.treenode.loading.value = false
            }
        }

        if (app.treenode.open.value) {
            await app.treenode.loadChildren(node.path, node)
            app.sidebar.setActive(node.path, folder)
        } else if (app.sidebar.activePath.value === node.path) {
            let parts = node.path.split("/").filter(Boolean)
            parts.pop()
            let parentPath = "/" + parts.join("/")
            if (parentPath === "") parentPath = "/"
            console.log("collapse → set-active:", parentPath)
            app.sidebar.setActive(parentPath, folder)
        }
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
                const res = await axios.get(app.endpoint, { params: params})
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
        {
            code: ['vue','html','js','css','php','json','xml', 'webmanifest', 'cpp', 'py'],
            icon: 'heroicons:code-bracket'
        },
        {
            data: ['csv','xls', 'xlsx'],
            icon: 'heroicons:table-cells'
        },
        {
            terminal: ['sh'],
            icon: 'heroicons:command-line'
        },
        {
            db: ['sql','db','sqlite','mdb','accdb','tsv','yaml','yml','plist'],
            icon: 'heroicons:circle-stack'
        },
        {
            image: ['png','jpg','jpeg','gif','svg','webp','bmp','ico','tiff','heic','avif'],
            icon: 'heroicons:photo'
        },
        {
            audio: ['mp3','wav','ogg','flac','aac','m4a','wma'],
            icon: 'heroicons:musical-note'
        },
        {
            video: ['mp4','mov','avi','mkv'],
            icon: 'heroicons:play'
        },
        {
            text: ['txt','md','log'],
            icon: 'heroicons:document-text'
        },
        {
            archive: ['zip','rar','7z','tar','gz','bz2','xz','iso'],
            icon: 'heroicons:archive-box'
        }
        
        ]
 
})


export {
    app
}