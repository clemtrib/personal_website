import axios from 'axios';

const api = axios.create({
  baseURL: import.meta.env.VITE_APP_URL || 'http://127.0.0.1:8000',
});

export default api;
