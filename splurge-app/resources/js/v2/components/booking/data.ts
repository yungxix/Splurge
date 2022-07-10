import { FormBuilder, AbstractControl, Validators, ValidatorFn, FormControl } from 'react-reactive-form';
import { Address, Booking, BookingViewModel, Customer, CustomerModel } from './types';
import {isAfter, parseISO} from 'date-fns';
import isString from 'lodash/isString';

const futureDate: ValidatorFn = (control) => {
    if (!control.value) {
        return null;
    }
    let v = control.value;
    if (isString(v)) {
        v = parseISO(v);
    }
    if (isAfter(v, new Date())) {
        return null;
    }

    return {
        futureDate: true
    };
};

type BookingAttribute = keyof BookingViewModel;

type AddressAttribute = keyof Pick<Address, "name" | "line1" | "line2" | "state" | "zip">;

type CustomerAttribute = keyof CustomerModel;

const getFullName = (customer: Customer): any => {
    const parts = [customer.first_name, customer.last_name].filter(a => Boolean(a));
    if (parts.length > 0) {
        return parts.join(" ");
    }
    return null;
};

const buildAddressControls = (address: Address): Record<AddressAttribute, AbstractControl> => ({
    name: FormBuilder.control(address.name || "", [Validators.maxLength(200)]),
    line1: FormBuilder.control(address.line1, [Validators.required, Validators.maxLength(255)]),
    line2: FormBuilder.control(address.line2 || "", [Validators.maxLength(255)]),
    state: FormBuilder.control(address.state, [Validators.required]),
    zip: FormBuilder.control(address.zip || "", [Validators.maxLength(10)])
});

const buildCustomerControls = (customer: Customer): Record<CustomerAttribute, AbstractControl> => ({
    fullName: FormBuilder.control(getFullName(customer), [Validators.required, Validators.max(30 * 2), Validators.pattern(/^\s*\S+\s+\S+\s*$/)]),
    email: FormBuilder.control(customer.email, [Validators.required, Validators.email]),
    phone: FormBuilder.control(customer.phone, [Validators.required, Validators.pattern(/^\+?\s*(\d+)([\s\-](\d+))*$/)])
});


const createBookingFormControlsImpl = (booking: Booking): Record<BookingAttribute, AbstractControl> => {
    const emv: any = null;
    return {
        address: FormBuilder.group(buildAddressControls(booking.address)),
        customer: FormBuilder.group(buildCustomerControls(booking.customer)),
        description: FormBuilder.control(booking.description, [Validators.required, Validators.maxLength(455)]),
        eventDate: FormBuilder.control(booking.eventDate || emv, [Validators.required, futureDate]),
        selected_tier: FormBuilder.control(booking.selected_tier, [Validators.required])
    
    };
};

export const createBookingFormControls = (booking: Booking) => FormBuilder.group(createBookingFormControlsImpl(booking));