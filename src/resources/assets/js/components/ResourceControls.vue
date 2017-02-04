<template>
    <div class="resource-controls">
        <modal
            :show="showRemoveModal"
            :icons="icons"
            @close="showRemoveModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
                <p slot="body">
                    Remove {{ resource.name }} from the portfolio?
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
                            class="button button--primary"
                            @click.prevent="removeResource"
                        >
                            Remove
                        </button>
                    </div>
                </div>

        </modal>
        <div class="resource-controls__group">
            <div class="resource-controls__section" v-show="visible">
                Visible
                <span
                    id="makeHidden"
                    class="resource-controls__icon green-icon"
                    v-html="icons.visible"
                    @click.prevent="changeVisibility(false)"
                ></span>
            </div>
            <div class="resource-controls__section" v-show="!visible">
                Hidden
                <span
                    id="makeVisible"
                    class="resource-controls__icon red-icon"
                    v-html="icons.hidden"
                    @click.prevent="changeVisibility(true)"
                ></span>
            </div>
        </div>
        <div class="resource-controls__group">
            <div class="resource-controls__section">
                Remove
                <span
                    id="removeResource"
                    class="resource-controls__icon red-icon"
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
    import Visibility from './../mixins/Visibility.js';

    export default {
        components: { Modal },

        mixins: [ Ajax, Flash, Visibility ],

        data: function () {
            return {
                /**
                 * When true, show the remove resource modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveModal: false,
            }
        },

        props: {
            /**
             * Action for updating resource.
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
             * Resource object.
             */
            resource: {
                type: Object
            }
        },

        methods: {
            /**
             * Remove the resource from the portfolio.
             */
            removeResource () {
                let name = this.resource.name;

                this.ajax.delete(this.removeAction)
                .then(function (response) {
                    this.postFlash({
                        title: 'Removed',
                        message: name + ' removed from portfolio',
                        type: 'success'
                    }, '/manager');
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not remove ' + name,
                        type: 'error'
                    });
                });
            }
        }
    };
</script>
