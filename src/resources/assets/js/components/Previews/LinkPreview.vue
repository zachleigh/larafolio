<template>
    <div
        class="project-form__display-area"
        v-bind:style="{ top: top }"
    >
        <div class="link__display-text">
            <slot name="text"></slot>
        </div>
        <slot name="url"></slot>
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
            height: {
                type: Number
            }
        }
    };
</script>
