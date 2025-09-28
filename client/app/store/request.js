import axios from "axios"
import { toast } from "./toast";
import { app } from "./app";
const request = reactive({
    get: async (params) => {
      try {
        const res = await axios.get(app.endpoint, { params: params })
        return res.data
      } catch (err) {
        
        toast.set_toast({
          state: true,
          message: err.message,
          type: 'error',
        })
        console.error("Error fetching", err)
      }
    }
});

export { request }