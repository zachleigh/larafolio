<template>
    <div>
        <a
            v-for="page in pages"
            class="nav__link" 
            v-bind:href="'/manager/pages/' + page.slug">
            <div class="nav__dropdown-item">
                <span class="nav__dropdown-item-text">
                    {{ page.name }}
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
                pages: this.passedPages
            }
        },

        props: {
            passedPages: {
                type: Array
            }
        },

        created () {
            this.$bus.$on('refreshNavPages', this.refresh);
        },

        methods: {
            /**
             * Update the nav list projects with current from server.
             */
            refresh () {
                this.ajax.get('/manager/pages')
                .then(function (response) {
                    this.pages = response.data;
                })
                .catch(function (error) {
                    // this.errors = error.data;
                });
            }
        }
    };
</script>
