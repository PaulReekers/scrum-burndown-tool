require('./bootstrap');

import Burndown from './components/Burndown';

const selector = '.vue'; // TODO: temp solution, to prevent vue to intervene with blade forms

if (document.querySelector(selector)) {
    const burndown = new Vue({
        el: selector,

        methods: {
            play: function(event) {
                this.$refs.goCoosto.play();
            }
        },

        components: { Burndown }
    });
}
