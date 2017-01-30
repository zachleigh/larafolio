export default {
    data: function () {
        return {
            /**
             * Heights of form fields, keyed by id.
             *
             * @type {Object}
             */
            heights: {},
        }
    },

    methods: {
        /**
         * Set heights for form and display elements.
         */
        setHeights () {
            let sections = document.querySelectorAll('.section');

            for (var i = 0; i < sections.length; i++) {
                this.setSectionHeight(sections[i]);
            }
        },

        /**
         * Set height for single form/display element.
         *
         * @param {Element} section Form element to set.
         */
        setSectionHeight (section) {
            var id = section.id;

            let display = this.getDisplayById(id);

            if (id === 'name' || id === 'projectType') {
                this.heights[id] = section.offsetTop + 24;
            } else {
                this.heights[id] = section.offsetTop + 46;
            }

            this.setPaddingBottom(section, display);
        },

        /**
         * Get a display element from the form section id.
         *
         * @param  {String} id Form section ID.
         *
         * @return {Element}
         */
        getDisplayById (id) {
            let displayId = 'display'+this.capitalizeFirstLetter(id);

            return document.getElementById(displayId);
        },

        /**
         * Set the bottom padding on a form element if the corresponding
         * display element is longer.
         *
         * @param {Element} section Form section element.
         * @param {Element} display Display element.
         */
        setPaddingBottom (section, display) {
            if (section && display) {
                let sectionHeight = section.offsetHeight;

                let displayHeight = display.offsetHeight;

                let difference = displayHeight - sectionHeight + 30;

                if (displayHeight > sectionHeight && difference > 15) {
                    section.style.paddingBottom = difference + 'px';
                }
            }
        },

        /**
         * Get form element registered from heights object.
         *
         * @param  {String} key Key for heights array.
         *
         * @return {String}    Height in px.
         */
        getHeight (key) {
            this.setHeights();
            
            return this.heights[key];
        }
    }
};