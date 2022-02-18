require('./bootstrap');
import  {createApp} from 'vue';

import MediaItemSliders from './components/media-item-slides.vue';

import GalleryView from './components/gallery/collage.vue';

import NavBar from './components/navbar.vue';


window.Splurge = window.Splurge || {};
window.Splurge.slides = {
    render: function (target, options) {
        const app = createApp(MediaItemSliders, options);
        app.mount(target);
    }
};

window.Splurge.gallery = {
    render: function (target, gallery, options = {}) {
        const app = createApp(GalleryView, {
            gallery,
            ...options
        });

        app.mount(target);
    }

};


window.Splurge.navbar = {
    render: function (target, options) {
        const app = createApp(NavBar, options);
        app.mount(target);
    }

};