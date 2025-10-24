// vuejs/src/api.js
import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8007/api/", // Laravel API container
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default api;