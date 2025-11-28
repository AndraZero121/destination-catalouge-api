import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// set initial Authorization header if token present
const _t = localStorage.getItem("token");
if (_t) {
    window.axios.defaults.headers.common["Authorization"] = "Bearer " + _t;
}

// ensure every request reads latest token (in case user logs in/out without full reload)
window.axios.interceptors.request.use(
    function (config) {
        const t = localStorage.getItem("token");
        if (t) {
            config.headers = config.headers || {};
            config.headers["Authorization"] = "Bearer " + t;
        } else {
            if (config.headers) delete config.headers["Authorization"];
        }
        return config;
    },
    function (error) {
        return Promise.reject(error);
    }
);
