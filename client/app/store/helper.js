import axios from "axios"

const helper = reactive({
    the_ids: new Set(),
    
    get_timestamp: () => {
      return Date.now();
    },
    uniqid: () => {
      const chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
      let id;
      do {
        id = '';
        for (let i = 0; i < 8; i++) {
          id += chars.charAt(Math.floor(Math.random() * chars.length));
        }
      } while (helper.the_ids.has(id));

      helper.the_ids.add(id);
      return id;
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

export { helper }