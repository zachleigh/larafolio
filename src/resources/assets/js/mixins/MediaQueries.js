export default {
    computed: {
        wide () {
            var mq = window.matchMedia( "(max-width: 1400px)" );

            return mq.matches;
        },

        medium () {
            var mq = window.matchMedia( "(max-width: 1200px)" );

            return mq.matches;
        },

        small () {
            var mq = window.matchMedia( "(max-width: 1000px)" );

            return mq.matches;
        },

        tiny () {
            var mq = window.matchMedia( "(max-width: 800px)" );

            return mq.matches;
        },
    },
};