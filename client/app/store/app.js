const app = reactive({
    endpoint: 'http://localhost:8888/swiftfilesio/server/',
    darkmode: false,
    extensions: [
        {
            code: ['vue','html','js','css','php','json','xml', 'webmanifest'],
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