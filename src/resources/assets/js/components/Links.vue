<template>
    <div>
        <modal
            :show="showRemoveLinkModal"
            :icons="icons"
            @close="showRemoveLinkModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
                <p slot="body">
                    Remove this link and loose all changes?
                </p>
                <div slot="footer">
                    <div class="modal__buttons">
                        <button
                            class="button button--secondary"
                            @click.prevent="showRemoveLinkModal = false"
                        >
                            Don't Remove
                        </button>
                        <button
                            class="button button--primary"
                            @click.prevent="removeLink"
                        >
                            Remove Link
                        </button>
                    </div>
                </div>
        </modal>
        <project-link
            v-for="(link, index) in links"
            :key="link.id"
            :link="link"
            :index="index"
            :icons="icons"
            @up="moveLinkUp"
            @down="moveLinkDown"
            @update="updateLinks"
            @remove="toggleRemoveLinkModal"
        >
        </project-link>
        <div class="project-form__section-controls">
            <button
                id="addLink"
                class="button button--secondary button--icon"
                @click.prevent="addLink"
            >+</button>
        </div>     
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Modal from './Modal.vue';
    import ProjectLink from './ProjectLink.vue';

    export default {
        components: { Modal, ProjectLink },

        mixins: [ Ajax ],

        data: function () {
            return {
                /**
                 * Array of links for project.
                 *
                 * @type {Array}
                 */
                links: [],

                /**
                 * Next link id.
                 *
                 * @type {Number}
                 */
                nextLink: 0,

                /**
                 * The currently selected link, if any.
                 *
                 * @type {String}
                 */
                currentLink: '',

                /**
                 * If true, show the remove link modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveLinkModal: false
            }
        },

        props: {
            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Next link id value.
             */
            nextLinkOrder: {
                type: Number
            },

            /**
             * Array of links to render.
             */
            passedLinks: {
                type: Array,
                default: []
            }
        },

        computed: {
            linkCount () {
                return this.links.length;
            }
        },

        created () {
            if (typeof this.nextLinkOrder !== 'undefined') {
                this.nextLink = this.nextLinkOrder;
            }

            this.links = this.passedLinks;

            if (this.linkCount === 0) {
                this.addLink();
            }
        },

        methods: {
            /**
             * Add a new link to the project.
             */
            addLink () {
                this.links.push({
                    order: this.nextLink,
                    id: this.nextLink++,
                });

                this.$emit('change', this.links);
            },

            /**
             * Update currentLink in the links array.
             *
             * @param  {Object} currentLink The link that was updated.
             */
            updateLinks (currentLink) {
                let index = currentLink.index;

                this.links.splice(index, 1, currentLink);

                this.$emit('change', this.links);
            },

            /**
             * Set currentLink and show remove link confirmation modal.
             *
             * @param  {Object} currentLink Link object received through event.
             */
            toggleRemoveLinkModal (currentLink) {
                this.currentLink = currentLink;

                this.showRemoveLinkModal = !this.showRemoveLinkModal;
            },
            
            /**
             * Remove a link from the project.
             */
            removeLink () {
                if (typeof this.currentLink.project_id !== 'undefined') {
                    this.destroyLink(this.currentLink.id);
                }

                this.showRemoveLinkModal = false;
                
                let index = this.currentLink.index;

                this.links.splice(index, 1);

                this.$emit('change', this.links);
            },

            /**
             * Destroy a link on the server.
             *
             * @param  {Number} id Id of link to destroy.
             */
            destroyLink (id) {
                this.ajax.delete('/manager/links/'+id)
                .then(function (response) {
                    // console.log(response.data);
                })
                .catch(function (error) {
                    // this.errors = error.data;
                });
            },

            /**
             * Move current block up one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveLinkUp (index) {
                if (index > 0) {
                    let link = this.links.splice(index, 1);

                    this.links.splice(index - 1, 0, link[0]); 
                }

                this.$emit('change', this.links);
            },

            /**
             * Move current block down one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveLinkDown (index) {
                let link = this.links.splice(index, 1);

                this.links.splice(index + 1, 0, link[0]);

                this.$emit('change', this.links);
            },
        }
    };
</script>
