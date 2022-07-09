import React, {FC, ReactElement, useMemo, createContext, useContext, useState} from "react";

import { LaravelFieldErrors } from "../forms/types";

import { FormBuilder, FieldGroup, ValidatorFn, FieldControl, Validators, AbstractControl } from 'react-reactive-form';

import { RefreshIcon } from '@heroicons/react/solid';

import { CalendarIcon, LocationMarkerIcon, ChatIcon, UserIcon } from '@heroicons/react/outline';

import classNames from "classnames";

import { parseISO, formatISO, format as formatDate, formatDistanceToNow, addDays, addMonths, isAfter, sub } from 'date-fns';

import * as CustomValidators from '../forms/reactive/custom-validators';

import Lines from "../lines";

import axios from '../../../axios-proxy';

import get from 'lodash/get';
import isArray from 'lodash/isArray';
import has from 'lodash/has';
import isNull from 'lodash/isNull';
import isFunction from 'lodash/isFunction';
import last from 'lodash/last';

import { humanize } from "../../utils";
import InDevelopment from "../dev";

const ErrorContext = createContext<{errors: LaravelFieldErrors}>({errors: {}});

const EMPTY_STRING_ARRAY: string[] = [];

type ErrorMessageFactory = (name: string, error?: any) => string;

type ErrorMessage = string | ErrorMessageFactory;

type LocalErrorMessageMap = Record<string, ErrorMessage>;

interface OptionModel {
    text: string;
    value: any;
}

interface ControlRenderProps {
    name: string; control: AbstractControl, serverErrors?:  LaravelFieldErrors;
}

type ControlRenderer = (props: ControlRenderProps) => ReactElement;

const translateNameToPath = (name: string) => {
    return name.replace(/\[/g, '.').replace(/\]/g, '');
};

enum Status {
    pending = 0,
    posting = 1,
    completed = 2,
    failed = -1
}


const GlobalErrorMessagesMap: Record<string, ErrorMessage> = {
    required: ':label is required',
    maxLength: ':actualLength is too long. Must not exceed :requiredLength characters',
    minLength: ':actualLength is too short. Minimum is :requiredLength characters',
    max: ':label is too large. maximum value of :max is expected',
    min: ':label is too small. minimum value of :min is expected',
    email: ':label is not a valid email address',
    eventDate: 'Invalid event date',
    pattern: 'Pattern for :label is wrong',
    phoneNumber: 'Invalid phone number'
};

const resolveErrorMessageImpl = (env: {label: string; errors: any; errorKey: string; message: ErrorMessage}): string => {
    if(isFunction(env.message)) {
        return (env.message as ErrorMessageFactory)(env.errorKey, env.errors[env.errorKey]);
    }

    return (env.message as string).replace(/\:(\w+)/g, (m, g) => {
        if ('label' === g) {
            return env.label;
        }
        return get(env.errors, g);
    });

};

const resolveErrorMessage = (env: {label: string; errors: any; errorKey: string; messages?: LocalErrorMessageMap}): string | null => {
    if (env.messages && has(env.messages, env.errorKey)) {
        return resolveErrorMessageImpl({
            errorKey: env.errorKey,
            errors: env.errors[env.errorKey],
            message: env.messages[env.errorKey],
            label: env.label
        });
    }
    if (has(GlobalErrorMessagesMap, env.errorKey)) {
        return resolveErrorMessageImpl({
            errorKey: env.errorKey,
            errors: env.errors[env.errorKey],
            message: get(GlobalErrorMessagesMap,env.errorKey),
            label: env.label
        });
    }
    return null;
};


const ErrorRenderer: FC<{name: string, control: AbstractControl}> = (props) => {
    const {errors} = useContext(ErrorContext);

    const path = translateNameToPath(props.name);

    const simpleName = last(path.split(/\./g)) as string;
    
    const serverError = get(errors, path, EMPTY_STRING_ARRAY);

    const hasError = props.control.dirty && props.control.invalid;

    if (serverError.length === 0 && !hasError) {
        return null;
    }


    const label = props.control.meta.label || humanize(simpleName);

    const errorKeys = props.control.errors ? Object.keys(props.control.errors) : EMPTY_STRING_ARRAY;

    const errorMessages = errorKeys.map(x => resolveErrorMessage({
        errorKey: x,
        errors: props.control.errors,
        label,
        messages: props.control.meta.validationMessages
    })).filter(x => !isNull(x));

    return <>
        {
            serverError.map((msg, i) => (<p key={`se_${i}`} className="text-red-500">{msg}</p>))
        }
        {
            errorMessages.map((msg, i) => (<p key={`le_${i}`} className="text-red-500">{msg}</p>))
        }
    </>
};

const renderHint = (control: AbstractControl) =>  {
    if (control.meta.hint) {
        return <p className="text-gray-400 italic">
            {control.meta.hint}
        </p>
    }
    return null;
};

