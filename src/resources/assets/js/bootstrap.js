window.Vue = require('vue');

require('vue-resource');

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Larafolio.csrfToken);

    next();
});
