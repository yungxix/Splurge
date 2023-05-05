import React, {} from "react";
import { render} from 'react-dom';
import { GuestOptionsContext, GuestContext } from "./contextx";
import { GuestView} from './guest';
import { CustomerEventGuest } from "./types";
import GuestAttachments from './guest-attanchment';
import { parseWithOptions } from "date-fns/fp";

const Splurge = (window as any).Splurge || {};

const CustomerEvents = {
    mountGuestView(host: HTMLElement, options: {
        data: CustomerEventGuest;
        editable?: boolean;
        baseUrl: string;
    }) {
        const root = <GuestOptionsContext.Provider value={{baseUrl: options.baseUrl}}>
                <GuestView value={options.data} editable={options.editable === true}>
    
                </GuestView>
            </GuestOptionsContext.Provider>
    
        render(root, host);
    },

    mountStandaloneAttachmentWidget(host: HTMLElement, options: {
        value: Record<string, any> | null;
        attribute: string;
        name: string;
    }) {
        const root = <GuestOptionsContext.Provider value={{baseUrl: '#'}}>
            <GuestContext.Provider value={{id: 0}}>
                <GuestAttachments value={options.value}
                 attribute={options.attribute}
                  name={options.name} standalone={true} editable={true} />
            </GuestContext.Provider>
        </GuestOptionsContext.Provider>

        render(root, host);
    }
}

Splurge.CustomerEvents = CustomerEvents;

(window as any).Splurge = Splurge;