const resolveSelectOptions = (control: AbstractControl): Array<OptionModel> => {
    if (isArray(control.meta.options)) {
        return control.meta.options;
    }
    return Object.keys(control.meta.options).map(a =>
         ({text: get(control.meta.options, a), value: a}));

};

const hasError = (props: ControlRenderProps) => {
    if (props.serverErrors && has(props.serverErrors, props.name)) {
        return true;
    }
    return props.control.dirty && props.control.invalid;
};


const resolvePlaceholder = (props: ControlRenderProps): string => {
    if (props.control.meta.placeholder) {
        return props.control.meta.placeholder;
    }
    if (props.control.meta.label) {
        return `Enter ${props.control.meta.label.toLowerCase()}`;
    }
    const path = last(translateNameToPath(props.name).split(/\./g)) as string;
    return `Enter ${humanize(path, false)}`;
};

const DefaultInput: ControlRenderer = (props) => (<div className={classNames({
    error: hasError(props)
})}>
    <input type={props.control.meta.type || 'text'} 
    className="control  w-full" placeholder={resolvePlaceholder(props)}
     {...props.control.handler()} name={props.name}  min={props.control.meta.min} />
    {renderHint(props.control)}
    <ErrorRenderer control={props.control} name={props.name} />
</div>);


const TextAreaInput: ControlRenderer = (props) => (<div className={classNames({
    error: hasError(props)
})}>
    <textarea className="control w-full"
         placeholder={resolvePlaceholder(props)}
    {...props.control.handler()}  name={props.name} />
    {renderHint(props.control)}
    <ErrorRenderer control={props.control} name={props.name} />
</div>);


const SelectInput: ControlRenderer = (props) => (<div className={classNames({
    error: hasError(props)
})}>
    <select className="control w-full" {...props.control.handler()} name={props.name} >
        {
            props.control.meta.placeholder && (<option value="">
                {props.control.meta.placeholder}
            </option>)
        }
        {
            resolveSelectOptions(props.control).map(opt => (<option key={opt.value} value={opt.value}>
                {opt.text}
            </option>))
        }
    </select>
    <ErrorRenderer control={props.control} name={props.name} />
</div>);


const CustomerRenderer = (serverErrors: LaravelFieldErrors) => {
    const nameAttrs: Record<string, string> = {first_name: 'First name', last_name: 'Last name'};
    const contactAttrs: Record<string, {type: string; label: string}> = {
        phone: {
            type: 'tel',
            label: 'Phone number'
        },
        email: {
            type: 'email',
            label: 'Email address'
        }
    };
    return (
        <FieldGroup name="customer" render={(ctrl) => (
                <div className="mb-6">
                    <p className='text-gray-700 mb-4'>
                        Let us know who you are
                    </p>

                    <div className="md:grid grid-cols-2 gap-2 mb-4">
                        {
                            Object.keys(nameAttrs).map((attr) => (<div key={`cust_${attr}_container`}>
                                   <label className="control-label block mb-2" htmlFor={`customer[${attr}]`}>
                                       {nameAttrs[attr]}
                                   </label> 
                                   <FieldControl meta={{label: nameAttrs[attr]}} 
                                    name={attr}
                                     render={(c) => DefaultInput({name: `customer[${attr}]`, control: c, serverErrors})}>

                                   </FieldControl>
                            </div>))
                        }
                    </div>

                    <div className="md:grid grid-cols-2 gap-2 mb-4">
                        {
                            Object.keys(contactAttrs).map((attr) => (<div key={`cust_${attr}_container`}>
                                   <label className="control-label block mb-2" htmlFor={`customer[${attr}]`}>
                                       {contactAttrs[attr].label}
                                   </label> 
                                   <FieldControl meta={{label: contactAttrs[attr].label, type: contactAttrs[attr].type}} 
                                    name={attr}
                                     render={(c) => DefaultInput({name: `customer[${attr}]`, control: c, serverErrors})}>

                                   </FieldControl>
                            </div>))
                        }
                    </div>

                </div>
        )}>
        </FieldGroup>


    )

};

