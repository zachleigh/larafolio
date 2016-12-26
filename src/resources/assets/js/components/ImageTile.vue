<template>
    <div class="image-tile">
        <div class="image-tile__controls">
            <span
                v-bind:id="elementId('remove')"
                class="text-block__icon red-icon"
                v-html="icons.remove"
                @click.prevent="$emit('remove', passedImage)"
            ></span>
        </div>
        <div class="image-tile__top">
            <div class="image-tile__name">
                <label
                    class="image-tile__label"
                    v-bind:for="elementId('name')"
                >
                    Name:
                </label>
                <input
                    v-bind:id="elementId('name')"
                    v-bind:name="elementId('name')"
                    class="form__name-input image-tile__input"
                    type="text"
                    placeholder="None"
                    v-model="name"
                >
            </div>
        </div>
        <div class="image-tile__body">
            <a
                class="image-tile__image"
                v-bind:href="image.full"
            >
                <img v-bind:src="image.small">
            </a>
            <div class="image-tile__right">
                <div class="image-tile__form-section image-tile__alt">
                    <label
                        class="form__label"
                        :for="elementId('alt')"
                    >
                        Alt text
                    </label>
                    <input
                        v-bind:id="elementId('alt')"
                        v-bind:name="elementId('alt')"
                        class="form__input"
                        type="text"
                        placeholder="Alt text"
                        v-model="alt"
                    >
                </div>
                <div class="image-tile__form-section">
                    <label
                        class="form__label"
                        :for="elementId('caption')"
                    >
                        Caption
                    </label>
                    <textarea
                        v-bind:id="elementId('caption')"
                        v-bind:name="elementId('caption')"
                        class="form__input image-tile__caption"
                        type="text"
                        placeholder="Caption"
                        v-model="caption"
                    ></textarea>
                </div>

            </div>
        </div>
        <div class="image-tile__buttons">
            <button
                v-bind:id="elementId('button')"
                class="button button--green"
                :disabled="buttonState"
                @click.prevent="update"
            >
                Update Image
            </button>
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
                /**
                 * Image object from prop.
                 *
                 * @type {Object}
                 */
                passedImage: this.image,

                /**
                 * Image caption.
                 *
                 * @type {String}
                 */
                caption: this.image.caption,

                /**
                 * Image name.
                 *
                 * @type {String}
                 */
                name: this.image.name,

                /**
                 * Image alt text.
                 *
                 * @type {String}
                 */
                alt: this.image.alt,
            }
        },

        props: {
            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Image object with paths for different image sizes.
             */
            image: {
                type: Object
            }
        },

        computed: {
            /**
             * Return true when component data has changed.
             *
             * @return {Boolean}
             */
            changed () {
                return this.caption !== this.passedImage.caption ||
                    this.name !== this.passedImage.name ||
                    this.alt !== this.passedImage.alt;
            },

            /**
             * Update button state. Removes 'disabled' when component has changed.
             *
             * @return {String}
             */
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
                    caption: this.caption,
                    alt: this.alt
                })
                .then(function (response) {
                    this.flash({
                        title: 'Updated',
                        message: 'Image information updated',
                        type: 'success'
                    });

                    this.passedImage.name = this.name;
                    this.passedImage.caption = this.caption;
                    this.passedImage.alt = this.alt;
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not update image',
                        type: 'error'
                    });
                });
            },
        }
    };
</script>
