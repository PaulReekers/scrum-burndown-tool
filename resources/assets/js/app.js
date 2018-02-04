require('./bootstrap');

import Burndown from './components/Burndown';

const selector = '.vue'; // TODO: temp solution, to prevent vue to intervene with blade forms

if (document.querySelector(selector)) {
    const burndown = new Vue({
        el: selector,

        data: {
            images: [
                '/images/meme/am-i-the-only-one-here-yelling-go-coosto.jpg',
                '/images/meme/burn-all-the-points.jpg',
                '/images/meme/computer-kit.jpg',
                '/images/meme/if-i-wanna-yell-go-coosto-always.jpg',
                '/images/meme/not-sure-if-go-coosto-or-coosto-are-go.jpg',
                '/images/meme/one-does-not-simply-say-go-coosto.jpg',
                '/images/meme/reading-meme-cant-yell-go-coosto.jpg',
                '/images/meme/so-youre-telling-me-go-coosto.jpg',
                '/images/meme/y-u-no-go-coosto.jpg',
                '/images/meme/yell-go-coosto-no.jpg',
            ],
            selectedImage: ''
        },
        created() {
            const idx = Math.floor(Math.random() * this.images.length);
            this.selectedImage = this.images[idx]
        },

        methods: {
            play: function(event) {
                this.$refs.goCoosto.play();
            }
        },

        components: { Burndown }
    });
}
