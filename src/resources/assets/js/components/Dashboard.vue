<template>
    <div class="project__main dashboard__wrapper">
        <project-tile
            v-for="project in projects"
            :block="projectBlock(project)"
            :icons="icons"
            :image="projectImage(project)"
            :project="project"
        ></project-tile>
    </div>
</template>

<script>
    import ProjectTile from './ProjectTile.vue';

    export default {
        components: { ProjectTile },

        mixins: [],

        data: function () {
            return {
                //
            }
        },

        props: {
            blocks: {
                type: Object
            },

            icons: {
                type: Object
            },

            images: {
                type: Object
            },

            projects: {
                type: Array
            }
        },

        computed: {
            //
        },

        created () {
            //
        },

        mounted () {
            //
        },

        methods: {
            /**
             * Return block for project.
             *
             * @param  {Object} project Project object.
             *
             * @return {String}
             */
            projectBlock (project) {
                return this.blocks[project.name];
            },

            /**
             * Return image url for project.
             *
             * @param  {Object} project Project object.
             *
             * @return {String}
             */
            projectImage (project) {
                return this.images[project.name];
            }
        }
    };
</script>
