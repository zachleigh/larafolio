<template>
    <div>
        <modal
            :show="showRemoveLineModal"
            :icons="icons"
            @close="showRemoveLineModal = false"
        >
            <h3 slot="header">
                Sanity Check
            </h3>
                <p slot="body">
                    Remove this line and loose all changes?
                </p>
                <div slot="footer">
                    <div class="modal__buttons">
                        <button
                            class="button button--secondary"
                            @click.prevent="showRemoveLineModal = false"
                        >
                            Don't Remove
                        </button>
                        <button
                            class="button button--primary"
                            @click.prevent="removeLine"
                        >
                            Remove Line
                        </button>
                    </div>
                </div>
        </modal>
        <text-line
            v-for="(line, index) in lines"
            :key="line.id"
            :line="line"
            :index="index"
            :icons="icons"
            @up="moveLineUp"
            @down="moveLineDown"
            @update="updateLines"
            @remove="toggleRemoveLineModal"
        >
        </text-line>
        <div class="project-form__section-controls">
            <button
                id="addLine"
                class="button button--secondary button--icon"
                @click.prevent="addLine"
            >+</button>
        </div>     
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';

    export default {
        mixins: [ Ajax ],

        data: function () {
            return {
                /**
                 * Array of lines for project.
                 *
                 * @type {Array}
                 */
                lines: [],

                /**
                 * Next line id.
                 *
                 * @type {Number}
                 */
                nextLine: 0,

                /**
                 * The currently selected line, if any.
                 *
                 * @type {String}
                 */
                currentLine: '',

                /**
                 * If true, show the remove line modal.
                 *
                 * @type {Boolean}
                 */
                showRemoveLineModal: false
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
             * Next line id value.
             */
            nextLineOrder: {
                type: Number
            },

            /**
             * Array of lines to render.
             */
            passedLines: {
                type: Array,
                default: []
            }
        },

        computed: {
            lineCount () {
                return this.lines.length;
            }
        },

        created () {
            if (typeof this.nextLineOrder !== 'undefined') {
                this.nextLine = this.nextLineOrder;
            }

            this.lines = this.passedLines;

            if (this.lineCount === 0) {
                this.addLine();
            }
        },

        methods: {
            /**
             * Add a new line to the project.
             */
            addLine () {
                this.lines.push({
                    order: this.nextLine,
                    id: this.nextLine++,
                });

                this.$emit('change', this.lines, true);
            },

            /**
             * Update currentLine in the lines array.
             *
             * @param  {Object} currentLine The line that was updated.
             */
            updateLines (currentLine) {
                let index = currentLine.index;

                this.lines.splice(index, 1, currentLine);

                this.$emit('change', this.lines, true);
            },

            /**
             * Set currentLine and show remove line confirmation modal.
             *
             * @param  {Object} currentLine Line object received through event.
             */
            toggleRemoveLineModal (currentLine) {
                this.currentLine = currentLine;

                this.showRemoveLineModal = !this.showRemoveLineModal;
            },
            
            /**
             * Remove a line from the project.
             */
            removeLine () {
                if (typeof this.currentLine.resource_id !== 'undefined') {
                    this.destroyLine(this.currentLine.id);
                }

                this.showRemoveLineModal = false;
                
                let index = this.currentLine.index;

                this.lines.splice(index, 1);

                this.$emit('change', this.lines, false);
            },

            /**
             * Destroy a line on the server.
             *
             * @param  {Number} id Id of line to destroy.
             */
            destroyLine (id) {
                this.ajax.delete('/manager/lines/'+id)
                .then(function (response) {
                    // console.log(response.data);
                })
                .catch(function (error) {
                    // this.errors = error.data;
                });
            },

            /**
             * Move current line up one position in list.
             *
             * @param  {Number} index Index of line object to move.
             */
            moveLineUp (index) {
                if (index > 0) {
                    let line = this.lines.splice(index, 1);

                    this.lines.splice(index - 1, 0, line[0]); 
                }

                this.$emit('change', this.lines, true);
            },

            /**
             * Move current line down one position in list.
             *
             * @param  {Number} index Index of line object to move.
             */
            moveLineDown (index) {
                let line = this.lines.splice(index, 1);

                this.lines.splice(index + 1, 0, line[0]);

                this.$emit('change', this.lines, true);
            },
        }
    };
</script>
