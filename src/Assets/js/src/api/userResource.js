import http from './http';
import authorizationHeader from './authorizationHeader';

export default {
  signup: (user) => {
    return http.post('/users/signup', user);
  },

  login: (credentials) => {
    return http.post('/users/login', credentials);
  },

  logout: (token) => {
    const headers = authorizationHeader(token);

    return http.post('/users/logout', null, {headers});
  },

  lostPassword: (data) => {
    return http.post('/users/lost-password', data);
  },

  resetPassword: (data) => {
    return http.patch('/users/reset-password', data);
  },

  info: (token) => {
    const headers = authorizationHeader(token);

    return http.get('/users/info', {headers});
  },

  verify: (token) => {
    return http.post('/users/verify', {token});
  },

  requestVerificationEmail: (token) => {
    const headers = authorizationHeader(token);

    return http.post('/users/request-verification-email', null, {headers});
  },

  updateWinner: (token, vote) => {
    const headers = authorizationHeader(token);

    return http.patch('/users/update-winner', vote, {headers});
  }
}
