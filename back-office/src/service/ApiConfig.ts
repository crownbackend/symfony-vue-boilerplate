import axios from "axios";

axios.defaults.baseURL = import.meta.env.VITE_API_BACKEND;

axios.create({
    headers: {
        "Accept": "application/json",
        "Content-Type": "application/json"
    }
})

export default axios