const AddressRenderer = ( serverErrors: LaravelFieldErrors, states: Array<OptionModel>) => {
    const primaryInputs: Record<string, string> = {
        line1: 'Line #1',
        line2: 'Line #2'
    };
    const secondaryInputs: Record<string, {label: string; placeholder?: string, options?: Array<OptionModel>}> = {
        state: {
            label: 'State',
            options: states,
            placeholder: '(Select state)'
        }
    };

    const rendererFor = (field: string): ControlRenderer =>  'state' === field ?  SelectInput : DefaultInput;

    return (<FieldGroup name="address" render={(ctrl) => (<div>
        <p className="text-gray-700 mb-4">
            Tell us where it is located
        </p>

        {
            Object.keys(primaryInputs).map((field) => (<div className="mb-2" key={`address_${field}_container`}>
                <label className="mb-2 control-label block" htmlFor={`address[${field}]`}>
                    {primaryInputs[field]}
                </label>
                <FieldControl 
                meta={{label: primaryInputs[field]}}
                name={field} render={(fc) => rendererFor(field)({
                    name: `address[${field}]`,
                    control: fc,
                    serverErrors
                })}>

                </FieldControl>
            </div>))
        }

        <div className="md:flex flex-row justify-end items-start">
            {
                Object.keys(secondaryInputs).map((field) => (<div className="md:w-1/2" key={`address_${field}_container`}>
                    <label className="mb-2 block control-label" htmlFor={`address[${field}]`}>
                        {secondaryInputs[field].label}
                    </label>
                    <FieldControl 
                    meta={secondaryInputs[field]}
                    name={field} render={(fc) => rendererFor(field)({
                        name: `address[${field}]`,
                        control: fc,
                        serverErrors
                    })}>

                    </FieldControl>
                </div>))
            }

        </div>

    </div>)}>

    </FieldGroup>)



};


const validateEventDate: ValidatorFn = (ctrl) => {
    if (!ctrl.value) {
        return null;
    }

    const ctrlValue = parseISO(ctrl.value);
    
    const n = new Date();


    if (!isAfter(ctrlValue, n)) {
        return {
            eventDate: true
        };
    }

    return null;

};


export interface IntegratedBookingFormProps {
    errors?: LaravelFieldErrors;
    states: string[];
    renderButtons?: boolean;
    url: string;
    serviceTierId: number;
    companyName: string;
    galleryUrl: string;
}

interface BookingFormData {
    service_tier_id: number;
    description: string;
    event_date: string;
    customer: {
        first_name: string;
        last_name: string;
        email: string;
        phone: string;
    };
    address: {
        line1: string;
        line2: string;
        state: string;
        zip?: string;
    }
};


const CompleteView: FC<{data: BookingFormData;
     galleryUrl: string; 
     companyName: string; 
     code: string}> = (props) => {
    const date = parseISO(props.data.event_date);

    const iconClass = 'w-8 h-8 text-splarge-600';

    const headingClass = 'pr-8';

    return (<div className="leading-6">
        <h4 className="text-lg font-bold">
            Thank you!
        </h4>
        <p>
            Thank you for booking your event on {props.companyName}.
             Your booking reference number is <strong>#{props.code}</strong> and
              we will contact you by phone or email about next steps.
        </p>
        <table className="booking-details-table with-icons my-4">
            <caption>
                Details
            </caption>
            <tbody>
                <tr>
                    <th scope="row">
                        <CalendarIcon className={iconClass}></CalendarIcon>
                    </th>
                    <td>
                        <p>
                            {formatDate(date, 'EEEE MMMM d, yyyy')}
                        </p>
                        <p>
                            ({formatDistanceToNow(date, {addSuffix: true})})
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <ChatIcon className={iconClass} />
                    </th>
                    <td>
                        <Lines text={props.data.description} />
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <UserIcon className={iconClass} />
                    </th>
                    <td>
                        <p>
                            {props.data.customer.first_name} {props.data.customer.last_name}
                        </p>
                        <p>
                            Email: {props.data.customer.email}
                        </p>
                        <p>
                            Phone: {props.data.customer.phone}
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <LocationMarkerIcon className={iconClass} />
                    </th>
                    <td>
                        <address>
                            <em className="block">{props.data.address.line1}</em>
                            <em className="block">{props.data.address.line2}</em>
                            <em className="block">{props.data.address.state}</em>
                        </address>
                    </td>
                </tr>
            </tbody>
        </table>
        <p className="mt-4 text-center">
            <a className="link" href={props.galleryUrl}>
                Checkout our recent gallery
            </a>
        </p>
    </div>);
}

const buildFormControlsImpl = (options: {serviceTierId: number}): Record<keyof BookingFormData, AbstractControl> => (
    {
        service_tier_id: FormBuilder.control(options.serviceTierId, [Validators.required]),
        description: FormBuilder.control('', [Validators.required, Validators.maxLength(455)]),
        event_date: FormBuilder.control(formatISO(addMonths(new Date(), 2)), [Validators.required, validateEventDate]),
        customer: FormBuilder.group({
            first_name: FormBuilder.control('', [Validators.required, Validators.maxLength(255)]),
            last_name: FormBuilder.control('', [Validators.required, Validators.maxLength(255)]),
            email: FormBuilder.control('', [Validators.required, Validators.email]),
            phone: FormBuilder.control('', [Validators.required,
                CustomValidators.phoneNumber]),
        }),
        address: FormBuilder.group({
            line1: FormBuilder.control('', [Validators.required, Validators.maxLength(255)]),
            line2: FormBuilder.control('', [Validators.maxLength(255)]),
            state: FormBuilder.control('', [Validators.required, Validators.maxLength(25)]),
            zip: FormBuilder.control('', [Validators.maxLength(25)]),
        })

    }
);

