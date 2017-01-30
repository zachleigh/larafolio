export default {
    data: function () {
        return {
            /**
             * Form errors, keyed by field name.
             *
             * @type {Object}
             */
            errors: {},
        }
    },

    methods: {
        /**
         * Return true if field has error and is empty.
         *
         * @param  {String}  field
         *
         * @return {Boolean}
         */
        hasError (field) {
            if (field in this.errors && !this[field]) {
                return true;
            }

            return false;
        },

        /**
         * Format text block errors before display.
         *
         * @param  {String} error Error to format.
         *
         * @return {String}
         */
        formatError (error) {
            if (error.includes('.text')) {
                let errorArray = error.split('.');

                return 'The block ' + errorArray[2] + '.';
            }

            return error;
        }
    }
};