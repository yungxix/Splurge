import React, {FC, useState, useMemo, ChangeEvent, useCallback} from "react";

import { parseISO, isDate, format as formatDate } from "date-fns";

import {PencilIcon} from "@heroicons/react/solid";


import classNames from "classnames";

interface ControlRenderProps {
    value: string,

    onChange: (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => void;

    onFocus?: (e: any) => void;

    className?: string;

    placeholder?: string;
}


interface ControlCoordinator<T> {
    getDefault(env: {value?: T}): string;
    render(props: ControlRenderProps): any;
    parse(text: string): T | null;
}

const makeInputControlCoodinator = (type: string): ControlCoordinator<string> => ({
    getDefault: p => p.value || '',
    render: props => <input onFocus={props.onFocus} placeholder={props.placeholder} type={type} className={props.className} value={props.value} onChange={props.onChange} />,
    parse: t => t
});

export type SupportedControlType = "text" | "number" | "date" | "textarea" | "email" | "phone";

type LikeDate = Date | string;

const dateCordinator: ControlCoordinator<LikeDate> = {
    getDefault(p) {
        if (p.value) {
            if (isDate(p.value)) {
                return formatDate(p.value as Date, 'yyyy-MM-dd');
            }
            return p.value as string;
        }
        return '';
    },
    render(props) {
        return <input type="date"  placeholder={props.placeholder} onFocus={props.onFocus} value={props.value} className={props.className} onChange={props.onChange} />
    },
    parse(text) {
        if (text) {
            return parseISO(text);
        }
        return null;
    } 
    
};


const CORDINATORS: Record<SupportedControlType, ControlCoordinator<any>> = {
    'text': makeInputControlCoodinator('text'),
    'number': makeInputControlCoodinator('number'),
    'email': makeInputControlCoodinator('email'),
    'phone': makeInputControlCoodinator('tel'),
    'textarea': {
        getDefault: p => p.value || '',
        render(props) {
            return <textarea
            placeholder={props.placeholder}
            onFocus={props.onFocus}
             value={props.value} 
             className={props.className} onChange={props.onChange} />
        },
        parse: t => t
    },
    'date': dateCordinator
};

export type Alignment = "vertical" | "horizontal" | "default";

export interface EditableProps<T> {
    value: T | null;
    onEditing?: (editing: boolean) => void;   
    onChange: (value: T | null) => void | Promise<any>;
    prompt?: string;
    validation?: (value: T | null) => string | null;
    as?: SupportedControlType;
    inputClassName?: string;
    placeholder?: string;
    alignment?: Alignment;

}

const useVerticalLayout = (t: SupportedControlType): boolean => t === 'textarea';

const DynamicEditable: FC<EditableProps<any>> = (props) => {
    const cordinator = useMemo(() => CORDINATORS[props.as || 'text'] || CORDINATORS.text, [props.as]);

    const [currentValue, setCurrentValue] = useState(cordinator.getDefault(props));
    const [editing, setEditing] = useState(false);
    const [errorMessage, setErrorMessage] = useState<string | null>(null);
    const [saving, setSaving] = useState(false);
    


    const doSave = () => {
        const toSave = cordinator.parse(currentValue);
        const error = props.validation ? props.validation(toSave) : null;
        setErrorMessage(error);
        if (!error) {
            const result = props.onChange(toSave);
            if (result) {
                setSaving(true);
                result.then(() => {
                    setSaving(false);
                    setEditing(false);
                }, (err) => {
                    setSaving(false);
                    setErrorMessage(err.message);
                });
            }
        }
    };

    const doCancel = () => {
        setCurrentValue(cordinator.getDefault(props));
        setEditing(false);
    };

    const veritical = props.alignment === "vertical" ||  useVerticalLayout(props.as || 'text');

    const defaultControlClass = veritical ? "block control w-full" : "control flex-1";

    if (saving) {
        return <div className="flex flex-row justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-8 w-8 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
            <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
    }

    if (editing) {
        
        return <form noValidate onSubmit={(e) => {
            e.preventDefault();
            doSave();
        }}>
            {
                errorMessage && (<p className="text-red-700">
                    {errorMessage}
                </p>)
            }
            <div className={classNames("flex", {
                "flex-row items-center": !veritical,
                "flex-col justify-start": veritical
            })}>
                {
                    props.prompt && (<span className="mr-4 text-gray-700">
                        {props.prompt}
                    </span>)
                }
                {cordinator.render({
                    className: props.inputClassName || defaultControlClass,
                    placeholder: props.placeholder,
                    value: currentValue,
                    onChange: (e) => {
                        setCurrentValue(e.target.value)
                        if (props.validation) {
                            setErrorMessage(props.validation(cordinator.parse(e.target.value)))
                        }
                    },
                    onFocus: (e) => setErrorMessage(null)
                })}
                <div className={classNames("flex flex-row items-center gap-x-4", {
                    "w-full mt-4": veritical,
                    "ml-8": !veritical
                })}>
                    <button onClick={doCancel} type="button">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-green-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </form>
    }

    return <a onClick={(e) => setEditing(true)} className="link border-dashed border-b" title="Edit">
        {props.children} <PencilIcon className="w-4 h-4 inline" />
    </a>

}

export default DynamicEditable;