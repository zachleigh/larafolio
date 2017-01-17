<template>
    <div v-bind:id="elementId('link')" class="link section">
        <div class="link__control text-block__control">
            <div>
                <label v-bind:for="elementId('linkName')">Name: </label>
                <input
                    class="form__name-input"
                    type="text"
                    autocomplete="off"
                    :name="elementId('linkName')"
                    v-model="name"
                    v-on:input="update()"
                    placeholder="None"
                >
            </div>
            <div class="text-block__control-buttons">
                <span
                    v-bind:id="elementId('upLink')"
                    class="text-block__icon"
                    v-html="icons.up"
                    @click.prevent="$emit('up', index)"
                    alt="Up"
                ></span>
                <span
                    v-bind:id="elementId('downLink')"
                    class="text-block__icon"
                    v-html="icons.down"
                    @click.prevent="$emit('down', index)"
                    alt="Down"
                ></span>
                <span
                    :id="elementId('deleteLink')"
                    class="text-block__icon red-icon"
                    v-html="icons.remove"
                    @click.prevent="remove"
                    alt="Remove link"
                ></span>
            </div>
        </div>
        <div class="section__indented">
            <label class="form__label" :for="elementId('linkText')">Text</label>
            <input
                :id="elementId('linkText')"
                class="form__input link__text"
                type="text"
                :name="elementId('linkText')"
                autocomplete="off"
                v-model="text"
                v-on:input="update()"
                placeholder="Text"
            >
            <label class="form__label" :for="elementId('url')">Url</label>
            <input
                class="form__input link__url"
                type="text"
                :name="elementId('url')"
                autocomplete="off"
                v-model="url"
                v-on:input="update()"
                placeholder="Url"
            >
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                /**
                 * Link name.
                 *
                 * @type {String}
                 */
                name: this.link.name,

                /**
                 * Link from props.
                 *
                 * @type {Object}
                 */
                passedLink: this.link,

                /**
                 * Link text.
                 *
                 * @type {String}
                 */
                text: this.link.text,

                /**
                 * Link url.
                 *
                 * @type {String}
                 */
                url: this.link.url
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
             * Index of this link in links array on parent.
             */
            index: {
                type: Number
            },

            /**
             * Link object.
             */
            link: {
                type: Object
            }
        },

        created () {
            this.update();
        },

        methods: {
            /**
             * Emit event to update the link.
             */
            update () {
                this.updatePassedLink();

                this.$emit('update', this.passedLink);
            },

            /**
             * Update the data in the passed link object.
             */
            updatePassedLink () {
                this.passedLink.url = this.url,
                this.passedLink.name = this.name,
                this.passedLink.text = this.text,
                this.passedLink.index = this.index
            },

            /**
             * Emit event to remove link.
             */
            remove () {
                this.updatePassedLink();

                this.$emit('remove', this.passedLink);
            },

            /**
             * Make prefixed name/id for element.
             *
             * @param  {String} prefix String to be prepended to index.
             *
             * @return {String}
             */
            elementId (prefix) {
                return prefix + this.index;
            }
        }
    };
</script>
