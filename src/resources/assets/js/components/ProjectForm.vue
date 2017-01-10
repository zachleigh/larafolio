<template>
    <div class="project-form content">
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
                    <links
                        :passedLinks="links"
                        :nextLinkOrder="nextLinkOrder"
                        :icons="icons"
                        @change="updateLinks"
                    ></links>
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
            <div class="project__right project__half">
                <div class="project-form__preview placeholder">
                    Live Preview
                </div>
                <name-preview
                    :name="name"
                    :height="getHeight('name')"
                ></name-preview>
                <type-preview
                    :type="projectType"
                    :height="getHeight('projectType')"
                ></type-preview>
                <link-preview
                    v-for="(link, index) in links"
                    :key="link.id"
                    :id="'displayLink' + index"
                    :link="link"
                    :height="getHeight('link' + index)"
                >
                    {{ }}
                </link-preview>
                <block-preview
                    v-for="(block, index) in blocks"
                    :key="block.id"
                    :id="'displayBlock' + index"
                    :block="block"
                    :height="getHeight('block' + index)"
                >
                    {{ }}
                </block-preview>
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
            if (typeof this.project !== 'undefined') {
                this.setInitialValues();
            }
        },

        mounted () {
            this.updateLinks(this.links);

            this.updateBlocks(this.blocks);

            this.componentChanged = false;

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
             * Update blocks array.
             *
             * @param  {Array} blocks Array of updated blocks.
             */
            updateBlocks (blocks) {
                this.blocks = blocks;

                this.componentChanged = true;
            },

            /**
             * Update links array.
             *
             * @param  {Array} links Array of updated links.
             */
            updateLinks (links) {
                this.links = links;

                this.componentChanged = true;
            }
        }
    };
</script>
