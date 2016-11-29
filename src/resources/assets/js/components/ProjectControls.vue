<template>
    <div class="project-controls">
        <modal
            :show="showRemoveModal"
            :icons="icons"
            @close="showRemoveModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
                <p slot="body">
                    Remove this project from the portfolio?
                </p>
                <div slot="footer">
                    <div class="modal__buttons">
                        <button
                            class="button button--secondary"
                            @click.prevent="showRemoveModal = false"
                        >
                            Don't Remove
                        </button>
                        <button
                            class="button button--blue"
                            @click.prevent="removeProject"
                        >
                            Remove Project
                        </button>
                    </div>
                </div>

        </modal>
        <div class="project-controls__group">
            <div class="project-controls__section" v-show="visible">
                Visible
                <span
                    id="makeHidden"
                    class="project-controls__icon green-icon"
                    v-html="icons.visible"
                    @click.prevent="changeVisibility(false)"
                ></span>
            </div>
            <div class="project-controls__section" v-show="!visible">
                Hidden
                <span
                    id="makeVisible"
                    class="project-controls__icon red-icon"
                    v-html="icons.hidden"
                    @click.prevent="changeVisibility(true)"
                ></span>
            </div>
        </div>
        <div class="project-controls__group">
            <div class="project-controls__section">
                Remove
                <span
                    id="removeProject"
                    class="project-controls__icon red-icon"
                    v-html="icons.remove"
                    @click.prevent="showRemoveModal = true"
                ></span>
            </div>
        </div>
    </div>

</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import Modal from './Modal.vue';

    export default {
        components: { Modal },

        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                /**
                 * When true, show the remove project modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveModal: false,

                /**
                 * When true, project is visible.
                 *
                 * @type {Boolean}
                 */
                visible: this.project.visible
            }
        },

        props: {
            /**
             * Action for updating project.
             */
            updateAction: {
                type: String
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Project object.
             */
            project: {
                type: Object
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

                this.ajax.patch(this.updateAction, {
                    visible: this.visible,
                })
                .then(function (response) {
                    let title = 'Project Hidden';
                    let message = 'Project is not publicly viewable';

                    if (this.visible) {
                        title = 'Project Visible';
                        message = 'Project is now publicly viewable';
                    }

                    this.flash({
                        title: title,
                        message: message,
                        type: 'success'
                    });
                })
                .catch(function (error) {
                    
                });
            },

            /**
             * Remove the project from the portfolio.
             */
            removeProject () {
                this.ajax.delete(this.removeAction)
                .then(function (response) {
                    this.postFlash({
                        title: 'Removed',
                        message: 'Project removed from portfolio',
                        type: 'success'
                    }, '/manager');
                })
                .catch(function (error) {
                    
                });
            }
        }
    };
</script>
