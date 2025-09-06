import axios from "axios"
const app = reactive({
    endpoint: 'http://localhost:8888/swiftfilesio/server/',
    darkmode: false,
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