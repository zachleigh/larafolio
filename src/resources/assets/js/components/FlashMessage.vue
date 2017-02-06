<template>
    <transition name="fade">
        <div
            class="flash-message"
            v-show="showFlash"
            v-bind:class="[typeClass]"
        >
            <div class="flash-message__top">
                <div class="flash-message__lines">
                    <header-lines></header-lines>
                </div>
                <div v-on:click="close" class="flash-message__close">
                    ✕
                </div>
                <div class="flash-message__title">
                    {{ message.title }}
                </div>
            </div>
            <div class="flash-message__bottom">
                <div class="flash-message__message">
                    {{ message.message }}
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        data () {
            return {
                /**
                 * If true, show flash mesasage.
                 *
                 * @type {Boolean}
                 */
                showFlash: true,
            };
        },

        props: {
            /**
             * The message to flash.
             */
            message: {
                type: Object,
                default: null
            }
        },

        computed: {
            /**
             * Return class for styling flash type.
             *
             * @return {String}
             */
            typeClass () {
                if (this.message) {
                    return 'flash-message--'+this.message.type;
                }
            }
        },

        created () {
            setTimeout(function () {
                this.showFlash = false;
            }.bind(this), 3500);
        },

        methods: {
            /**
             * Close the flash message.
             */
            close () {
                this.showFlash = false;
            },
        }
    };
</script>
