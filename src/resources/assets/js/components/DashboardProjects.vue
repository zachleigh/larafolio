<template>
    <div class="page__content dashboard__wrapper">
        <div class="page__top">
            <div class="page__top-block">
                <header-lines></header-lines>
                <h1 class="page__top-title">Projects</h1>
            </div>
        </div>
        <div v-show="count == 0">
            <h2>You have not added any projects yet.</h2>
            <a
                class="button button--primary"
                href="/manager/projects/add"
            >
                Add a Project
            </a>
        </div>
        <dashboard-tile
            v-for="(project, index) in passedProjects"
            :key="project.id"
            v-bind:index="index"
            :block="projectBlock(project)"
            :icons="icons"
            :image="projectImage(project)"
            :resource="project"
            resource-type="project"
            @down="moveProjectDown"
            @up="moveProjectUp"
        ></dashboard-tile>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';

    export default {
        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                /**
                 * Projects received from prop.
                 *
                 * @type {Array}
                 */
                passedProjects: this.projects
            }
        },

        props: {
            /**
             * Update project order action.
             */
            action: {
                type: String
            },

            /**
             * Blocks, keyed by project name.
             */
            blocks: {
                type: Object
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Images, keyed by project name.
             */
            images: {
                type: Object
            },

            /**
             * Array of all projects in portfolio.
             */
            projects: {
                type: Array
            }
        },

        computed : {
            count () {
                return this.passedProjects.length;
            }
        },

        methods: {
            /**
             * Move a project down.
             *
             * @param  {Number} index Index of project to move.
             */
            moveProjectDown (index) {
                let project = this.passedProjects.splice(index, 1);

                this.passedProjects.splice(index + 1, 0, project[0]);

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
                    this.flash({
                        title: 'Error',
                        message: 'Could not update project order',
                        type: 'error'
                    });
                });
            }
        }
    };
</script>
