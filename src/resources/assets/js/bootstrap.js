/** global: Vue */
window.Vue = require('vue');

require('vue-resource');

/** global: Larafolio */
Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Larafolio.csrfToken);

    next();
});
