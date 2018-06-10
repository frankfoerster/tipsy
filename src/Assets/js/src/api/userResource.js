import http from './http';

export default {
  signup: (user) => {
    return http.post('/users/signup', user);
  },

  login: (credentials) => {
    return http.post('/users/login', credentials);
  },

  logout: (token) => {
    const headers = {
      'Authorization': 'Bearer ' + token
    };

    return http.post('/users/logout', null, {headers});
  },

  info: (token) => {
    const headers = {
      'Authorization': 'Bearer ' + token
    };

    return http.get('/users/info', {headers});
  },

  verify: (token) => {
    return http.post('/users/verify', {token});
  },

  requestVerificationEmail: (token) => {
    const headers = {
      'Authorization': 'Bearer ' + token
    };

    return http.post('/users/request-verification-email', null, {headers});
  }
}
