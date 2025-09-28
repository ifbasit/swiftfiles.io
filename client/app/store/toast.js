import { reactive } from "vue"

const toast = reactive({
  state: false,
  message: "",
  duration: 0,
  type: "", 

  clear_toast: () => {
    toast.set_toast({
      state: false,
      message: "",
      duration: 0,
      type: ""
    })
  },

  set_toast: (data) => {   
    const { state, message, duration, type } = data
    toast.state = state
    toast.message = message || ""
    toast.duration = duration || 4000
    toast.type = type || "info"
  }
})

export { toast }
