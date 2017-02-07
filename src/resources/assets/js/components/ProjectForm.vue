<template>
    <div class="page">
        <div class="page__top">
            <div class="page__top-block">
                <header-lines></header-lines>
                <h1 class="page__top-title">{{ title }}</h1>
            </div>
        </div>
        <div class="page__content">
            <div class="page__half">
                <form
                    class="project-form__form form"
                    method="POST"
                    action=""
                >
                    <div id="name" class="section">
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
                            v-on:input="projectChanged()"
                        >
                    </div>
                    <div id="projectType" class="section">
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
                            v-on:input="projectChanged()"
                        >
                    </div>
                    <h3 class="project-form__section-header">Links</h3>
                    <links
                        :passedLinks="links"
                        :nextLinkOrder="nextLinkOrder"
                        :icons="icons"
                        @change="updateLinks"
                    ></links>
                    <h3 class="project-form__section-header">Text Lines</h3>
                    <text-lines
                        :passedLines="lines"
                        :nextLinkOrder="nextLineOrder"
                        :icons="icons"
                        @change="updateLines"
                    ></text-lines>
                    <h3 class="project-form__section-header">Text Blocks</h3>
                    <blocks
                        :passedBlocks="blocks"
                        :nextBlockOrder="nextBlockOrder"
                        :icons="icons"
                        @change="updateBlocks"
                    ></blocks>
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
                    </div>
                    <div class="form__errors" v-for="formField in errors">
                        <div class="form__error" v-for="error in formField">
                            {{ formatError(error) }}
                        </div>
                    </div>
                </form>
            </div>
            <div class="page__half">
                <div class="project-form__preview placeholder">
                    Live Preview
                </div>
                <name-preview
                    :height="getHeight('name')"
                >
                    {{ name }}
                </name-preview>
                <type-preview
                    :height="getHeight('projectType')"
                >
                    {{ projectType }}
                </type-preview>
                <link-preview
                    v-for="(link, index) in links"
                    :key="link.id"
                    :id="'displayLink' + index"
                    :height="getHeight('link' + index)"
                >
                    <span slot="text">{{ link.text }}</span>
                    <a slot="url" v-bind:href="link.url">
                        {{ link.url }}
                    </a>
                </link-preview>
                <line-preview
                    v-for="(line, index) in lines"
                    :key="line.id"
                    :id="'displayLine' + index"
                    :height="getHeight('line' + index)"
                >
                    <span slot="text">{{ line.text }}</span>
                </line-preview>
                <block-preview
                    v-for="(block, index) in blocks"
                    :key="block.id"
                    :id="'displayBlock' + index"
                    :height="getHeight('block' + index)"
                    v-html="block.formatted_text"
                ></block-preview>
            </div>
        </div>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import FormErrors from './../mixins/FormErrors.js';
    import FormPreview from './../mixins/FormPreview.js';
    import Helpers from './../mixins/Helpers.js';

    export default {
        mixins: [ Ajax, Flash, FormErrors, FormPreview, Helpers ],

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
                 * Array of lines for project.
                 *
                 * @type {Array}
                 */
                lines: [],

                /**
                 * Array of blocks for project.
                 *
                 * @type {Array}
                 */
                blocks: [],

                /**
                 * If true, components (links, lines, blocks) have changed.
                 *
                 * @type {Boolean}
                 */
                componentChanged: false,

                /**
                 * If true, project is being saved.
                 *
                 * @type {Boolean}
                 */
                saving: false
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
             * Next line id value.
             */
            nextLineOrder: {
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
             * Update/Add button state. If form has changed, remove disabled.
             *
             * @return {String}
             */
            buttonState () {
                if (!this.componentChanged) {
                    return 'disabled';
                }
            }
        },

        created () {
            if (typeof this.project !== 'undefined') {
                this.setInitialValues();
            }
        },

        mounted () {
            this.updateLinks(this.links);

            this.updateBlocks(this.blocks);

            this.updateLines(this.lines);

            this.projectUpToDate();

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

                if (this.project.lines.length >= 1) {
                    this.lines = this.project.lines;
                }
            },

            /**
             * Submit ajax request to add a project.
             */
            addProject () {
                this.saving = true;

                this.ajax.post(this.action, {
                    name: this.name,
                    type: this.projectType,
                    links: this.links,
                    lines: this.lines,
                    blocks: this.blocks
                })
                .then(function (response) {
                    let slug = response.data.project.slug;

                    this.postFlash({
                        title: 'Added',
                        message: 'Project successfully added',
                        type: 'success'
                    }, '/manager/projects/'+slug);
                })
                .catch(function (error) {
                    this.errors = error.data;
                });
            },

            /**
             * Update a project.
             */
            updateProject () {
                this.saving = true;

                this.ajax.patch(this.action, {
                    id: this.project.id,
                    name: this.name,
                    type: this.projectType,
                    links: this.links,
                    lines: this.lines,
                    blocks: this.blocks
                })
                .then(function (response) {
                    let slug = response.data.project.slug;

                    this.projectUpToDate();

                    this.postFlash({
                        title: 'Updated',
                        message: 'Project successfully updated',
                        type: 'success'
                    }, '/manager/projects/'+slug+'/edit');
                })
                .catch(function (error) {
                    this.errors = error.data;
                });
            },

            /**
             * Update blocks array.
             *
             * @param  {Array} blocks Array of updated blocks.
             */
            updateBlocks (blocks, changed) {
                this.blocks = blocks;

                if (changed) {
                    this.projectChanged();
                }
            },

            /**
             * Update links array.
             *
             * @param  {Array} links   Array of updated links.
             * @param  {Bool}  changed True if change requires project save.
             */
            updateLinks (links, changed) {
                this.links = links;

                if (changed) {
                    this.projectChanged();
                }
            },

            /**
             * Update lines array.
             *
             * @param  {Array} lines   Array of updated lines.
             * @param  {Bool}  changed True if change requires project save.
             */
            updateLines (lines, changed) {
                this.lines = lines;

                if (changed) {
                    this.projectChanged();
                }
            },

            /**
             * Add onbeforeunload event and change componentChanged state.
             */
            projectChanged () {
                if (!this.componentChanged) {
                    window.onbeforeunload = function() {
                        if (!this.saving) {
                            return 'Form contains unsaved content. '+
                                'Are you sure you want to leave?';
                        }
                    }.bind(this);

                    this.componentChanged = true;
                }
            },

            /**
             * Remove onbeforeunload event and change componentChanged state.
             */
            projectUpToDate () {
                this.componentChanged = false;

                window.onbeforeunload = function () {
                    //
                }

                this.saving = false;
            }
        }
    };
</script>
