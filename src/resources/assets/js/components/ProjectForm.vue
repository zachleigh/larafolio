<template>
    <div class="project-form content">
        <modal
            :show="showRemoveBlockModal"
            :icons="icons"
            @close="showRemoveBlockModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
                <p slot="body">
                    Remove this text block and loose all changes?
                </p>
                <div slot="footer">
                    <div class="modal__buttons">
                        <button
                            class="button button--secondary"
                            @click.prevent="showRemoveBlockModal = false"
                        >
                            Don't Remove
                        </button>
                        <button
                            class="button button--primary"
                            @click.prevent="removeBlock"
                        >
                            Remove Block
                        </button>
                    </div>
                </div>
        </modal>
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
        <div class="top">
            <div class="top__title-block">
                <lines></lines>
                <h1 class="top__title">{{ title }}</h1>
            </div>
        </div>
        <div class="project__main">
            <div class="project__left project__half">
                <form
                    class="project-form__form form"
                    method="POST"
                    action=""
                >
                    <div id="name" class="form__section">
                        <h3 class="project-form__section-header">
                            <label for="name">
                                Project Name
                            </label>
                        </h3>
                        <input
                            class="form__input project-form__input"
                            type="text"
                            name="name"
                            autocomplete="off"
                            v-model="name"
                            v-bind:class="{ form__errored: hasError('name') }"
                        >
                    </div>
                    <div id="projectType" class="form__section">
                        <h3 class="project-form__section-header">
                            <label for="name">
                                Project Type
                            </label>
                        </h3>
                        <input
                            class="form__input project-form__input"
                            type="text"
                            name="projectType"
                            autocomplete="off"
                            v-model="projectType"
                            v-bind:class="{ form__errored: hasError('projectType') }"
                        >
                    </div>
                    <h3 class="project-form__section-header">Links</h3>
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
                    <h3 class="project-form__section-header">Text Blocks</h3>
                    <text-block
                        v-for="(block, index) in blocks"
                        :key="block.id"
                        :index="index"
                        :block="block"
                        :errors="errors"
                        :icons="icons"
                        @up="moveBlockUp"
                        @down="moveBlockDown"
                        @remove="toggleRemoveBlockModal"
                        @blockInput="updateBlocks"
                    ></text-block>
                    <div class="form__button-row">
                        <div v-show="type === 'add'">
                            <button
                                class="button button--green"
                                @click.prevent="addProject"
                                :disabled="buttonState"
                            >
                                Add Project
                            </button>
                        </div>
                        <div v-show="type === 'update'">
                            <button
                                class="button button--green"
                                @click.prevent="updateProject"
                                :disabled="buttonState"
                            >
                                Update Project
                            </button>
                            <a
                                class="button button--secondary"
                                v-bind:href="cancelAction"
                            >
                                Finished
                            </a>
                        </div>
                        <div class="project-form__section-controls">
                            <button
                                id="addBlock"
                                class="button button--secondary button--icon"
                                @click.prevent="addBlock"
                            >+</button>
                        </div>
                    </div>
                    <div class="form__errors" v-for="formField in errors">
                        <div class="form__error" v-for="error in formField">
                            {{ formatError(error) }}
                        </div>
                    </div>
                </form>
            </div>
            <div class="project__right project__half">
                <div class="project-form__preview placeholder">
                    Live Preview
                </div>
                <h2
                    id="displayName"
                    class="project-form__display-area"
                    v-bind:style="{ top: getHeight('name', 4) }"
                >
                    {{ name }}
                </h2>
                <div
                    id="displayProjectType"
                    class="project-form__display-area"
                    v-bind:style="{ top: getHeight('projectType', 6) }"
                >
                    {{ projectType }}
                </div>
                <div
                    v-for="(link, index) in links"
                    :key="link.id"
                    :id="'displayLink' + index"
                    class="project-form__display-area"
                    v-bind:style="{ top: getHeight('link' + index, 4) }"
                >
                    <div class="link__display-text" v-html="link.text"></div>
                    <a v-bind:href="link.url">
                        {{ link.url }}
                    </a>
                </div>
                <div
                    v-for="(block, index) in blocks"
                    :key="block.id"
                    v-bind:id="'displayBlock' + index"
                    class="project-form__display-area"
                    v-bind:style="{ top: getHeight('block' + index, 4) }"
                    v-html="block.formatted_text"
                >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import FormErrors from './../mixins/FormErrors.js';
    import Helpers from './../mixins/Helpers.js';
    import Modal from './Modal.vue';
    import ProjectLink from './ProjectLink.vue';
    import TextBlock from './TextBlock.vue';

    export default {
        components: { Modal, ProjectLink, TextBlock },

        mixins: [ Ajax, Flash, FormErrors, Helpers ],

        data: function () {
            return {
                /**
                 * Name of project.
                 *
                 * @type {String}
                 */
                name: '',

                /**
                 * Type of project.
                 *
                 * @type {String}
                 */
                projectType: '',

                /**
                 * Array of links for project.
                 *
                 * @type {Array}
                 */
                links: [],

                /**
                 * Array of blocks for project.
                 *
                 * @type {Array}
                 */
                blocks: [],

                /**
                 * If true, components (links, blocks) have changed.
                 *
                 * @type {Boolean}
                 */
                componentChanged: false,

                /**
                 * Next block order value.
                 *
                 * @type {Number}
                 */
                nextBlock: 0,

                /**
                 * Next link id.
                 *
                 * @type {Number}
                 */
                nextLink: 0,

                /**
                 * Heights of form fields, keyed by id.
                 *
                 * @type {Object}
                 */
                heights: {},

                /**
                 * The currently selected block, if any.
                 *
                 * @type {String}
                 */
                currentBlock: '',

                /**
                 * The currently selected link, if any.
                 *
                 * @type {String}
                 */
                currentLink: '',

                /**
                 * If true, show the remove block modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveBlockModal: false,

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
             * Add project action.
             */
            action: {
                type: String
            },

            /**
             * Cancel form action.
             */
            cancelAction: {
                type: String
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Next block order value.
             */
            nextBlockOrder: {
                type: Number
            },

            /**
             * Next link id value.
             */
            nextLinkOrder: {
                type: Number
            },

            /**
             * The project to edit, if type is update.
             */
            project: {
                type: Object
            },

            /**
             * Page title.
             */
            title: {
                type: String
            },

            /**
             * Form type: add, update.
             */
            type: {
                type: String
            }
        },

        computed: {
            /**
             * Return true if project form has changed.
             *
             * @return {Boolean}
             */
            changed () {
                if (this.project) {
                    return this.project.name !== this.name ||
                        this.project.type !== this.projectType ||
                        this.componentChanged;
                }

                return this.name !== '' ||
                    this.projectType !== '' ||
                    this.componentChanged;

            },

            /**
             * Update/Add button state. If form has changed, remove disabled.
             *
             * @return {String}
             */
            buttonState () {
                if (!this.changed) {
                    return 'disabled';
                }
            }
        },

        created () {
            if (typeof this.nextBlockOrder !== 'undefined') {
                this.nextBlock = this.nextBlockOrder;
            }

            if (typeof this.nextLinkOrder !== 'undefined') {
                this.nextLink = this.nextLinkOrder;
            }
   
            this.addBlock();

            this.addLink();

            if (typeof this.project !== 'undefined') {
                this.setInitialValues();
            }
        },

        mounted () {
            this.setHeights();
        },

        methods: {
            /**
             * Set the initial project values.
             */
            setInitialValues () {
                this.name = this.project.name;

                this.projectType = this.project.type;

                this.blocks = this.project.blocks;

                if (this.project.links.length >= 1) {
                    this.links = this.project.links;
                }
            },

            /**
             * Set heights for form and display elements.
             */
            setHeights () {
                let sections = document.querySelectorAll('.form__section');

                for (var i = 0; i < sections.length; i++) {
                    let section = sections[i];

                    var id = section.id;

                    let display = this.getDisplayById(id);

                    this.heights[id] = section.offsetTop;

                    this.setMarginBottom(section, display);
                }
            },

            /**
             * Get a display element from the form section id.
             *
             * @param  {String} id Form section ID.
             *
             * @return {Element}
             */
            getDisplayById (id) {
                let displayId = 'display'+this.capitalizeFirstLetter(id);

                return document.getElementById(displayId);
            },

            /**
             * Set the bottom margin on a form element if the corresponding
             * display element is longer.
             *
             * @param {Element} section Form section element.
             * @param {Element} display Display element.
             */
            setMarginBottom (section, display) {
                let sectionHeight = section.offsetHeight;

                let displayHeight = display.offsetHeight;

                let difference = displayHeight - sectionHeight - 15;

                if (displayHeight > sectionHeight && difference > 15) {
                    section.style.marginBottom = difference + 'px';
                }
            },

            /**
             * Get form element registered from heights object.
             *
             * @param  {String}  id       ID of form element.
             * @param  {Number}  padding  Additional padding if necessary.
             *
             * @return {String}    Height in px.
             */
            getHeight (id, padding) {
                this.setHeights();

                padding = typeof padding === 'undefined' ? 0 : padding;

                if (id === 'name' || id === 'projectType') {
                    return (this.heights[id] + 24 + padding) + 'px';
                }

                return (this.heights[id] + 46 + padding) + 'px';
            },

            /**
             * Submit ajax request to add a project.
             */
            addProject () {
                this.ajax.post(this.action, {
                    name: this.name,
                    type: this.projectType,
                    links: this.links,
                    blocks: this.blocks
                })
                .then(function (response) {
                    let slug = response.data.project.slug;

                    this.postFlash({
                        title: 'Added',
                        message: 'Project successfully added',
                        type: 'success'
                    }, '/manager/'+slug);
                })
                .catch(function (error) {
                    this.errors = error.data;
                });
            },

            /**
             * Update a project.
             */
            updateProject () {
                this.ajax.patch(this.action, {
                    name: this.name,
                    type: this.projectType,
                    links: this.links,
                    blocks: this.blocks
                })
                .then(function (response) {
                    let slug = response.data.project.slug;

                    this.componentChanged = false;

                    this.postFlash({
                        title: 'Updated',
                        message: 'Project successfully updated',
                        type: 'success'
                    }, '/manager/'+slug+'/edit');
                })
                .catch(function (error) {
                    this.errors = error.data;
                });
            },

            /**
             * Add a new text block to the project.
             */
            addBlock () {
                this.blocks.push({
                    order: this.nextBlock,
                    id: this.nextBlock++
                });
            },

            /**
             * Update currentBlock in the blocks array.
             *
             * @param  {Object} currentBlock The block that was updated.
             */
            updateBlocks (currentBlock) {
                let index = currentBlock.index;

                this.blocks.splice(index, 1, currentBlock);

                this.componentChanged = true;
            },

            /**
             * Move current block up one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveBlockUp (index) {
                this.componentChanged = true;

                if (index > 0) {
                    let block = this.blocks.splice(index, 1);

                    this.blocks.splice(index - 1, 0, block[0]); 
                }
            },

            /**
             * Move current block down one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveBlockDown (index) {
                this.componentChanged = true;

                let block = this.blocks.splice(index, 1);

                this.blocks.splice(index + 1, 0, block[0]);
            },

            /**
             * Set currentBlock and show remove block confirmation modal.
             *
             * @param  {Object} currentBlock Block object received through event.
             */
            toggleRemoveBlockModal (currentBlock) {
                this.currentBlock = currentBlock;

                this.showRemoveBlockModal = !this.showRemoveBlockModal;
            },

            /**
             * Remove a block from the blocks list.
             */
            removeBlock () {
                if (typeof this.currentBlock.project_id !== 'undefined') {
                    this.destroyBlock(this.currentBlock.id);
                }

                this.showRemoveBlockModal = false;
                
                let index = this.currentBlock.index;

                this.blocks.splice(index, 1);
            },

            /**
             * Make ajax call to server to remove block.
             *
             * @param  {Number} id Id of text block to remove.
             */
            destroyBlock (id) {
                this.ajax.delete('/manager/blocks/'+id)
                .then(function (response) {
                    // console.log(response.data);
                })
                .catch(function (error) {
                    this.errors = error.data;
                });
            },

            /**
             * Add a new link to the project.
             */
            addLink () {
                this.links.push({
                    order: this.nextLink,
                    id: this.nextLink++
                });
            },

            /**
             * Update currentLink in the links array.
             *
             * @param  {Object} currentLink The link that was updated.
             */
            updateLinks (currentLink) {
                let index = currentLink.index;

                this.links.splice(index, 1, currentLink);

                this.componentChanged = true;
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
                    this.errors = error.data;
                });
            },

            /**
             * Move current block up one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveLinkUp (index) {
                this.componentChanged = true;

                if (index > 0) {
                    let link = this.links.splice(index, 1);

                    this.links.splice(index - 1, 0, link[0]); 
                }
            },

            /**
             * Move current block down one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveLinkDown (index) {
                this.componentChanged = true;

                let link = this.links.splice(index, 1);

                this.links.splice(index + 1, 0, link[0]);
            },
        }
    };
</script>
