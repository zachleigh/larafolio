<template>
    <div>
        <modal
            :show="showDeleteModal"
            :icons="icons"
            @close="showDeleteModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
                <p slot="body">
                    Delete this project from the database?
                </p>
                <div slot="footer">
                    <div class="modal__buttons">
                        <button
                            id="dontDelete"
                            class="button button--secondary"
                            @click.prevent="hideDeleteModal"
                        >
                            Don't Delete
                        </button>
                        <button
                            id="confirmDelete"
                            class="button button--primary"
                            @click.prevent="purge"
                        >
                            Delete Project
                        </button>
                    </div>
                </div>

        </modal>
        <div
            class="section__item"
            v-if="projectsEmpty"
        >
            No deleted projects
        </div>
        <div
            class="section__item section__divided"
            v-for="(project, index) in projects"
            key="project.id"
        >
            <div class="">
                <div><b>{{ project.name }}</b></div>
                <div class="section__indented">
                    Deleted {{ project.deletedAt }}
                </div>
            </div>
            <div class="settings__button-row">
                <button
                    :id="'restore' + project.id"
                    class="button button--primary button--small"
                    @click.prevent="restore(project, index)"
                >
                    Restore
                </button>
                <button
                    :id="'delete' + project.id"
                    class="button button--secondary button--small"
                    @click.prevent="confirmDelete(project, index)"
                >
                    Delete
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import Modal from './Modal.vue';

    export default {
        components: { Modal },

        mixins: [ Ajax, Flash ],

        data () {
            return {
                activeData: {},
                projects: this.passedProjects,
                showDeleteModal: false
            }
        },

        props: {
            /**
             * Array of deleted projects.
             */
            passedProjects: {
                type: Array
            },

            /**
             * Icon object.
             */
            icons: {
                type: Object
            }
        },

        computed: {
            projectsEmpty () {
                return this.projects.length === 0;
            }
        },

        methods: {
            /**
             * Show modal to confirm project deletion.
             *
             * @param  {Object} project Project to delete.
             * @param  {Number} index   Index of project in projects array.
             */
            confirmDelete (project, index) {
                this.activeData = {
                    project: project,
                    index: index
                };

                this.showDeleteModal = true;
            },

            /**
             * Purge the project from the database.
             */
            purge () {
                this.ajax.delete('/manager/'+this.activeData.project.slug)
                .then(function (response) {
                    this.removeFromProjects(this.activeData.index);

                    this.hideDeleteModal();

                    this.flash({
                        title: 'Deleted',
                        message: 'Project removed from database',
                        type: 'success'
                    });
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not remove project',
                        type: 'error'
                    });
                });
            },

            /**
             * Clear all active data and hide the delete modal.
             */
            hideDeleteModal () {
                this.activeData = {};

                this.showDeleteModal = false;
            },

            /**
             * Restore the project.
             *
             * @param  {Object} project Project to restore.
             * @param  {Number} index   Index of project in projects array.
             */
            restore (project, index) {
                this.removeFromProjects(index);

                this.ajax.patch('/manager/'+project.slug+'/update')
                .then(function (response) {
                    this.flash({
                        title: 'Restored',
                        message: 'Project un-deleted',
                        type: 'success'
                    });
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not restore project',
                        type: 'error'
                    });
                });
            },

            /**
             * Remove the project from the projects array.
             * @param  {Numer} index Index of project in projects array.
             */
            removeFromProjects (index) {
                this.projects.splice(index, 1);
            }
        }
    };
</script>
