<template>
    <div class="project__main dashboard__wrapper">
        <project-tile
            v-for="(project, index) in passedProjects"
            :key="project.id"
            v-bind:index="index"
            :block="projectBlock(project)"
            :icons="icons"
            :image="projectImage(project)"
            :project="project"
            @down="moveProjectDown"
            @up="moveProjectUp"
        ></project-tile>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import ProjectTile from './ProjectTile.vue';

    export default {
        components: { ProjectTile },

        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                passedProjects: this.projects
            }
        },

        props: {
            action: {
                type: String
            },

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
             * Move a project down.
             *
             * @param  {Number} index Index of project to move.
             */
            moveProjectDown (index) {
                let project = this.passedProjects.splice(index, 1);

                this.projects.splice(index + 1, 0, project[0]);

                this.updateProjectOrder();
            },

            /**
             * Move a project up.
             *
             * @param  {Number} index Index of project to move.
             */
            moveProjectUp (index) {
                if (index > 0) {
                    let project = this.passedProjects.splice(index, 1);

                    this.passedProjects.splice(index - 1, 0, project[0]);

                    this.updateProjectOrder();
                }
            },

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
            },

            /**
             * Update the order of projects in the portfolio.
             */
            updateProjectOrder () {
                this.ajax.patch(this.action, {
                    projects: this.passedProjects
                })
                .then(function (response) {
                    //
                })
                .catch(function (error) {
                    // this.errors = error.data;
                });
            }
        }
    };
</script>
