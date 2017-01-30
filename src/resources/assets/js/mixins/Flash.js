import Ajax from './../mixins/Ajax.js';

export default {
    components: { Ajax },

    methods: {
        /**
         * Fire a flash event.
         *
         * @param  {Mixed} data
         */
        flash (data) {
            this.$bus.$emit('flash', data);
        },

        /**
         * Post session data to server and flash.
         *
         * @param  {Object} data  Flash message data.
         * @param  {String} route Redirect route.
         */
        postFlash(data, route) {
            this.ajax.post('/manager/session', {
                type: 'flash',
                key: 'flash_message',
                value: data
            })
            .then(function () {
                window.location = route;
            });
        }
    }
};