import { Address, Customer } from "../../../booking/types";

export interface Payment {
    id: number;
    created_at: string;
    updated_at: string;
    amount: number;
    statement: string;
    code: string;
}

export interface Booking {
    id: number;
    description: string;
    event_date: string;
    customer: Customer;
    location: Address;
    payments?: Array<Payment>;
    code: string;
    current_charge?: number;
    created_at: string;

}