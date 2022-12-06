import { createContext } from "react";


export interface GuestOptionsContextData {
    baseUrl: string;
}

export const GuestOptionsContext = createContext<GuestOptionsContextData>({ baseUrl: '/admin/customer_events/' });

export interface GuestContextData {
    id: number;
}

export const GuestContext = createContext<GuestContextData>({id: 0});