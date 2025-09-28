import { reactive } from "vue"

const app = reactive({
  endpoint: 'http://localhost:8888/swiftfilesio/server/',
  darkmode: false,
  toggle_dark_mode: () => {
      app.darkmode = !app.darkmode
      document.documentElement.classList.toggle("dark", app.darkmode)
  },
  user: null,

})



export { app }
