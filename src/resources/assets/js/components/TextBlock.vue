<template>
    <div v-bind:id="elementId('block')" class="text-block form__section">
        <div class="text-block__control">
            <div>
                <label v-bind:for="elementId('name')">Name: </label>
                <input
                    class="form__name-input"
                    type="text"
                    v-bind:name="elementId('name')"
                    autocomplete="off"
                    v-model="name"
                    v-on:input="update()"
                    placeholder="None"
                >
            </div>
            <div class="text-block__control-buttons">
                <span
                    v-bind:id="elementId('up')"
                    class="text-block__icon"
                    v-html="icons.up"
                    @click.prevent="up"
                    alt="Up"
                ></span>
                <span
                    v-bind:id="elementId('down')"
                    class="text-block__icon"
                    v-html="icons.down"
                    @click.prevent="down"
                    alt="Down"
                ></span>
                <span
                    v-bind:id="elementId('delete')"
                    class="text-block__icon red-icon"
                    v-html="icons.remove"
                    @click.prevent="remove"
                    alt="Remove block"
                ></span>
            </div>
        </div>
        <div class="text-block__fields">
            <label class="form__label" :for="elementId('text')">Text</label>
            <textarea
                :id="elementId('text')"
                class="form__input form__textarea"
                v-bind:name="elementId('text')"
                autocomplete="off"
                v-model="text"
                v-on:input="update()"
                placeholder="Text"
                v-bind:class="{ form__errored: errored() }"
            ></textarea>
        </div>
    </div>
</template>

<script>
    import Autosize from './../mixins/Autosize.js';
    import Markdown from './../mixins/Markdown.js';

    export default {
        mixins: [ Autosize, Markdown ],

        data: function () {
            return {
                /**
                 * Block object passed from props.
                 *
                 * @type {Object}
                 */
                passedBlock: this.block,

                /**
                 * Block name.
                 *
                 * @type {String}
                 */
                name: '',

                /**
                 * Block text.
                 *
                 * @type {String}
                 */
                text: '',
            }
        },

        props: {
            /**
             * This block.
             */
            block: {
                type: Object
            },

            /**
             * Form errors, keyed by field name.
             */
            errors: {
                type: Object
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Index of this block in blocks array on parent.
             */
            index: {
                Type: Number
            }
        },

        created () {
            if (this.keyExists('name')) {
                this.name = this.passedBlock.name;
            }

            if (this.keyExists('text')) {
                this.text = this.passedBlock.text;
            }
        },

        mounted () {
            this.autosize(document.querySelectorAll('textarea'));
        },

        methods: {
            /**
             * Update passedBlock and emit event.
             */
            update () {
                this.updatePassedBlock();

                this.$emit('blockInput', this.passedBlock);
            },

            /**
             * Emit event to remove block.
             */
            remove () {
                this.updatePassedBlock();

                this.$emit('remove', this.passedBlock);
            },

            /**
             * Emit event to move block up in blocks array.
             */
            up () {
                this.updatePassedBlock();

                this.$emit('up', this.passedBlock.index);
            },

            /**
             * Emit event to move block down in blocks array.
             */
            down () {
                this.updatePassedBlock();

                this.$emit('down', this.passedBlock.index);
            },

            /**
             * Update the passedBlock data.
             */
            updatePassedBlock () {
                this.passedBlock.index = this.index;
                this.passedBlock.name = this.name;
                this.passedBlock.text = this.text;
                this.passedBlock.formatted_text = this.md.render(this.text);
            },

            /**
             * Return true if this block contains a form error.
             *
             * @return {Boolean}
             */
            errored () {
                for (var i in this.errors) {
                    let error = this.errors[i];

                    if (this.containsError(error) && !this.textExists()) {
                        return true;
                    }
                }

                return false;
            },

            /**
             * Return true if the error is an error for current block.
             *
             * @param  {String} error Form errors.
             *
             * @return {Boolean}
             */
            containsError (error) {
                let errorArray = error[0].split('.');

                return errorArray[0] === 'The blocks' &&
                    errorArray[1] == this.index;
            },

            /**
             * Return true if text field is populated.
             *
             * @return {Boolean}
             */
            textExists () {
                return this.text !== '' && typeof this.text != 'undefined';
            },

            /**
             * Return true if key exists in blocks.
             *
             * @param  {String} key Key to check for.
             *
             * @return {Boolean}
             */
            keyExists (key) {
                return this.block[key] !== '' &&
                    typeof this.block[key] != 'undefined';
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
