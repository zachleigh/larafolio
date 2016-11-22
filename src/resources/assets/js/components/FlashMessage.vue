<template>
    <transition name="fade">
        <div
            v-show="showFlash"
            class="flash-message"
            v-bind:class="[typeClass]"
        >
            <div class="flash-message__top">
                <div class="flash-message__lines">
                    <lines></lines>
                </div>
                <div v-on:click="close" class="flash-message__close">
                    ✕
                </div>
                <div class="flash-message__title">
                    {{ passedTitle }}
                </div>
            </div>
            <div class="flash-message__bottom">
                <div class="flash-message__message">
                    {{ passedMessage }}
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    import Lines from './Lines.vue';

    export default {
        components: { Lines },

        data () {
            return {
                /**
                 * If true, show flash mesasage.
                 *
                 * @type {Boolean}
                 */
                showFlash: false,

                /**
                 * Flash title.
                 *
                 * @type {String}
                 */
                passedTitle: this.title,

                /**
                 * Flash message.
                 *
                 * @type {String}
                 */
                passedMessage: this.message,

                /**
                 * Flash type.
                 *
                 * @type {String}
                 */
                passedType: this.type
            };
        },

        props: {
            title: {
                type: String
            },

            message: {
                type: String
            },

            type: {
                type: String
            }
        },

        computed: {
            /**
             * Return class for styleing flash type.
             *
             * @return {String}
             */
            typeClass () {
                return 'flash-message--'+this.passedType;
            }
        },

        created () {
            this.$bus.$on('flash', this.flash);

            if (typeof this.message !== 'undefined') {
                 this.show();
            }
        },

        beforeDestroy () {
            this.$bus.$off('flash', this.flash);
        },

        methods: {
            /**
             * Close the flash message.
             */
            close () {
                this.showFlash = false;
            },

            /**
             * Flash a message.
             * 
             * @param {Object} data Flash message data.
             */
            flash (data) {
                this.passedTitle = data.title;

                this.passedMessage = data.message;

                this.passedType = data.type;
                
                this.show();
            },

            /**
             * Show flash message for 3.5 sec.
             */
            show () {
                this.showFlash = true;

                setTimeout(function () {
                    this.showFlash = false;
                }.bind(this), 3500);
            }
        }
    };
</script>
