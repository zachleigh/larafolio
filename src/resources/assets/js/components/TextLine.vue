<template>
    <div v-bind:id="elementId('line')" class="line section">
        <div class="link__control text-block__control">
            <div>
                <label v-bind:for="elementId('lineName')">Name: </label>
                <input
                    class="form__name-input"
                    type="text"
                    autocomplete="off"
                    :name="elementId('lineName')"
                    v-model="name"
                    v-on:input="update()"
                    placeholder="None"
                >
            </div>
            <div class="text-block__control-buttons">
                <span
                    v-bind:id="elementId('upLine')"
                    class="text-block__icon"
                    v-html="icons.up"
                    @click.prevent="$emit('up', index)"
                    alt="Up"
                ></span>
                <span
                    v-bind:id="elementId('downLine')"
                    class="text-block__icon"
                    v-html="icons.down"
                    @click.prevent="$emit('down', index)"
                    alt="Down"
                ></span>
                <span
                    :id="elementId('deleteLine')"
                    class="text-block__icon red-icon"
                    v-html="icons.remove"
                    @click.prevent="remove"
                    alt="Remove line"
                ></span>
            </div>
        </div>
        <div class="section__indented">
            <label class="form__label" :for="elementId('lineText')">Text</label>
            <input
                :id="elementId('lineText')"
                class="form__input line__text"
                type="text"
                :name="elementId('lineText')"
                autocomplete="off"
                v-model="text"
                v-on:input="update()"
                placeholder="Text"
            >
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                /**
                 * Line name.
                 *
                 * @type {String}
                 */
                name: this.line.name,

                /**
                 * Line from props.
                 *
                 * @type {Object}
                 */
                passedLine: this.line,

                /**
                 * Line text.
                 *
                 * @type {String}
                 */
                text: this.line.text,
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
             * Index of this line in lines array on parent.
             */
            index: {
                type: Number
            },

            /**
             * Line object.
             */
            line: {
                type: Object
            }
        },

        created () {
            this.update();
        },

        methods: {
            /**
             * Emit event to update the line.
             */
            update () {
                this.updatePassedLine();

                this.$emit('update', this.passedLine);
            },

            /**
             * Update the data in the passed line object.
             */
            updatePassedLine () {
                this.passedLine.name = this.name,
                this.passedLine.text = this.text,
                this.passedLine.index = this.index
            },

            /**
             * Emit event to remove line.
             */
            remove () {
                this.updatePassedLine();

                this.$emit('remove', this.passedLine);
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
