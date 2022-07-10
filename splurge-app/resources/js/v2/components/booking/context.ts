import noop from 'lodash/noop';
import { createContext } from 'react';
import { ServiceTier } from '../booking-tier-selector';

import { Booking, Contact } from "./types";

export interface BookingContextData {
    data: Partial<Booking>;
    onChange: (data: Partial<Booking>) => void;
}

export interface BookingEnvironment {
    states: Array<{name: string}>;
    tiers: ServiceTier[];
    contact: Contact;
    postUrl: string;
    catalogUrl: string;
    paymentUrl: string;
}

export interface StepContextData {
    step: number;
    onChange: (s: number) => void;
}


export const BookingContext = createContext<BookingContextData>({ data: {}, onChange: noop });

BookingContext.displayName = "BookingDataContext";

export const StepContext = createContext<StepContextData>({ step: 0, onChange: noop });

StepContext.displayName = "StepContext";


export const BookingEnvContext = createContext<BookingEnvironment>({ catalogUrl: "", paymentUrl: "",
 contact: {phone: "", email: ""},
  tiers: [], states: [], postUrl: "-1" });

BookingEnvContext.displayName = "BookingEnvContext";