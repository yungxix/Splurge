import React, {useContext, useRef} from "react";
import { BookingContext, BookingEnvContext, StepContext } from "./context";
import {createBookingFormControls} from "./data";
import {addMonths} from 'date-fns';
import { Booking, BookingViewModel } from "./types";
import { FieldGroup, AbstractControl, FieldControl } from 'react-reactive-form';
import ServiceTierSelector from '../booking-tier-selector';
import * as RXControls from "../forms/reactive/controls";
import PersonalInfoControls from "./person-info2";
import AddressBlockInputs from "./address-block2";
import InDevelopment from "../dev";


const createDefaultBooking = (env: Partial<Booking>): Booking => {
    return {
        customer: env.customer || {first_name: "", last_name: "", email: "", phone: ""},
        address: env.address || {line1: "", state: "", zip: ""},
        description: env.description || "",
        eventDate: env.eventDate || addMonths(new Date(), 2),
        selected_tier: env.selected_tier || 0,
        service_id: env.service_id || 0,
        price: env.price
    };
};

const ServiceTiersControl = (control: AbstractControl) => (<div>
    <ServiceTierSelector inputName="tier" tiers={control.meta.tiers} value={control.value} onChange={(e) => {
        control.setValue(e)
    }} />
    {control.touched && control.hasError("required") && (<p className="text-red-500">
        You have not selected a package
    </p>)}
</div>);

const DESCRIPTION_META = {
    label: "Tell us briefly about the event",
    placeholder: "Short description of event",
    hint: "Don't worry, you can tell us more later",
    errors: {
        required: "Description is required",
        maxLength: ":actualLength is too long. Must not exceed :requiredLength characters"
    },
    className: "mb-4",
    controlClassName: "control block w-full"
};

const DATE_META = {
    label: "When is the event?",
    placeholder: "Event date",
    errors: {
        required: "You have not selected a date for the event",
        futureDate: "Must be a future date"
    },
    className: "mb-4",
    controlClassName: "control block w-full"
};


export default function Step1() {
    const booking = useContext(BookingContext);
    const formGroup = useRef(createBookingFormControls(createDefaultBooking(booking.data)));
    const env = useContext(BookingEnvContext);
    const stepCtx = useContext(StepContext);

    const commitForm = (data: BookingViewModel) => {
        if (formGroup.current.invalid) {
            return;
        }
        const [first_name, last_name] = data.customer.fullName.trim().split(/\s+/);
        booking.onChange({
            customer: {first_name, last_name, email: data.customer.email, phone: data.customer.phone},
            address: data.address,
            description: data.description,
            eventDate: data.eventDate,
            selected_tier: data.selected_tier
        });
        stepCtx.onChange(stepCtx.step + 1);
    };
    
    const generateData = async () => {
      const api = await import('@faker-js/faker');

      formGroup.current.patchValue({
        customer: {
            fullName: [api.faker.name.firstName(), api.faker.name.lastName()].join(' '),
            email: api.faker.internet.email(),
            phone: api.faker.phone.number('+234 #0# #######')
        },
        description: api.faker.lorem.paragraph().slice(0, 455),
        eventDate: api.faker.date.future(),
        address: {
            name: api.faker.lorem.sentence(5),
            line1: [api.faker.address.streetAddress()].join(' '),
            line2: api.faker.address.secondaryAddress(),
            zip: api.faker.address.zipCode(),
            state: 'Lagos'
        }
      });


    };
    return <FieldGroup control={formGroup.current} render={(ctrl) => (<form onSubmit={(e) => {
        e.preventDefault();
        commitForm(ctrl.value);
    }} method="POST" noValidate>
        <FieldControl name="selected_tier" meta={{tiers: env.tiers}} render={ServiceTiersControl}>


        </FieldControl>
        <div className="bg-white py-16 px-4 md:px-16">
                <div className="md:flex">
                    <div className="md:w-2/3">
                        <FieldControl name="description" meta={DESCRIPTION_META} render={RXControls.TextAreaInput}>

                        </FieldControl>
                        <FieldControl name="eventDate" meta={DATE_META} render={RXControls.DateInput}></FieldControl>
                        <PersonalInfoControls control={ctrl.get("customer")} className="mt-4" />
                        <AddressBlockInputs control={ctrl.get("address")} className="mt-4" />
                        <div className="mt-4 flex flex-row items-center justify-end gap-10">
                            <InDevelopment>
                                <button className="btn" onClick={generateData} type="button">
                                    Generate
                                </button>
                            </InDevelopment>
                            <button type="submit"
                            className="btn"
                            disabled={ctrl.invalid}>
                                Continue 
                            </button>
                            <a className="link" href="/">
                                CANCEL
                            </a>
                        </div>
                    </div>
                </div>
                
        </div>


    </form>)}>

    </FieldGroup>
}                                                                                                                                                                                                        
