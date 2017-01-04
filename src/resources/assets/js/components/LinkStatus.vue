<template>
    <div class="project__display-item link__status-wrapper">
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
                status: null,

                statusFirst: 0
            }
        },

        props: {
            url: {
                type: String
            }
        },

        computed: {
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
            this.checkStatus();
        },

        methods: {
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
