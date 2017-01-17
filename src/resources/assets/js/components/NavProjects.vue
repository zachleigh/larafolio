<template>
    <div>
        <a
            v-for="project in projects"
            class="nav__link" 
            v-bind:href="'/manager/' + project.slug">
            <div class="nav__dropdown-item">
                <span class="nav__dropdown-item-text">
                    {{ project.name }}
                </span>
            </div>
        </a>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';

    export default {
        mixins: [ Ajax ],

        data () {
            return {
                projects: this.passedProjects
            }
        },

        props: {
            passedProjects: {
                type: Array
            }
        },

        created () {
            this.$bus.$on('refreshNavProjects', this.refresh);
        },

        methods: {
            /**
             * Update the nav list projects with current from server.
             */
            refresh () {
                this.ajax.get('/manager/projects')
                .then(function (response) {
                    this.projects = response.data;
                })
                .catch(function (error) {
                    // this.errors = error.data;
                });
            }
        }
    };
</script>
