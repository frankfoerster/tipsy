import http from './http';

export default {
  createOrUpdate: (token, vote) => {
    const headers = {
      'Authorization': 'Bearer ' + token
    };

    return http.post('/tips/create-update', vote, {headers});
  }
}
