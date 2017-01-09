<template>
    <div
        class="project-form__display-area"
        v-bind:style="{ top: top }"
    >
        <div class="link__display-text" v-html="link.text"></div>
        <a v-bind:href="link.url">
            {{ link.url }}
        </a>
    </div>
</template>

<script>
    import PreviewHeight from './PreviewHeight.js';

    export default {
        mixins: [ PreviewHeight ],

        data: function () {
            return {
                padding: 4
            }
        },

        props: {
            link: {
                type: Object
            },

            height: {
                type: Number
            }
        }
    };
</script>
