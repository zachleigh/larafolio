<template>
    <div class="page__content dashboard__wrapper">
        <div class="page__top">
            <div class="page__top-block">
                <header-lines></header-lines>
                <h1 class="page__top-title">Pages</h1>
            </div>
            <div>
                <a
                    href="/manager/pages/add"
                    class="button button--small button--green"
                >
                    Add Page
                </a>
            </div>
        </div>
        <div v-show="count == 0">
            <h2>You have not added any pages yet.</h2>
            <a
                class="button button--primary"
                href="/manager/pages/add"
            >
                Add a Page
            </a>
        </div>
        <dashboard-tile
            v-for="(page, index) in passedPages"
            :key="page.id"
            v-bind:index="index"
            :icons="icons"
            :resource="page"
            :block="firstBlock(page)"
            resource-type="page"
            @down="movePageDown"
            @up="movePageUp"
        ></dashboard-tile>
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';
    import Flash from './../mixins/Flash.js';

    export default {
        mixins: [ Ajax, Flash ],

        data: function () {
            return {
                /**
                 * Projects received from prop.
                 *
                 * @type {Array}
                 */
                passedPages: this.pages
            }
        },

        props: {
            /**
             * Update project order action.
             */
            action: {
                type: String
            },

            /**
             * Icons object.
             */
            icons: {
                type: Object
            },

            /**
             * Array of all pages in portfolio.
             */
            pages: {
                type: Array
            }
        },

        computed : {
            count () {
                return this.passedPages.length;
            }
        },

        methods: {
            /**
             * Move a page down.
             *
             * @param  {Number} index Index of page to move.
             */
            movePageDown (index) {
                let page = this.passedPages.splice(index, 1);

                this.passedPages.splice(index + 1, 0, page[0]);

                this.updatePageOrder();
            },

            /**
             * Move a page up.
             *
             * @param  {Number} index Index of page to move.
             */
            movePageUp (index) {
                if (index > 0) {
                    let page = this.passedPages.splice(index, 1);

                    this.passedPages.splice(index - 1, 0, page[0]);

                    this.updatePageOrder();
                }
            },

            /**
             * Return block for page.
             *
             * @param  {Object} page Project object.
             *
             * @return {String}
             */
            firstBlock (page) {
                if (page.blocks.length > 0) {
                    return page.blocks[0].formatted_text;
                }
            },

            /**
             * Update the order of pages in the portfolio.
             */
            updatePageOrder () {
                this.ajax.patch(this.action, {
                    pages: this.passedPages
                })
                .then(function (response) {
                    //
                })
                .catch(function (error) {
                    this.flash({
                        title: 'Error',
                        message: 'Could not update page order',
                        type: 'error'
                    });
                });
            }
        }
    };
</script>
