<template>
    <section class="dashboard__item">
        <div class="dashboard__arrows">
            <span
                v-bind:id="elementId('up')"
                class="nav__icon black-icon"
                v-html="icons.up"
                @click="moveUp"
            >                 
            </span>
            <span
                v-bind:id="elementId('down')"
                class="nav__icon black-icon"
                v-html="icons.down"
                @click="moveDown"
            >                 
            </span>
        </div>
        <div class="dashboard__info">
            <div>
                <h2 class="dashboard__name">
                    {{ project.name }}
                </h2>
                <div
                    class="project-controls__section dashboard__visibility"
                    v-show="project.visible"
                >
                    <span class="nav__icon green-icon" v-html="icons.visible">
                    </span>
                    Visible
                </div>
                <div
                    class="project-controls__section dashboard__visibility"
                    v-show="!project.visible"
                >
                    <span class="nav__icon red-icon" v-html="icons.hidden">                 
                    </span>
                    Hidden
                </div>
                <div>
                    Type: {{ project.type }}
                </div>
            </div>
            <div>
                <a
                    class="button button--blue"
                    v-bind:href="link"
                >
                    Manage
                </a>
            </div>
        </div>
        <div class="dashboard__photo">
            <img v-bind:src="image">
        </div>
        <div class="dashboard__description" v-html="block">
        </div>
    </section>
</template>

<script>
    export default {
        components: {},

        mixins: [],

        data: function () {
            return {
                //
            }
        },

        props: {
            block: {
                type: String
            },

            icons: {
                type: Object
            },

            image: {
                type: String
            },

            index: {
                type: Number
            },

            project: {
                type: Object
            }
        },

        computed: {
            link () {
                return '/manager/'+this.project.slug;
            }
        },

        created () {
            //
        },

        mounted () {
            //
        },

        methods: {
            elementId (element) {
                return element + this.project.id;
            },

            moveDown () {
                this.$emit('down', this.index);
            },

            moveUp () {
                this.$emit('up', this.index);
            }
        }
    };
</script>
