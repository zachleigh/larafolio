export default {
    data: function () {
        return {
            /**
             * When true, resource is visible.
             *
             * @type {Boolean}
             */
            visible: this.resource.visible
        }
    },

    methods: {
        /**
         * Toggle the visibility property.
         */
        toggleVisiblity () {
            this.visible = !this.visible;
        },

        /**
         * Send ajax request to server to change visibility.
         *
         * @param  {Boolean} visible Desired resource visibility.
         */
        changeVisibility (visible) {
            this.toggleVisiblity();

            let updateAction = this.getAction();

            this.ajax.patch(updateAction, {
                visible: this.visible,
            })
            .then(function () {
                let title = 'Resource Hidden';
                let message = this.resource.name + ' is not publicly viewable';

                if (this.visible) {
                    title = 'Resource Visible';
                    message = this.resource.name + ' is now publicly viewable';
                }

                this.flash({
                    title: title,
                    message: message,
                    type: 'success'
                });
            })
            .catch(function () {
                this.flash({
                    title: 'Error',
                    message: 'Could not change visibility',
                    type: 'error'
                });
            });
        },

        /**
         * Return the default update action if no update action is present.
         *
         * @return {String}
         */
        getAction () {
            if (typeof this.updateAction === 'undefined') {
                return '/manager/projects/'+this.resource.slug;
            }

            return this.updateAction;
        }
    }
};