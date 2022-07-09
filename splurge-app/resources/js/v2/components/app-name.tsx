import {createContext} from 'react';

export interface AppNameContextProps {
    name: string;
}



export const AppNameContext = createContext<AppNameContextProps>({name: document.querySelector('meta[name="app-name"]')?.getAttribute('content') || 'Serene Splurge'});

