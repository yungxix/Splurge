require('./bootstrap');

import  {createApp} from 'vue';

import MediaItemSliders from './components/media-item-slides.vue';

import GalleryView from './components/gallery/collage.vue';

import UploadForm from './components/admin/medium-uploader.vue';

import UploadTrigger from './components/admin/upload-trigger.vue';

import NavBar from './components/navbar.vue';

import FlashComponent from './components/flash-messages.vue';

import TagsTableView from './components/admin/tags/table-view.vue';

import TagsAssignment from './components/admin/tags/manager.vue';

import TagsSelector from './components/admin/tags/selector.vue';

import AltMediumSelector from './components/admin/alt-medium-selector.vue';


import TaggedLoader from './components/search/tagged-loader.vue';

import GroupedLoader from './components/search/tagged-group-loader.vue';

import Alpine from 'alpinejs';


window.Splurge = window.Splurge || {};


window.Splurge.flash = {
    render(target, options) {
        const app = createApp(FlashComponent, options);
        app.mount(target);
    }
}

window.Splurge.search = {
    renderTagged(target, options) {
        const app = createApp(TaggedLoader, {...options, noContent: () => {
            // app.unmount();
        }});
        app.mount(target);
    },
    renderGroupedTagged(target, options) {
        const app = createApp(GroupedLoader, options);
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
        },
        renderAltSelector: function (target, options) {
            const app = createApp(AltMediumSelector, options);
            app.mount(target);
        }
    },
    tags : {
        renderTableView(target, tags, options = {}) {
            const app = createApp(TagsTableView, {tags, ...options});
            app.mount(target);
        },
        renderAssignment(target, options) {
            const app = createApp(TagsAssignment, options);
            app.mount(target);
        },
        renderSelector(target, options) {
            const app = createApp(TagsSelector, options);
            app.mount(target);
        }
    }
}



window.Alpine = Alpine;

Alpine.start();
