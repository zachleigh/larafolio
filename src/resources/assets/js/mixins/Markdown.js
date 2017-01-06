var Remarkable = require('remarkable');
var md = new Remarkable({
    linkify: true,
    html: true
});

export default {
    data: function () {
        return {
            /**
             * Parse markdown.
             */
            md: md
        }
    },
}
