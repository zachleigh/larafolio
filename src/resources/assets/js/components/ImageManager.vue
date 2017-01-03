<template>
    <div class="image-manager">
        <modal
            :show="showRemoveModal"
            :icons="icons"
            @close="showRemoveModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
            <div slot="body">
                <p>
                    Remove this image?
                </p>
                <img v-bind:src="currentThumbnail">
            </div>

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
                            @click.prevent="removeImage"
                        >
                            Remove Image
                        </button>
                    </div>
                </div>
        </modal>
        <form
            v-bind:action="action"
            method="POST"
            class="dropzone image-manager__dropzone"
            id="my-awesome-dropzone"
        >
            <input type="hidden" name="_token" v-bind:value="token">
        </form>
        <h3 
            class="image-manager__message"
            v-show="!hasImages"
        >
            This project has no images
        </h3>
        <div class="image-manager__images">
            <image-tile
                v-for="image in passedImages"
                :key="image.id"
                :icons="icons"
                :image="image"
                @remove="toggleRemoveModal"
            ></image-tile>
        </div>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import ImageTile from './ImageTile.vue';
    import Modal from './Modal.vue';

    export default {
        components: { ImageTile, Modal },

        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                /**
                 * Array of images passed from prop.
                 *
                 * @type {Array}
                 */
                passedImages: this.images,

                /**
                 * The currently seleted image, if any.
                 *
                 * @type {Object}
                 */
                currentImage: null,

                /**
                 * When true, show the remove image modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveModal: false
            }
        },

        props: {
            /**
             * Store image action.
             */
            action: {
                type: String
            },

            /**
             * Fetch images action.
             */
            fetchAction: {
                type: String
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Array of project images.
             */
            images: {
                type: Array
            },

            /**
             * Csrf token for dropbox input.
             */
            token: {
                type: String
            }
        },

        computed: {
            /**
             * Return the thumbnail url for the currently selected image.
             *
             * @return {String}
             */
            currentThumbnail () {
                if (this.currentImage) {
                    return this.currentImage.thumbnail;
                }
            },

            /**
             * Return true if project hass images.
             *
             * @return {Boolean}
             */
            hasImages () {
                return this.passedImages.length > 0;
            }
        },

        created () {
            let Dropzone = require('dropzone');

            let instance = this;

            Dropzone.options.myAwesomeDropzone = {
                dictDefaultMessage: 'Drop images here to add to project',
                init: function() {
                    this.on('success', function (file) {
                        instance.uploaded();
                    });
                    this.on('error', function (message) {
                        instance.uploadErrored(message);
                    });
                }
            };
        },
        methods: {
            /**
             * Called when image successfully uploaded.
             */
            uploaded () {
                this.flash({
                    title: 'Added',
                    message: 'Image added to project',
                    type: 'success'
                });

                this.fetchImages();
            },

            /**
             * Called when image upload errored.
             *
             * @param  {Object} message Error object received from server.
             */
            uploadErrored (message) {
                this.flash({
                    title: 'Error',
                    message: 'Could not upload image',
                    type: 'error'
                });
            },

            /**
             * Fetch all images from server and set on component.
             *
             * @return {Array}
             */
            fetchImages () {
                this.ajax.get(this.fetchAction)
                .then(function (response) {
                    this.passedImages = response.data;
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not fetch updated images',
                        type: 'error'
                    });
                });
            },

            /**
             * Set current image on component and toggle remove modal.
             *
             * @param  {Object} currentImage Image object sent through event.
             */
            toggleRemoveModal (currentImage) {
                this.currentImage = currentImage;

                this.showRemoveModal = !this.showRemoveModal;
            },

            /**
             * Remove image.
             */
            removeImage () {
                this.ajax.delete('/manager/images/'+this.currentImage.id)
                .then(function (response) {
                    this.flash({
                        title: 'Removed',
                        message: 'Image removed from project',
                        type: 'success'
                    });

                    this.showRemoveModal = false;

                    let index = this.passedImages.indexOf(this.currentImage);

                    this.passedImages.splice(index, 1);
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not remove image',
                        type: 'error'
                    });
                });
            }
        }
    };
</script>
