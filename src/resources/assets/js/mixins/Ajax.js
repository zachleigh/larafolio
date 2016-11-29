Vue.use(require('vue-resource'));

export default {
    data: function () {
        return {
            /**
             * Current ajax implementation.
             */
            ajax: this.$http
        }
    },
}
