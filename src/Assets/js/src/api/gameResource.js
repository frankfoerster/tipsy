import http from './http';

export default {
  list: () => {
    return http.get('/games');
  }
}
