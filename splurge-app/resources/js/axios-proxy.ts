import axios, {AxiosInstance, AxiosError} from "axios";


axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.interceptors.request.use((config) => {
        if (!config.method || !(/get/i).test(config.method)) {
            return config;
        }

        const data = config.data || {};

        data._token = token.getAttribute('content');

        config.data = data;

        return config;
    });
}

export {
    AxiosInstance,
    AxiosError
}

export default axios;