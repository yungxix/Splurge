import React from "react";
import { render } from 'react-dom';
import { MenuItem } from "./types";
import MenuItemsApp from "./app";


const Splurge = (window as any).Splurge || {};

Splurge.EventMenuItems = {
    render(host: HTMLElement, options: {items: MenuItem[]}) {
        const root = <MenuItemsApp value={options.items} />
        render(root, host);
    }
}