Vue.use(require('vue-resource'));

export default {
    data: function () {
        return {
            ajax: this.$http
        }
    },
}
