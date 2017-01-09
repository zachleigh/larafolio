<template>
    <h2
        id="displayName"
        class="project-form__display-area"
        v-bind:style="{ top: top }"
    >
        {{ name }}
    </h2>
</template>

<script>
    import PreviewHeight from './PreviewHeight.js';

    export default {
        mixins: [ PreviewHeight ],
        
        data: function () {
            return {
                padding: 4,
            }
        },

        props: {
            name: {
                type: String
            }
        }
    };
</script>
