var Remarkable = require('remarkable');
var md = new Remarkable();

export default {
    data: function () {
        return {
            md: md
        }
    },
}
