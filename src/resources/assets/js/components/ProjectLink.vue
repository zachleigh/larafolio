<template>
    <div v-bind:id="elementId('link')" class="link form__section">
        <div class="link__control text-block__control">
            <div>
                <label v-bind:for="elementId('key')">Key: </label>
                <input
                    class="text-block__control-input"
                    type="text"
                    autocomplete="off"
                    :name="elementId('key')"
                    v-model="key"
                    v-on:input="update()"
                    placeholder="None"
                >
            </div>
            <div class="text-block__control-buttons">
                <span
                    :id="elementId('deleteLink')"
                    class="text-block__icon red-icon"
                    v-html="icons.remove"
                    @click.prevent="remove"
                    alt="Remove link"
                ></span>
            </div>
        </div>
        <div class="link__fields">
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
        components: {},

        mixins: [],

        data () {
            return {
                key: this.link.key,
                passedLink: this.link,
                url: this.link.url
            }
        },

        props: {
            icons: {
                type: Object
            },

            index: {
                type: Number
            },

            link: {
                type: Object
            }
        },

        computed: {
            //
        },

        created () {
            //
        },

        mounted () {
            //
        },

        methods: {
            update () {
                this.updatePassedLink();

                this.$emit('update', this.passedLink);
            },

            updatePassedLink () {
                this.passedLink.url = this.url,
                this.passedLink.key = this.key,
                this.passedLink.index = this.index
            },

            remove () {
                this. updatePassedLink();

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
