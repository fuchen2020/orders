const v = new Validator();
const fly = new Fly();
fly.config.baseURL = `http://tx.chenziyong.vip/api/v1/`;
fly.config.crossDomain = true;
// 获取token
const token = localStorage.getItem('token');
// if (!token && (location.href.indexOf('login.html') < 0 || location.href.indexOf('register.html') < 0) ) {
//     location.href = '../../login.html';
// }
fly.config.headers.token = token;
