<template>
    <section class="dashboard__item">
        <div class="dashboard__arrows">
            <span
                v-bind:id="elementId('up')"
                class="nav__icon black-icon"
                v-html="icons.up"
                @click="$emit('up', index)"
            >                 
            </span>
            <span
                v-bind:id="elementId('down')"
                class="nav__icon black-icon"
                v-html="icons.down"
                @click="$emit('down', index)"
            >                 
            </span>
        </div>
        <div class="dashboard__flex-medium">
            <div class="dashboard__flex-tiny">
                <div class="dashboard__info">
                    <div>
                        <h2 class="dashboard__name">
                            {{ resource.name }}
                        </h2>
                        <div
                            class="resource-controls__section dashboard__visibility"
                            v-show="visible"
                        >
                            <span
                                :id="elementId('makeHidden')"
                                class="nav__icon green-icon"
                                v-html="icons.visible"
                                @click.prevent="changeVisibility(false)"
                            >
                            </span>
                            Visible
                        </div>
                        <div
                            class="resource-controls__section dashboard__visibility"
                            v-show="!visible"
                        >
                            <span
                                :id="elementId('makeVisible')"
                                class="nav__icon red-icon"
                                v-html="icons.hidden"
                                @click.prevent="changeVisibility(true)"
                            >                 
                            </span>
                            Hidden
                        </div>
                        <div class="dashboard__type" v-show="resource.type">
                            Type: {{ resource.type }}
                        </div>
                    </div>
                    <div>
                        <a
                            :id="elementId('manage')"
                            class="button button--primary button--small"
                            v-bind:href="link"
                        >
                            Manage
                        </a>
                    </div>
                </div>
                <div
                    class="dashboard__photo"
                    v-show="image"
                >
                    <img v-bind:src="image">
                </div>
            </div>
            <div class="dashboard__description" v-html="block">
            </div>
        </div>
    </section>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';
    import Visibility from './../mixins/Visibility.js';

    export default {
        mixins: [ Ajax, Flash, Visibility ],

        data: function () {
            return {
                //
            }
        },

        props: {
            /**
             * This projects mian block formatted text.
             */
            block: {
                type: String
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object,
                default: null
            },

            /**
             * This projects main image small url.
             */
            image: {
                type: String,
                default: null
            },

            /**
             * This projects index in projects array on parent.
             */
            index: {
                type: Number
            },

            /**
             * This project.
             */
            resource: {
                type: Object
            }
        },

        computed: {
            link () {
                return '/manager/projects/'+this.resource.slug;
            }
        },

        methods: {
            /**
             * Make unique id for given element.
             *
             * @param  {[type]} element [description]
             * @return {[type]}         [description]
             */
            elementId (element) {
                return element + this.resource.id;
            },
        }
    };
</script>
