<template>
    <div>
        <flash-message
            v-for="message in messages"
            :message="message"
        >
        </flash-message>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                messages: [],
            };
        },

        props: {
            /**
             * Passed flash message.
             */
            passedMessage: {
                type: Object,
                default: null
            },
        },

        computed: {
            count () {
                return this.messages.length;
            },
        },

        created () {
            this.$bus.$on('flash', this.flash);

            if (this.passedMessage) {
                this.messages.push({
                    title: this.passedMessage.title,
                    message: this.passedMessage.message,
                    type: this.passedMessage.type
                });
            }
        },

        beforeDestroy () {
            this.$bus.$off('flash', this.flash);
        },

        methods: {
            /**
             * Flash a message.
             * 
             * @param {Object} data Flash message data.
             */
            flash (data) {
                this.messages.push({
                    title: data.title,
                    message: data.message,
                    type: data.type
                });
            },
        }
    };
</script>
