<template>
    <div class="project-form content">
        <modal
            :show="showRemoveModal"
            :icons="icons"
            @close="showRemoveModal = false"
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
                            @click.prevent="showRemoveModal = false"
                        >
                            Don't Remove
                        </button>
                        <button
                            class="button button--blue"
                            @click.prevent="removeBlock"
                        >
                            Remove Block
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
                        <label class="form__label" for="name">Project Name</label>
                        <input
                            class="form__input"
                            type="text"
                            name="name"
                            autocomplete="off"
                            v-model="name"
                            v-bind:class="{ form__errored: hasError('name') }"
                        >
                    </div>
                    <div id="link" class="form__section">
                        <label class="form__label" for="name">External Link</label>
                        <input
                            class="form__input"
                            type="text"
                            name="link"
                            autocomplete="off"
                            v-model="link"
                        >
                    </div>
                    <div class="form__label" for="name">Text Blocks</div>
                    <text-block
                        v-for="(block, index) in blocks"
                        :key="block.id"
                        v-bind:index="index"
                        :block="block"
                        :errors="errors"
                        :icons="icons"
                        @up="moveBlockUp"
                        @down="moveBlockDown"
                        @remove="toggleRemoveModal"
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
                        <button
                            class="button button--secondary button--icon"
                            @click.prevent="addBlock"
                        >
                            +
                        </button>
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
                    v-bind:style="{ top: getHeight('name') }"
                >
                    {{ name }}
                </h2>
                <div
                    id="displayLink"
                    class="project-form__display-area"
                    v-bind:style="{ top: getHeight('link') }"
                >
                    {{ link }}
                </div>
                <div
                    v-for="(block, index) in blocks"
                    :key="block.id"
                    v-bind:id="getBlockId(index)"
                    class="project-form__display-area"
                    v-bind:style="{ top: getHeight('block' + index) }"
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
    import Modal from './Modal.vue';
    import TextBlock from './TextBlock.vue';

    export default {
        components: { Modal, TextBlock },

        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                name: '',
                link: '',
                blocks: [],
                errors: {},
                blocksChanged: false,
                nextOrder: 0,
                heights: {},
                currentBlock: '',
                showRemoveModal: false
            }
        },

        props: {
            /**
             * Form action.
             */
            action: {
                type: String
            },

            buttonText: {
                type: String
            },

            cancelAction: {
                type: String
            },

            /**
             * Object containing icons.
             */
            icons: {
                type: Object
            },

            nextInOrder: {
                type: Number
            },

            project: {
                type: Object
            },

            title: {
                type: String
            },

            type: {
                type: String
            }
        },

        computed: {
            changed () {
                if (this.project) {
                    return this.project.name !== this.name ||
                        this.project.link !== this.link ||
                        this.blocksChanged;
                }

                return this.name !== '' ||
                    this.link !== '' ||
                    this.blocksChanged;

            },

            buttonState () {
                if (!this.changed) {
                    return 'disabled';
                }
            }
        },

        created () {
            if (typeof this.nextInOrder !== 'undefined') {
                this.nextOrder = this.nextInOrder;
            }
   
            this.addBlock();

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

                this.link = this.project.link;

                this.blocks = this.project.blocks;
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
             * @param {Element} section For section element.
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
             * @param  {String} id ID of form element.
             *
             * @return {String}    Height in px.
             */
            getHeight (id) {
                this.setHeights();

                return (this.heights[id] + 24) + 'px';
            },

            /**
             * Submit ajax request to add a project.
             */
            addProject () {
                this.ajax.post(this.action, {
                    name: this.name,
                    link: this.link,
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
                    link: this.link,
                    blocks: this.blocks
                })
                .then(function (response) {
                    let slug = response.data.project.slug;

                    this.blocksChanged = false;

                    this.postFlash({
                        title: 'Updated',
                        message: 'Project successfully updated',
                        type: 'success'
                    }, '/manager/'+slug+'/edit');
                })
                .catch(function (error) {
                    // this.errors = error.data;
                });
            },

            /**
             * Return true if field has error and is empty.
             *
             * @param  {String}  field
             *
             * @return {Boolean}
             */
            hasError (field) {
                if (field in this.errors && !this[field]) {
                    return true;
                }
            },

            /**
             * Add a new text block to the project.
             */
            addBlock () {
                this.blocks.push({
                    order: this.nextOrder,
                    id: this.nextOrder++
                });
            },

            /**
             * Move current block up one position in list.
             *
             * @param  {Object} currentBlock Block object received through event.
             */
            moveBlockUp (currentBlock) {
                let index = currentBlock.index;

                this.blocksChanged = true;

                if (index > 0) {
                    let block = this.blocks.splice(index, 1);

                    this.blocks.splice(index - 1, 0, block[0]); 
                }
            },

            /**
             * Move current block down one position in list.
             *
             * @param  {Object} currentBlock Block object received through event.
             */
            moveBlockDown (currentBlock) {
                let index = currentBlock.index;

                this.blocksChanged = true;

                let block = this.blocks.splice(index, 1);

                this.blocks.splice(index + 1, 0, block[0]);
            },

            /**
             * Set currentBlock on object and show remove confirmation modal.
             *
             * @param  {Object} currentBlock Block object received through event.
             */
            toggleRemoveModal (currentBlock) {
                this.currentBlock = currentBlock;

                this.showRemoveModal = !this.showRemoveModal;
            },

            /**
             * Remove a block from the blocks list.
             */
            removeBlock () {
                if (typeof this.currentBlock.project_id !== 'undefined') {
                    this.destroyBlock(this.currentBlock.id);
                }

                this.showRemoveModal = false;
                
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
                    console.log(response.data);
                })
                .catch(function (error) {
                    this.errors = error.data;
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

                this.blocksChanged = true;
            },

            /**
             * Get the block ID from the block index.
             *
             * @param  {Number} index Block index.
             *
             * @return {String}
             */
            getBlockId (index) {
                return 'displayBlock' + index;
            },

            /**
             * Capitalize the first letter of a string.
             *
             * @param  {String} string
             *
             * @return {String}
             */
            capitalizeFirstLetter (string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            },

            /**
             * Format text block errors before display.
             *
             * @param  {String} error Error to format.
             *
             * @return {String}
             */
            formatError (error) {
                if (error.includes('.text')) {
                    let errorArray = error.split('.');

                    return 'The block ' + errorArray[2] + '.';
                }

                return error;
            }
        }
    };
</script>
