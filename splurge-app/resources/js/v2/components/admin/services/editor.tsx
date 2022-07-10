import React, {FC, useState, useEffect} from "react";

import ControlsContainer from '../../forms/horizontal-container';
import SplurgeModal from "../../modal";
import OptionsEditor , {} from './editor/index';
import { PricingType, ServiceItem } from "./types";
import axios from '../../../../axios-proxy';
import omit from 'lodash/omit';
import * as yup from 'yup';
import get from 'lodash/get';
import has from 'lodash/has';
import castArray from 'lodash/castArray';
import classNames from "classnames";
import { PricingOptionsEditorProps } from "./editor/types";
import { getErrorMessage } from "../../../utils";
import {ThumbUpIcon, ExclamationIcon} from '@heroicons/react/solid';



export interface EditorProps {
    baseURL: string;
    serviceItem: ServiceItem;
    onSave: (item: ServiceItem) => void;
    onCancel: () => void; 
    show: boolean;
}

type ServiceItemFormData = Omit<ServiceItem, "id" | "type" | "created_at">;

const UniPricingOptionEditor: FC<PricingOptionsEditorProps & {type: PricingType}> = (props) => {
    switch (props.type) {
        case 'incremental':
            return (<OptionsEditor.IncrementalOptionsEditor {...props}></OptionsEditor.IncrementalOptionsEditor>);
        case 'percentage':
            return (<OptionsEditor.PercentageOptionsEditor {...props}></OptionsEditor.PercentageOptionsEditor>);
        default:
            return (<OptionsEditor.FixedOptionsEditor {...props}></OptionsEditor.FixedOptionsEditor>)
    }

};


const PRICING_TYPES: Array<{text: string; value: PricingType}> = [
    {
        value: 'fixed',
        text: 'Fixed amount'
    }, {
        value: 'incremental',
        text: 'Incremental amount'
    }, {
        value: 'percentage',
        text: 'Percentage amount'
    }
];

const createValidationSchemaAttributes = (): Record<keyof ServiceItemFormData, yup.BaseSchema> => ({
    name: yup.string().label('Name').required(),
    category: yup.string().label('Category').max(25).nullable(),
    description: yup.string().label('Description').nullable(),
    price: yup.number().label('Price').nullable(),
    required: yup.boolean().label('Is required'),
    options: yup.object().label('Options').test({
        name: 'validOptions',
        message: 'Invalid options',
        test: (value, ctx) => {
            if ((/increment/).test(get(ctx.parent, 'pricing_type'))) {
                const m1 = get(value, 'minimum', 0);
                const m2 = get(value, 'maximum', 0);
                if (m1 === m2) {
                    return new yup.ValidationError('You selected incremental type but min/max is not valid');
                }
            }
            if ((/percent/).test(get(ctx.parent, 'pricing_type'))) {
                const m1 = get(value, 'rate', 0);
                if (m1 <= 0) {
                    return new yup.ValidationError('You selected percentage type but rate is not valid');
                }
            }
            return true;
        }
    }),
    pricing_type: yup.string().required().label('Pricing type').oneOf(PRICING_TYPES.map(x => x.value)),
    sort_number: yup.number().label('Position').nullable()
});


enum EditorStatus {
    idle = 0,
    saving = 1,
    saved = 2,
    error = -1
}

const VALIDATION_SCHEMA = yup.object().shape(createValidationSchemaAttributes());

const EMPTY_MESSAGES: string[]  = [];

const saveItem = async (item: ServiceItem, options: {baseURL: string}): Promise<ServiceItem> => {
    if (item.id) {
        const r = await axios.patch<{data: ServiceItem}>(`${options.baseURL}/${item.id}`, omit(item, 'id', 'created_at', 'type'));
        return r.data.data;
    }

    const r1 = await axios.post<{data: ServiceItem}>(options.baseURL, omit(item, 'id', 'created_at', 'type'));
    return r1.data.data;
}

