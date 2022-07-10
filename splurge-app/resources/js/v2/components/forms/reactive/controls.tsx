import React, {useState, useEffect, FC} from "react";
import { ControlMeta } from "./types";
import isFunction from "lodash/isFunction";
import isString from "lodash/isString";
import isObject from "lodash/isObject";
import get from "lodash/get";
import { AbstractControl } from "react-reactive-form";
import classNames from "classnames";
import {parseISO, formatISO, parse, format} from "date-fns";


const getOptionValue = (value: any) => {
    if (isString(value)) {
        return value;
    }
    return get(value, "value", get(value, "name", get(value, "id", JSON.stringify(value))));
};

const getOptionText = (value: any) => {
    if (isString(value)) {
        return value;
    }
    return get(value, "text", get(value, "name", get(value, "title", JSON.stringify(value))));
};

const getOptionId = (value: any, index: number) => {
    if (isString(value)) {
        return index;
    }
    return getOptionValue(value);
};

const getErrorMessage = (errorKey: string, env: ControlMeta, ctrl: AbstractControl): string => {
    if (!env.errors) {
        return `Error: ${errorKey}`;
    }
    const v = env.errors[errorKey];
    if (!v) {
        return `Error: ${errorKey}`;
    }

    const parameters = ctrl.errors[errorKey];

    let fullParams = {label: env.label};
    if (isObject(parameters)) {
        fullParams = {...fullParams, ...parameters};
    }

    if (isFunction(v)) {
        return v(errorKey, fullParams);
    }

    return v.replace(/\:(\w+)/g, (s, k) => {
        return get(fullParams, k);
    });
};

const renderLabel = (control: AbstractControl) => {
    if (control.meta.renderLabel === false) {
        return null;
    }
    return <label className="control-label mb-4" htmlFor={control.meta.name}>
        {control.meta.label}
    </label>

};

const renderHint = (control: AbstractControl) => {
    if (!control.meta.hint) {
        return null;
    }
    return <p className="text-gray-500 my-2">
        {control.meta.hint}
    </p>

};
const getInputAttributes = (control: AbstractControl): Record<string, any> => {
    const attrs: Record<string, any> = {};
    if (control.meta.inputAttributes) {
        Object.assign(attrs, control.meta.inputAttributes);
    }
    if (control.meta.name) {
        attrs.name = control.meta.name;
    }
    return attrs;
}

const renderError = (control: AbstractControl) => {
    if (!control.meta.errors || !control.touched) {
        return null;
    }
    return <>
    {
        (Object.keys(control.meta.errors)
        .filter(x => control.hasError(x))
        .map((errorKey) => (<p key={errorKey} className="text-red-700">
            {getErrorMessage(errorKey, control.meta, control)}
        </p>)))
    }
    </>

};

export function TextInput(control: AbstractControl) {
    return <div className={control.meta.className || ''}>
        {renderLabel(control)}
        {renderHint(control)}
        <input {...getInputAttributes(control)} className={classNames(control.meta.controlClassName, {
            'error': control.touched && control.invalid
        })}  {...control.handler(control.meta.type || "text")}
         placeholder={control.meta.placeholder || control.meta.label} />
        {renderError(control)}
    </div>
}

export function TextAreaInput(control: AbstractControl) {
    return <div className={control.meta.className || ''}>
        {renderLabel(control)}
        {renderHint(control)}
        <textarea  {...getInputAttributes(control)} className={classNames(control.meta.controlClassName, {
            'error': control.touched && control.invalid
        })} {...control.handler()} placeholder={control.meta.placeholder || control.meta.label} />
        {renderError(control)}
    </div>
}

export function SelectInput(control: AbstractControl) {
    const attrs: Record<string, string> = {};
    if (control.meta.name) {
        attrs.name = control.meta.name;
    }
    return <div className={control.meta.className || ''}>
        {renderLabel(control)}
        {renderHint(control)}
        <select {...attrs}  className={classNames(control.meta.controlClassName, {
            'error': control.touched && control.invalid
        })} {...control.handler()}>
            <option>(select {control.meta.placeholder || control.meta.label?.toLowerCase()})</option>
            {
                control.meta.options.map((opt: any, i: number) => (<option key={getOptionId(opt, i)} value={getOptionValue(opt)}>
                    {getOptionText(opt)}
                </option>))
            }
        </select>
        {renderError(control)}
    </div>
}

const formatDate = (value: Date) => format(value, 'yyyy-MM-dd');


const DateInputImpl: FC<{control: AbstractControl;
    placeholder?: string;
    className?: string; name?: string}> = ({control, placeholder, name, className}) => {
    const [textValue, setTextValue] = useState(control.value ? formatDate(control.value) : "");

    useEffect(() => {
        const subscriber = (value: any) => {
            const txt = value ? formatDate(value) : "";
            if (txt !== textValue) {
                setTextValue(txt);
            }            
        };

        control.valueChanges.subscribe(subscriber);

        return () => {
            control.valueChanges.unsubscribe(subscriber);
        };
    }, []);

    const attrs: Record<string, string> = {
        placeholder: placeholder || '',
        className: className || ''
    };
    if (name) {
        attrs.name = name;
    }

    return <input type="date" {...attrs} value={textValue} onChange={(e) => setTextValue(e.target.value)} onBlur={(e) => {
        if (textValue) {
            control.setValue(parseISO(textValue))
        } else {
            control.reset();
        }
        control.markAsTouched();
    }} />

};

export function DateInput(control: AbstractControl) {
    return <div className={control.meta.className || ''}>
        {renderLabel(control)}
        {renderHint(control)}
        <DateInputImpl control={control} name={control.meta.name} placeholder={control.meta.placeholder || control.meta.label} className={classNames(control.meta.controlClassName, {
            'error': control.touched && control.invalid
        })}/>
        {renderError(control)}
    </div>
}