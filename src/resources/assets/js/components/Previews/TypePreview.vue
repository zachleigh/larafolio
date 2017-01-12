<template>
    <div
        id="displayType"
        class="project-form__display-area"
        v-bind:style="{ top: top }"
    >
        <slot></slot>
    </div>
</template>

<script>
    import PreviewHeight from './PreviewHeight.js';

    export default {
        mixins: [ PreviewHeight ],

        data: function () {
            return {
                padding: 6
            }
        },

        props: {
            height: {
                type: Number
            }
        }
    };
</script>
