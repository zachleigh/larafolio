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
                            class="button button--blue"
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
                passedImages: this.images,
                currentImage: null,
                showRemoveModal: false
            }
        },

        props: {
            action: {
                type: String
            },

            fetchAction: {
                type: String
            },

            icons: {
                type: Object
            },

            images: {
                type: Array
            },

            token: {
                type: String
            }
        },

        computed: {
            currentThumbnail () {
                if (this.currentImage) {
                    return this.currentImage.thumbnail;
                }
            }
        },

        created () {
            let Dropzone = require('dropzone');

            let instance = this;

            Dropzone.options.myAwesomeDropzone = {
                dictDefaultMessage: 'Drop images here to add to project',
                init: function() {
                    this.on("complete", function(file) {
                        instance.uploaded();
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
                    
                });
            }
        }
    };
</script>
