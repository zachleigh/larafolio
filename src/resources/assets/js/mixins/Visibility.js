export default {
    data: function () {
        return {
            /**
             * When true, project is visible.
             *
             * @type {Boolean}
             */
            visible: this.project.visible
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
         * @param  {Boolean} visible Desired project visibility.
         */
        changeVisibility (visible) {
            this.toggleVisiblity();

            let updateAction = this.getAction();

            this.ajax.patch(updateAction, {
                visible: this.visible,
            })
            .then(function () {
                let title = 'Project Hidden';
                let message = this.project.name + ' is not publicly viewable';

                if (this.visible) {
                    title = 'Project Visible';
                    message = this.project.name + ' is now publicly viewable';
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
                    message: 'Could not change project visibility',
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
                return '/manager/'+this.project.slug+'/update'
            }

            return this.updateAction;
        }
    }
};