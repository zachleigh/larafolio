<template>
    <div class="image-tile">
        <div class="image-tile__controls">
            <span
                id="removeImage"
                class="text-block__icon red-icon"
                v-html="icons.remove"
                @click.prevent="remove"
            ></span>
        </div>
        <div class="image-tile__body">
            <a
                class="image-tile__image"
                v-bind:href="image.full"
            >
                <img v-bind:src="image.small">
            </a>
            <div class="image-tile__right">
                <div class="image-tile__form-section image-tile__name">
                    <label
                        class="image-tile__label"
                        v-bind:for="elementId('name')"
                    >
                        Name:
                    </label>
                    <input
                        v-bind:id="elementId('name')"
                        v-bind:name="elementId('name')"
                        class="text-block__control-input image-tile__input"
                        type="text"
                        placeholder="None"
                        v-model="name"
                    >
                </div>
                <div class="image-tile__form-section">
                    <label
                        class="image-tile__label"
                        v-bind:for="elementId('caption')"
                    >
                        Caption
                    </label>
                    <textarea
                        v-bind:id="elementId('caption')"
                        v-bind:name="elementId('caption')"
                        class="form__input image-tile__caption"
                        type="text"
                        placeholder="None"
                        v-model="caption"
                    ></textarea>
                </div>
                <div class="image-tile__buttons">
                    <button
                        class="button button--green"
                        :disabled="buttonState"
                        @click.prevent="update"
                    >
                        Update Image
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';

    export default {
        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                passedImage: this.image,
                caption: this.image.caption,
                name: this.image.name,
            }
        },

        props: {
            icons: {
                type: Object
            },

            image: {
                type: Object
            }
        },

        computed: {
            changed () {
                return this.caption !== this.passedImage.caption ||
                    this.name !== this.passedImage.name;
            },

            buttonState () {
                if (!this.changed) {
                    return 'disabled';
                }
            }
        },

        methods: {
            /**
             * Make prefixed name/id for element.
             *
             * @param  {String} prefix String to be prepended to index.
             *
             * @return {String}
             */
            elementId (prefix) {
                return prefix + this.image.id;
            },

            /**
             * Update the current image name and caption.
             */
            update () {
                this.ajax.patch('/manager/images/'+this.image.id, {
                    name: this.name,
                    caption: this.caption
                })
                .then(function (response) {
                    this.flash({
                        title: 'Updated',
                        message: 'Image information updated',
                        type: 'success'
                    });

                    this.passedImage.name = this.name;
                    this.passedImage.caption = this.caption;
                })
                .catch(function (error) {
                    
                });
            },

            /**
             * Fire remove image event.
             */
            remove () {
                this.$emit('remove', this.passedImage);
            }
        }
    };
</script>
