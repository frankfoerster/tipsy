import http from './http';

export default {
  imprint: () => {
    return http.get('/content/imprint');
  }
}
