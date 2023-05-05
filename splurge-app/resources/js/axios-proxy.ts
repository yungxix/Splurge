import axios, {AxiosInstance, AxiosError} from "axios";
import { isString } from "lodash";


axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

axios.defaults.withCredentials = true;

const token = document.querySelector('meta[name="csrf-token"]');

if (token) {
    axios.interceptors.request.use((config) => {
        if (!config.method || !(/get/i).test(config.method)) {
            return config;
        }

        let data = config.data || {};
        
        if (isString(data)) {
            data = JSON.parse(data);
        }
        data._token = token.getAttribute('content');

        config.data = JSON.stringify(data);

        return config;
    });
}

export {
    AxiosInstance,
    AxiosError
}

export default axios;