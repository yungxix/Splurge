import { ValidationError} from 'yup';
export interface Address {
    id?: string;
    line1: string;
    line2?: string | null;
    state: string;
    name?: string;
    zip?: string | null;
    category?: string;
}




export interface Customer {
    id?: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
}

export interface CustomerModel {
    fullName: string;
    email: string;
    phone: string;
}

export interface Contact {
    email: string;
    phone: string;
    addresses?: Address[];
}

export interface Booking {
    selected_tier: number;
    service_id: number;
    customer: Customer;
    address: Address;
    description: string;
    price?: number;
    eventDate: Date | null;
}

export interface BookingViewModel {
    selected_tier: number;
    customer: CustomerModel;
    address: Address;
    description: string;
    eventDate: Date;
}


export type ValidationMessages = Array<ValidationError>;