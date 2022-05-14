import React, {useState, useMemo, useCallback} from "react";
import  {ServiceTier} from '../booking-tier-selector';
import { Booking, Contact } from "./types";
import {BookingContext, BookingEnvContext, StepContext} from './context';
import StepsRenderer from "./steps";
export interface BookingProps {
    serviceId: number;
    tiers: Array<ServiceTier>;
    states: Array<{name: string}>;
    contact: Contact;
    catalogUrl: string;
    paymentUrl: string;
    postUrl: string;
}





export default function BookingFormRenderer(props: BookingProps) {
    const [step, setStep] = useState(0);
    const [booking, setBooking] = useState<Partial<Booking>>({service_id: props.serviceId, selected_tier: props.tiers[0].id});

    const bookingEnv = useMemo(() => ({paymentUrl: props.paymentUrl, states: props.states,
         catalogUrl: props.catalogUrl, contact: props.contact,
          tiers: props.tiers, postUrl: props.postUrl}), []);


    const updateBooking = useCallback((data: Partial<Booking>) => setBooking({...booking, ...data}), []);

    return <BookingEnvContext.Provider value={bookingEnv}>
            <StepContext.Provider value={{step, onChange: setStep}}>
                <BookingContext.Provider value={{data: booking, onChange: updateBooking}}>
                    <StepsRenderer />
                </BookingContext.Provider>
            </StepContext.Provider>
        </BookingEnvContext.Provider>


}