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
        <div class="project-form__section-controls">
            <button
                id="addBlock"
                class="button button--secondary button--icon"
                @click.prevent="addBlock"
            >+</button>
        </div>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import FormErrors from './../mixins/FormErrors.js';
    import Helpers from './../mixins/Helpers.js';
    import Modal from './Modal.vue';
    import TextBlock from './TextBlock.vue';

    export default {
        components: { Modal, TextBlock },

        mixins: [ Ajax, Flash, FormErrors, Helpers ],

        data: function () {
            return {
                /**
                 * Array of blocks for project.
                 *
                 * @type {Array}
                 */
                blocks: [],

                /**
                 * Next block order value.
                 *
                 * @type {Number}
                 */
                nextBlock: 0,

                /**
                 * The currently selected block, if any.
                 *
                 * @type {String}
                 */
                currentBlock: '',

                /**
                 * If true, show the remove block modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveBlockModal: false,
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
             * Next block order value.
             */
            nextBlockOrder: {
                type: Number
            },

            /**
             * Array of blocks to render.
             */
            passedBlocks: {
                type: Array,
                default: []
            }
        },

        computed: {
            blockCount () {
                return this.blocks.length;
            }
        },

        created () {
            if (typeof this.nextBlockOrder !== 'undefined') {
                this.nextBlock = this.nextBlockOrder;
            }

            this.blocks = this.passedBlocks;

            if (this.blockCount === 0) {
                this.addBlock();
            }
        },

        methods: {
            /**
             * Add a new text block to the project.
             */
            addBlock () {
                this.blocks.push({
                    order: this.nextBlock,
                    id: this.nextBlock++
                });

                this.$emit('change', this.blocks);
            },

            /**
             * Update currentBlock in the blocks array.
             *
             * @param  {Object} currentBlock The block that was updated.
             */
            updateBlocks (currentBlock) {
                let index = currentBlock.index;

                this.blocks.splice(index, 1, currentBlock);

                this.$emit('change', this.blocks);
            },

            /**
             * Move current block up one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveBlockUp (index) {
                if (index > 0) {
                    let block = this.blocks.splice(index, 1);

                    this.blocks.splice(index - 1, 0, block[0]); 
                }

                this.$emit('change', this.blocks);
            },

            /**
             * Move current block down one position in list.
             *
             * @param  {Number} index Index of block object to move.
             */
            moveBlockDown (index) {
                let block = this.blocks.splice(index, 1);

                this.blocks.splice(index + 1, 0, block[0]);

                this.$emit('change', this.blocks);
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

                this.$emit('change', this.blocks);
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
        }
    };
</script>