const ErrorMessageRenderer: FC<{messages: string[]}> = (props) => (<>
    {
        props.messages.map((str, i) => (<p className="text-red-700" key={`error_message_${i}`}>{str}</p>))
    }
</>);

const REQUIRE_OPTIONS_REGEXP = /increment|percent/;

const PERCENTAGE_TYPE = /percent/i;



const NUMBER_PATTERN = /^\d+(\.?\d+)?$/;

const debugValidatate = (d: ServiceItemFormData) => {
    try {
        VALIDATION_SCHEMA.validate(d);
    }catch (ex) {
        console.error(ex);
    }
}


const EditorForm: FC<{
    value: ServiceItemFormData,
    onSubmit: (data: ServiceItemFormData) => void;
    onCancel: () => void;
}> = (props) => {
    const [error, setError] = useState({});
    const [data, setData] = useState(props.value);
    const [valid, setValid] = useState(VALIDATION_SCHEMA.isValidSync(props.value));

    useEffect(() => {
        debugValidatate(props.value);
    }, [props.value]);


    const [focusedFields, setFocusedFields] = useState<Array<keyof ServiceItemFormData>>([]);


    const hasError = (key: keyof ServiceItemFormData) => {
        if (!focusedFields.includes(key)) {
            return false;
        }
        return has(error, key);
    };

    const getErrorMessages = (key: keyof ServiceItemFormData) => {
        if (!focusedFields.includes(key)) {
            return EMPTY_MESSAGES;
        }
        if (!has(error, key)) {
            return EMPTY_MESSAGES;
        }
        return castArray(get(error, key));
    };

    const focusedOn = (key: keyof ServiceItemFormData) => {
        if (!focusedFields.includes(key)) {
            setFocusedFields([...focusedFields, key]);
        }
    };

    const validate = (data: ServiceItemFormData, key: keyof ServiceItemFormData) => {
        setValid(VALIDATION_SCHEMA.isValidSync(data));
        try {
            VALIDATION_SCHEMA.validateSyncAt(key, data);
            const newError = omit(error, key);
            setError(newError);
        } catch (ex) {
            const ve = ex as yup.ValidationError;
            setError({...error, [key]: ve.message});
        }
    };

    return <form autoComplete="off" onSubmit={(e) => {
        e.preventDefault();
        if (VALIDATION_SCHEMA.isValidSync(data)) {
            props.onSubmit(data);
        }
    }} method="POST" noValidate>
    <ControlsContainer label="Name" className={classNames({
        error: hasError('name')
    })}>
        <input type="text" autoFocus autoComplete="off" autoCapitalize="on" className="control w-full" onFocus={(e) => focusedOn('name')} value={data.name} onChange={(e) => {
            const newData = {...data, name: e.target.value};
            setData(newData);
            validate(newData, 'name');
        }} />
        <ErrorMessageRenderer messages={getErrorMessages('name')}></ErrorMessageRenderer>
    </ControlsContainer>

    {
        data.pricing_type === 'fixed' && (<ControlsContainer label="Always required" labelTarget="price_required" className={classNames({
            error: hasError('required')
        })}>
            <input type="checkbox" id="price_required" onFocus={(e) => focusedOn('required')} checked={data.required} onChange={(e) => {
                const newData = {...data, required: e.target.checked};
                setData(newData);
                validate(newData, 'required');
            }} />
            <ErrorMessageRenderer messages={getErrorMessages('required')}></ErrorMessageRenderer>
        </ControlsContainer>)
    }

    

    {
        !PERCENTAGE_TYPE.test(data.pricing_type) && (<ControlsContainer label="Amount" className={classNames({
            error: hasError('price')
        })}>
            <input type="text" value={data.price} className="control" onFocus={(e) => focusedOn('price')} onChange={(e) => {
                const newData = {...data, 
                    price: NUMBER_PATTERN.test(e.target.value) ?
                     parseFloat(e.target.value) : 0};
                setData(newData);
                    validate(newData, 'price');
                
            }} />
            <ErrorMessageRenderer messages={getErrorMessages('price')}></ErrorMessageRenderer>
        </ControlsContainer>)
    }

    



    <ControlsContainer label="Type" className={classNames({
        error: hasError('pricing_type')
    })}>
        <select className="control" onFocus={(e) => focusedOn('pricing_type')} value={data.pricing_type} onChange={(e) => {
            const newData = {...data, pricing_type: e.target.value as PricingType};
            setData(newData);
            validate(newData, 'pricing_type');
        }}>
            {
                PRICING_TYPES.map((pt) => (<option key={pt.value} value={pt.value}>{pt.text}</option>))
            }
        </select>
        <ErrorMessageRenderer messages={getErrorMessages('required')}></ErrorMessageRenderer>
    </ControlsContainer>

    {
        REQUIRE_OPTIONS_REGEXP.test(data.pricing_type || '') && (<ControlsContainer className={classNames({
            error: hasError('options')
        })} label="Options">
            <UniPricingOptionEditor value={data.options} onFocus={(e) => focusedOn('options')} type={data.pricing_type || 'fixed'} onChange={(e) => {
                const newData = {...data, options: e};
                setData(newData);
                validate(newData, 'options')
            }}></UniPricingOptionEditor>
            <ErrorMessageRenderer messages={getErrorMessages('options')}></ErrorMessageRenderer>
        </ControlsContainer>)
    }
    <div className="flex flex-row justify-end items-center gap-x-4">
        <button disabled={!valid} type="submit" className="btn">
            Save
        </button>

        <button type="button" onClick={props.onCancel} className="btn">
            Cancel
        </button>

    </div>
 </form>

}

