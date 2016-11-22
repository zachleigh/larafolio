require('./bootstrap');

Vue.component('flash-message', require('./components/FlashMessage.vue'));
Vue.component('image-manager', require('./components/ImageManager.vue'));
Vue.component('image-tile', require('./components/ImageTile.vue'));
Vue.component('lines', require('./components/Lines.vue'));
Vue.component('project-controls', require('./components/ProjectControls.vue'));
Vue.component('project-form', require('./components/ProjectForm.vue'));

const bus = new Vue();

Vue.prototype.$bus = bus;

const app = new Vue({
    el: '#app'
});