const generateImpl = async (): Promise<Partial<BookingFormData>> => {
    const api = await import('@faker-js/faker');

    const result: Partial<BookingFormData> = {
        description: api.faker.lorem.paragraph().slice(0, 455),
        event_date: formatDate(api.faker.date.future(), 'yyyy-MM-dd'),
        customer: {
            first_name: api.faker.name.firstName(),
            last_name: api.faker.name.lastName(),
            email: api.faker.internet.email(),
            phone: api.faker.phone.number('+234 #0# ### ###')
        },
        address: {
            line1: api.faker.address.streetAddress(),
            line2: api.faker.address.secondaryAddress(),
            state: 'Lagos',
            zip: api.faker.address.zipCode()
        }

    };

    return result;

};

const buildFormControls = (options: {serviceTierId: number}) => FormBuilder.group(buildFormControlsImpl(options));

interface ControlMeta {
    label: string;
    hint?: string;
    name: string;
    placeholder?: string;
    type?: string; 
    min?: any;
}


const DESCRIPTION_META: ControlMeta  = {
    label: "Tell us briefly about the event",
    placeholder: "Short description of event",
    name: 'description',
    hint: "Don't worry, you can tell us more later",
};

const DATE_META: ControlMeta = {
    label: "When is the event?",
    placeholder: "Event date",
    name: 'event_date',
    type: 'date',
    min: formatDate(addDays(new Date(), 1), 'yyyy-MM-dd')
};



export default function IntegratedBookingForm(props: IntegratedBookingFormProps) {
    const controls = useMemo(() => buildFormControls({serviceTierId: props.serviceTierId}), []);

    const [status, setStatus] = useState(Status.pending);

    const [bookingCode, setBookingCode] = useState('');

    const renderedBy = (name: string) => 'description'  === name ? TextAreaInput : DefaultInput;

    const serverErrors = props.errors || {};

    const states = useMemo(() => props.states.map(state => ({text: state, value: state})), []);


    const renderCtrl = (meta: ControlMeta) => (<div className="mb-4">
        <label htmlFor={meta.name} className="block control-label">{meta.label}</label>
        <FieldControl 
        name={meta.name} meta={meta} render={(fc) => renderedBy(meta.name)({
            name: meta.name,
            control: fc,
            serverErrors
        }) }>

        </FieldControl>

    </div>);

    const generateData = async () => {       

        controls.patchValue(await generateImpl());

    };

    const commit = async () => {
        if (status === Status.completed) {
            return;
        }

        setStatus(Status.posting);


        try {
            const r = await axios.post<{data: {code: string}}>(props.url, controls.value);
            setBookingCode(r.data.data.code);
            setStatus(Status.completed);
        } catch (ex) {
            setStatus(Status.failed);
        }
        


    };

    const submitting = status === Status.posting;

    if (status === Status.completed) {
        return (<CompleteView data={controls.value} galleryUrl={props.galleryUrl} code={bookingCode} companyName={props.companyName} />)
    }

    if (status === Status.posting) {
        return (<div className="py-24"><div className="md:w-3/5 mx-auto flex flex-row justify-start items-center p-8">
            <RefreshIcon className="text-splarge-600 w-8 h-8 animate-spin flex-initial" />
            <p className="ml-4">
                Please wait...
            </p>
        </div></div>)
    }

    return (
        <ErrorContext.Provider value={{errors: serverErrors}}>
            <FieldGroup control={controls} render={(grp) => (<form onSubmit={(e) => {
                e.preventDefault();
                commit();
            }}>
                {
                    status === Status.failed && (<div className="mx-4 my-2 bg-red-400 text-red-800 p-4 rounded">
                        <p>
                            <em>
                                An error occurred while posting request for booking
                            </em>
                        </p>
                    </div>)
                }
                
                {renderCtrl(DESCRIPTION_META)}
                {renderCtrl(DATE_META)}
                {CustomerRenderer(serverErrors)} 
                {AddressRenderer(serverErrors, states)}
                {
                    props.renderButtons && (<div className="mt-4 flex flex-row items-center justify-end gap-4">
                        <InDevelopment>
                            <button onClick={generateData} disabled={submitting} type="button" className="btn">
                                Generate
                            </button>
                        </InDevelopment>
                        <button disabled={controls.invalid || submitting} type="submit" className="btn">
                            Book
                        </button>
                        {
                            !submitting && (<a className="btn" href="/">
                            Cancel
                        </a>)
                        }
                        
                    </div>)
                }
            </form>)}>
                
            </FieldGroup>
        </ErrorContext.Provider>
    );

}