import http from './http';
import authorizationHeader from './authorizationHeader';

export default {
  createOrUpdate: (token, vote) => {
    const headers = authorizationHeader(token);

    return http.post('/tips/create-update', vote, {headers});
  }
}
