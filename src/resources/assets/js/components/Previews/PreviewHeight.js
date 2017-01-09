export default {
    data: function () {
        return {
            padding: 0,
        }
    },

    props: {
        height: {
            type: Number
        },
    },

    computed: {
        top () {            
            return (this.height + this.padding) + 'px';
        }
    }
};