const Editor: FC<EditorProps> = (props) => {
    const [status, setStatus] = useState(EditorStatus.idle);
    const [error, setError] = useState('');   

    
    const handleClose = () => {
        setError('');
        setStatus(EditorStatus.idle);
        props.onCancel();
    }

    const saveImpl = async (item: ServiceItemFormData) => {
        setStatus(EditorStatus.saving);
        setError('');
        try {
            const result = await saveItem(item, {baseURL: props.baseURL});
            setStatus(EditorStatus.saved);
            props.onSave(result);
        } catch (ex: any) {
            setStatus(EditorStatus.error);
            setError(getErrorMessage(ex) || 'Failed to save pricing item');
        }

    };
    

    return <SplurgeModal show={props.show} onClose={handleClose}
     title={props.serviceItem.id ? 'Editing Pricing' : 'New Pricing'}>
         {
             status === EditorStatus.saving && (<div className="py-8">
                 <svg xmlns="http://www.w3.org/2000/svg" className="mx-auto animate-spin h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
             </div>)
         }
         {
             status === EditorStatus.saved && (<div className="flex flex-row items-center justify-start">
                 <ThumbUpIcon className="text-green-600 mr-8 w-8 h-8"></ThumbUpIcon>
                 <div className="text-green-800">
                     <p>
                            
                        <em>
                            Saved pricing model successfully
                        </em>
                     </p>
                     <p>

                        <a onClick={handleClose} className="link">
                        Close
                        </a>
                    </p>
                 </div>
             </div>)
         }
         {
             status === EditorStatus.error && (<div className="flex flex-row items-center justify-start">
                 <ExclamationIcon className="text-red-800 mr-8 w-8 h-8" />
                 <div className="text-red-800">
                    
                    <p>{error}</p>
                    <p>

                        <a onClick={(e) => {
                            setStatus(EditorStatus.idle);
                            setError('');
                        }} className="link">
                        Try again
                        </a>
                    </p>

                 </div>
             </div>)
         }
         {
             status === EditorStatus.idle && (<EditorForm 
             value={props.serviceItem} onCancel={handleClose} onSubmit={saveImpl}>

             </EditorForm>)
         }
    </SplurgeModal>

}


export default Editor;