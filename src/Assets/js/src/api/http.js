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
    let data = response.data;

    const authorizationHeader = global.window.AppConfig.authorizationHeader.toLowerCase();

    if (response.headers && response.headers[authorizationHeader]) {
      const token = response.headers[authorizationHeader].split(' ')[1];
      if (token) {
        data.auth = {
          token
        };
      }
    }

    return Promise.resolve(data);
  },
  (error) => {
    if (error.response.status === 403) {
      //@TODO: Redirect to login route
    }

    if ([400, 401, 409, 422].indexOf(error.response.status) !== -1 && typeof error.response.data !== 'undefined') {
      return Promise.reject(error.response.data);
    }

    return Promise.reject(error.response);
  }
);

export default http;
