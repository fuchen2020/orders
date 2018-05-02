const v = new Validator();
const fly = new Fly();
fly.config.baseURL = `http://kami.chenziyong.vip/api/v1/`;
fly.config.crossDomain = true;
// 获取token
const token = localStorage.getItem('token');
if (!token && location.href.indexOf('account/login.html') < 0) {
    location.href = '../account/login.html';
}
fly.config.headers.token = token;
