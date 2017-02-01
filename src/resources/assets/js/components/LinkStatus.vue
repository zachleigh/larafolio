<template>
    <div class="section__indented project__display-item link__status-wrapper">
        Status: 
        <div
            class="link__status"
            v-bind:class="colorClass"
        ></div> 
        {{ status }}
    </div>
</template>

<script>
    import Ajax from './../mixins/Ajax.js';

    export default {
        mixins: [ Ajax ],

        data () {
            return {
                /**
                 * The link status.
                 */
                status: null,

                /**
                 * The first number of the http status code.
                 */
                statusFirst: 0,

                exceptions: [
                    'http://httpstat.us/200',
                    'http://httpstat.us/302',
                    'http://httpstat.us/404',
                    'http://httpstat.us/500',
                ]
            }
        },

        props: {
            /**
             * The url to validate.
             */
            url: {
                type: String
            },

            /**
             * If true, check the validity of the url.
             */
            check: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            shouldCheck () {
                let isException = this.exceptions.indexOf(this.url);

                if (isException !== -1 || this.check) {
                    return true;
                }

                return false;
            },

            colorClass () {
                if (this.status === null) {
                    return 'greyDot';
                } else if (this.statusFirst == 2) {
                    return 'greenDot';
                } else if (this.statusFirst == 3) {
                    return 'limeDot';
                } else {
                    return 'redDot';
                }
            }
        },

        created () {
            if (this.shouldCheck) {
                this.checkStatus();
            }
        },

        methods: {
            /**
             * Fire ajax request to server to check url status.
             */
            checkStatus () {
                this.ajax.post('/manager/links/check', {
                    url: this.url
                })
                .then(function (response) {
                    if (response.data.httpCode == 0) {
                        this.status = 'unknown';
                    } else {
                        this.status = response.data.httpCode;
                    }

                    this.statusFirst = response.data.httpCode.toString()[0];
                })
                .catch(function (error) {
                    
                });
            }
        }
    };
</script>
