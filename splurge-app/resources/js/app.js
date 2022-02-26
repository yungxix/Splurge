require('./bootstrap');

import  {createApp} from 'vue';

import MediaItemSliders from './components/media-item-slides.vue';

import GalleryView from './components/gallery/collage.vue';

import UploadForm from './components/admin/medium-uploader.vue';

import UploadTrigger from './components/admin/upload-trigger.vue';

import NavBar from './components/navbar.vue';

import FlashComponent from './components/flash-messages.vue';

import Alpine from 'alpinejs';


window.Splurge = window.Splurge || {};


window.Splurge.flash = {
    render(target, options) {
        const app = createApp(FlashComponent, options);
        app.mount(target);
    }
}

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

window.Splurge.admin = {
    uploader : {
        renderForm: function (target, options) {
            const app = createApp(UploadForm, options);
            app.mount(target);
        },
        renderTrigger: function (target, options) {
            const app = createApp(UploadTrigger, options);
            app.mount(target);
        }
    }
}



window.Alpine = Alpine;

Alpine.start();
