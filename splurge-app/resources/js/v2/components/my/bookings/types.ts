import { ServiceTier } from "../../booking-tier-selector";
import { Address, Booking, Customer } from "../../booking/types";

export interface Service {
    name: string;
    description: string;
    image_url: string;
}



export interface ExtendedServiceTier extends ServiceTier {
    service: Service;
}

export interface ExtendedBooking {
    customer: Customer;
    location: Address;
    id: number;
    description: string;
    created_at: string;
    event_date: string;
    service_tier: ExtendedServiceTier;
    code: string;
    current_charge?: number;
}