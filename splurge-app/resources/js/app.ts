import React from "react";

import ReactDOM from 'react-dom';


import MediaItemSliders, {SlidesProps} from './v2/components/media-item-slides';

import GalleryView from './v2/components/gallery/collage';

import UploadForm, {UploaderProps} from './v2/components/admin/medium-uploader';

import UploadTrigger, {UploadTriggerProps} from './v2/components/admin/upload-trigger';

import { Gallery} from './v2/components/gallery/types';

import NavBar, {NavBarProps} from './v2/components/navbar/NavBar';

import FlashComponent, {MessagesProps} from './v2/components/FlashMessages';

import TagsTableView from './v2/components/admin/tags/table-view';

import TagsAssignment, {ManagerProps} from './v2/components/admin/tags/manager';

import TagsSelector, {SelectorProps as TagSelectorProps} from './v2/components/admin/tags/selector';

import AltMediumSelector, {MediumSelectorProps} from './v2/components/admin/alt-medium-selector';


import TaggedLoader, {ItemProps} from './v2/components/search/tagged-loader-item';

import GroupedLoader, {LoaderProps} from './v2/components/search/tagged-loader';

const flash = {
    render(target: HTMLElement, options: MessagesProps) {
        ReactDOM.render(React.createElement(FlashComponent, options), target);
    }
};

const search = {
    renderTagged(target: HTMLElement, options: ItemProps) {
        ReactDOM.render(React.createElement(TaggedLoader, options), target);
    },
    renderGroupedTagged(target: HTMLElement, options: LoaderProps) {
        ReactDOM.render(React.createElement(GroupedLoader, options), target);
    }
};

const slides = {
    render: function (target: HTMLElement, options: SlidesProps) {
        ReactDOM.render(React.createElement(MediaItemSliders, options), target);
    }
};

const gallery = {
    render: function (target: HTMLElement, gallery: Gallery, options: any) {
        ReactDOM.render(React.createElement(GalleryView, {item: gallery, ...options}), target);
    }
};


const navbar = {
    render: function (target: HTMLElement, options: NavBarProps) {
        ReactDOM.render(React.createElement(NavBar, options), target);
    }

};

const admin = {
    uploader : {
        renderForm: function (target: HTMLElement, options: UploaderProps) {
            ReactDOM.render(React.createElement(UploadForm, options), target);
        },
        renderTrigger: function (target: HTMLElement, options: UploadTriggerProps) {
            ReactDOM.render(React.createElement(UploadTrigger, options), target);
        },
        renderAltSelector: function (target: HTMLElement, options: MediumSelectorProps) {
            ReactDOM.render(React.createElement(AltMediumSelector, options), target);
        }
    },
    tags : {
        renderTableView(target: HTMLElement,
             tags: Array<{id: number; name: string; category?: string}>,
              options: {baseUrl: string}) {
            ReactDOM.render(React.createElement(TagsTableView, {tags, ...options}), target);
        },
        renderAssignment(target: HTMLElement, options: ManagerProps) {
            ReactDOM.render(React.createElement(TagsAssignment, options), target);
        },
        renderSelector(target: HTMLElement, options: TagSelectorProps) {
            ReactDOM.render(React.createElement(TagsSelector, options), target);
        }
    }
}

const plugins = {
    flash,
    search,
    admin,
    navbar,
    slides,
    gallery
}
var Splurge = (<any>window).Splurge || {};

Object.assign(Splurge, plugins);

(<any>window).Splurge = Splurge;

