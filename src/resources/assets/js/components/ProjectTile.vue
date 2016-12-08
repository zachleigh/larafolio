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
                type: Object
            },

            /**
             * This projects main image small url.
             */
            image: {
                type: String
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
            project: {
                type: Object
            }
        },

        computed: {
            link () {
                return '/manager/'+this.project.slug;
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
                return element + this.project.id;
            },
        }
    };
</script>
