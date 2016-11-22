<template>
    <transition name="modal">
        <div class="modal__mask" v-show="show">
            <div class="modal__wrapper">
                <div class="modal__container">
                    <div class="modal__close" @click="close" v-html="closeButton">
                    </div>
                    <div class="modal__header">
                        <slot name="header">

                        </slot>
                    </div>
                    <div class="modal__body">
                        <slot name="body">
                            
                        </slot>
                    </div>
                    <div class="modal__footer">
                        <slot name="footer">
                            
                        </slot>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        props: {
            /**
             * If true, show the modal.
             */
            show: {
                type: Boolean,
                required: true
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            }
        },

        computed: {
            /**
             * If present, set button to remove icon. Else, use times symbol.
             *
             * @return {String}
             */
            closeButton () {
                if (typeof this.icons.remove !== 'undefined') {
                    return this.icons.remove;
                }

                return '&#215';
            }
        },

        methods: {
            /**
             * Close the current modal.
             */
            close () {
                this.$emit('close');
            }
        }
    };
</script>