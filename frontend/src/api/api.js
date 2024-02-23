import axios from "axios";
import getCookie from "utils/getCookie";

const api = axios.create({
    baseURL: '/api/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${ getCookie('token') ?? '' }`
    }
});

export default api;