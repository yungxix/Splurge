import React, { useContext } from "react";
import { AbstractControl, FormGroup, FieldGroup, FieldControl } from "react-reactive-form";
import { ErrorMessageFactory } from "../forms/reactive/types";

import * as FormControls from '../forms/reactive/controls'
import { BookingEnvContext } from "./context";

const errorMap: Record<string, string | ErrorMessageFactory> = {
    required: ':label is required',
    maxLength: 'Too long. Must not exceed :requiredLength characters',
    pattern: "Does not match expected pattern",
    email: "Invalid email address"
};

export default function PersonalInfoControls(props: {control: AbstractControl, className?: string}) {
    const env = useContext(BookingEnvContext);
    const fg = props.control as FormGroup;
    return <div className={props.className || ''}>
        <p className='text-gray-700 mb-4'>
            Let us know who you are
        </p>
        <FieldGroup control={fg} render={(ctrl) => (<>
            <FieldControl 
                name="fullName"
                 meta={{label: 'Full name', placeholder: "Enter first and last name", className: 'mb-4', errors: errorMap, controlClassName: "control block w-full"}} 
                 render={FormControls.TextInput}>

            </FieldControl>

            <div className="w-full md:flex flex-row">
                <div className="md:w-1/2">
                    <FieldControl 
                        name="email"
                        meta={{label: 'Email address', placeholder: "Enter email address", type: "email", className: 'mb-4', options: env.states, errors: errorMap, controlClassName: "control block w-60"}} 
                        render={FormControls.TextInput}>

                    </FieldControl>
                </div>
                <div className="md:w-1/2">
                    <FieldControl 
                        name="phone"
                        meta={{label: 'Phone number', type: "tel", placeholder: 'Enter phone number', className: 'mb-4', errors: errorMap, controlClassName: "control block w-45"}} 
                        render={FormControls.TextInput}>

                    </FieldControl>
                </div>
            </div>
        
        </>)}> 
        </FieldGroup>


    </div>
}