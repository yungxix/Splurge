import React, { useContext } from "react";
import { AbstractControl, FormGroup, FieldGroup, FieldControl } from "react-reactive-form";
import { ErrorMessageFactory } from "../forms/reactive/types";

import * as FormControls from '../forms/reactive/controls'
import { BookingEnvContext } from "./context";

const errorMap: Record<string, string | ErrorMessageFactory> = {
    required: ':label is required',
    maxLength: ':actualLength is too long. Must not exceed :requiredLength characters'
};

export default function AddressBlockInputs(props: {control: AbstractControl, className?: string}) {
    const env = useContext(BookingEnvContext);
    const fg = props.control as FormGroup;
    return <div className={props.className || ''}>
         <p className="text-gray-700 mb-4">
            Tell us where it is located
        </p>
        <FieldGroup control={fg} render={(ctrl) => (<>
            <FieldControl 
                name="name"
                 meta={{label: 'Location name', placeholder: 'Enter name of location', renderLabel: false, inputAttributes: {maxLength: 200}, className: 'mb-4', errors: errorMap, controlClassName: "control block w-full"}} 
                 render={FormControls.TextInput}>

            </FieldControl>
            <FieldControl 
                name="line1"
                 meta={{label: 'Address Line #1', placeholder: 'Enter line #1 address', renderLabel: false, inputAttributes: {maxLength: 200}, className: 'mb-4', errors: errorMap, controlClassName: "control block w-full"}} 
                 render={FormControls.TextInput}>

            </FieldControl>

            <FieldControl 
                name="line2"
                 meta={{label: 'Address Line #2', placeholder: 'Enter line #2 address', renderLabel: false, inputAttributes: {maxLength: 200}, className: 'mb-4', errors: errorMap, controlClassName: "control block w-full"}} 
                 render={FormControls.TextInput}>

            </FieldControl>

            <div className="w-full md:flex flex-row gap-x-4">
                <div className="md:w-2/3">
                    <FieldControl 
                        name="state"
                        meta={{label: 'State', className: 'mb-4', options: env.states, errors: errorMap, controlClassName: "control block w-full"}} 
                        render={FormControls.SelectInput}>

                    </FieldControl>
                </div>
                <div className="md:w-1/3">
                    <FieldControl 
                        name="zip"
                        meta={{label: 'Postal/Zip code', className: 'mb-4', errors: errorMap, controlClassName: "control block w-45"}} 
                        render={FormControls.TextInput}>

                    </FieldControl>
                </div>
            </div>
        
        </>)}> 
        </FieldGroup>


    </div>
}