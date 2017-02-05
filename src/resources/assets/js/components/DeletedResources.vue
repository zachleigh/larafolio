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
                    Delete this {{ resourceType }} from the database?
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
                            Delete
                        </button>
                    </div>
                </div>

        </modal>
        <div
            class="section__item"
            v-if="resourcesEmpty"
        >
            No deleted {{ resourceTypePlural }}
        </div>
        <div
            class="section__item section__divided"
            v-for="(resource, index) in resources"
            key="resource.id"
        >
            <div class="">
                <div><b>{{ resource.name }}</b></div>
                <div class="section__indented">
                    Deleted {{ resource.deletedAt }}
                </div>
            </div>
            <div class="settings__button-row">
                <button
                    :id="'restore' + resource.id"
                    class="button button--primary button--small"
                    @click.prevent="restore(resource, index)"
                >
                    Restore
                </button>
                <button
                    :id="'delete' + resource.id"
                    class="button button--secondary button--small"
                    @click.prevent="confirmDelete(resource, index)"
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
    import Helpers from './../mixins/Helpers.js';

    export default {
        mixins: [ Ajax, Flash, Helpers ],

        data () {
            return {
                activeData: {},
                resources: this.passedResources,
                showDeleteModal: false
            }
        },

        props: {
            /**
             * Array of deleted resources.
             */
            passedResources: {
                type: Array
            },

            /**
             * Icon object.
             */
            icons: {
                type: Object
            },

            /**
             * Type of resource.
             */
            resourceType: {
                type: String
            }
        },

        computed: {
            resourcesEmpty () {
                return this.resources.length === 0;
            },

            resourceTypePlural () {
                return this.resourceType+'s';
            },

            resourceTypeCapitalized() {
                return this.capitalizeFirstLetter(this.resourceType);
            },

            deleteRoute () {
                return '/manager/'+this.resourceTypePlural+'/'+this.activeData.resource.slug;
            },

            refreshNavEvent () {
                return 'refreshNav'+this.capitalizeFirstLetter(this.resourceTypePlural);
            }
        },

        methods: {
            /**
             * Show modal to confirm resource deletion.
             *
             * @param  {Object} resource resource to delete.
             * @param  {Number} index   Index of resource in resources array.
             */
            confirmDelete (resource, index) {
                this.activeData = {
                    resource: resource,
                    index: index
                };

                this.showDeleteModal = true;
            },

            /**
             * Purge the resource from the database.
             */
            purge () {
                this.ajax.delete(this.deleteRoute)
                .then(function (response) {
                    this.removeFromResources(this.activeData.index);

                    this.hideDeleteModal();

                    this.flash({
                        title: 'Deleted',
                        message: this.resourceTypeCapitalized+' removed from database',
                        type: 'success'
                    });
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not remove '+this.resourceType,
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
             * Restore the resource.
             *
             * @param  {Object} resource resource to restore.
             * @param  {Number} index   Index of resource in resources array.
             */
            restore (resource, index) {
                this.removeFromResources(index);

                this.ajax.patch(
                    '/manager/'+this.resourceTypePlural+'/'+resource.slug+'/update'
                )
                .then(function (response) {
                    this.$bus.$emit(this.refreshNavEvent);

                    this.flash({
                        title: 'Restored',
                        message: this.resourceTypeCapitalized+' restored',
                        type: 'success'
                    });
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not restore '+this.resourceType,
                        type: 'error'
                    });
                });
            },

            /**
             * Remove the resource from the resources array.
             *
             * @param  {Numer} index Index of resource in resources array.
             */
            removeFromResources (index) {
                this.resources.splice(index, 1);
            }
        }
    };
</script>
