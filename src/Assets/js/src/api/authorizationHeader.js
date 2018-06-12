const authorizationKey = global.window.AppConfig.authorizationHeader;

const authorizationHeader = (token) => {
  const header = {};
  header[authorizationKey] = 'Bearer ' + token;

  return header;
};

export default authorizationHeader;
