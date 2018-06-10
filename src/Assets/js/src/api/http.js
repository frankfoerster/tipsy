import axios from 'axios';

const baseUrl = global.window.AppConfig.apiBaseUrl;

const http = axios.create({
  baseURL: baseUrl,
  timeout: 10000,
  headers: {
    'Accept': 'application/json, text/javascript, */*; q=0.01',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

http.interceptors.response.use(
  (response) => {
    return Promise.resolve(response);
  },
  (error) => {
    if (error.response.status === 403) {
      //@TODO: Display login modal
    }

    return Promise.reject(error);
  }
);

export default http;
