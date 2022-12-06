import React, { useState, FC, useRef, createContext, useContext, useMemo } from "react";

import { get, isNumber, uniqueId } from 'lodash';

import axios from "axios"; '../../../../axios-proxy';

import { GuestOptions, CustomerEventGuest } from './types';

import { PencilIcon, RefreshIcon } from '@heroicons/react/solid';

import { CheckIcon, XIcon } from '@heroicons/react/outline';

import { parse as parseDate, parseISO, format as formatDate } from 'date-fns';
import { useCommiter2 } from "./hooks";
import { GuestContext, GuestOptionsContext } from "./contextx";
import GuestAttachments from "./guest-attanchment";


const TimeRenderer: FC<{ value: string; className?: string }> = ({ value, className, children }) => {
    const formatted = useMemo(() => {
        if (!value) {
            return null;
        }
        if (/^\d{4}/.test(value)) {
            return formatDate(parseISO(value), 'h:mm a');
        }
        return formatDate(parseDate(value, 'HH:mm', new Date()), 'h:mm a');
    }, [value]);

    if (!formatted) {
        return null;
    }
    return <span className={className || ''}>
        {formatted}
        {children}
    </span>

};





interface OptionsPresenterProps {
    guestId: number;
    value?: GuestOptions;
    editable: boolean;
    attribute: keyof CustomerEventGuest;
}


const EditableInput: FC<{
    editable: boolean;
    type?: string;
    className?: string;
    value: string;
    placeholder?: string;
    guestAttribute: string,
    emptyContent?: any;
    guestId: number;
    renderValue?: (v: string) => any;
}> = (props) => {
    const [current, setCurrent] = useState(props.value);
    const inputRef = useRef<HTMLInputElement | null>(null);
    const editor = useCommiter2();

    const defaultRender = (v: string): any => {
        if (!v) {
            return props.emptyContent ? props.emptyContent : null;
        }
        return (<span className={props.className || ''}>{v}</span>);
    };

    const renderFn = props.renderValue || defaultRender;

    const doSave = async () => {
        try {
            const v = inputRef.current?.value || '';
            await editor.commit({
                id: props.guestId,
                attribute: props.guestAttribute,
                value: v
            });
            setCurrent(v);
        } catch (error) {

        }
    };
    if (editor.busy) {
        return <RefreshIcon className="w-6 h-6 animate-spin" />
    }
    if (editor.editing) {
        return <div>
            {
                editor.failed && (<p className="text-center text-red-800">
                    Failed to save changes <a onClick={editor.cancel}>&times;</a>
                </p>)
            }
            <div className="flex flex-row items-center gap-x-2">
                <input type={props.type || 'text'} defaultValue={current} ref={inputRef} placeholder={props.placeholder || ''} className="control flex-grow" />
                {
                    !editor.busy && (<>
                        <a title="Cancel" className="cursor-pointer" onClick={editor.cancel}>
                            <XIcon className="w-6 h-6 text-red-900" />
                        </a>
                        <a title="Save" onClick={doSave} className="cursor-pointer">
                            <CheckIcon className="w-6 h-6 text-green-900 " />
                        </a>
                    </>)
                }

            </div></div>
    }

    return <span>
        {renderFn(current)}
        {
            props.editable && (<span className="ml-8 text-sm">
                <a className="cursor-pointer" onClick={editor.edit}>
                    <PencilIcon className="w-4 h-4" />
                </a>
            </span>)}
    </span>

};

const EditableSelect: FC<{
    editable: boolean;
    className?: string;
    value: string;
    placeholder?: string;
    guestAttribute: keyof CustomerEventGuest,
    emptyContent?: any;
    guestId: number;
    options: Array<{ text: string; value: string }>
}> = (props) => {
    const [current, setCurrent] = useState(props.value);
    const inputRef = useRef<HTMLSelectElement | null>(null);
    const editor = useCommiter2();

    const doCancel = () => {
        editor.cancel();
    };
    const doEdit = () => editor.edit();

    const doSave = async () => {
        const v = inputRef.current?.value || '';

        try {
            await editor.commit({
                id: props.guestId,
                attribute: props.guestAttribute,
                value: v
            });
            setCurrent(v);
        } catch (error) {

        }
    };
    if (editor.busy) {
        return <RefreshIcon className="w-6 h-6 animate-spin" />
    }
    if (editor.editing) {
        return <div>
            {
                editor.failed && (<p className="text-center text-red-800">
                    Failed to save changes <a onClick={doCancel}>&times;</a>
                </p>)
            }
            <div className="flex flex-row items-center gap-x-2">
                <select className="flex-grow control " defaultValue={current} ref={inputRef}>
                    {props.options.map((opt, i) => (<option value={opt.value} key={`${i}_opt_${opt.value}`}>
                        {opt.text}
                    </option>))}
                </select>
                {
                    !editor.busy && (<>
                        <a title="Cancel" className="cursor-pointer" onClick={doCancel}>
                            <XIcon className="w-6 h-6 text-red-900 " />
                        </a>
                        <a title="Save" onClick={doSave} className="cursor-pointer">
                            <CheckIcon className="w-6 h-6 text-green-900 " />
                        </a>
                    </>)
                }

            </div></div>
    }

    return <span>
        {
            current && (<span className={props.className || ''}>{current}</span>)
        }
        {
            !current && props.emptyContent && (<>{props.emptyContent}</>)
        }
        {
            props.editable && (<span className="ml-8 text-sm">
                <a className="cursor-pointer" onClick={doEdit}>
                    <PencilIcon className="w-4 h-4" />
                </a>
            </span>)
        }

    </span>

};


const AttendanceRenderer: FC<{
    editable: boolean;
    time?: string | null;
    guestId: number;
}> = (props) => {
    const [currentTime, setCurrentTime] = useState(props.time || '');
    const editor = useCommiter2();
    const timeRef = useRef<HTMLInputElement | null>(null);

    const doSave = async () => {
        const time = timeRef.current?.value;
        if (!time) {
            return;
        }
        const saved = await editor.commit({
            id: props.guestId,
            value: time,
            attribute: 'attendance_at'
        });    
        if (saved) {
            setCurrentTime(time);
        }
    };

    if (editor.busy) {
        return <RefreshIcon className="w-6 h-6 animate-spin" />
    }
    const hasTime = Boolean(currentTime);
    if (editor.editing) {
        return <div>
            <label className="block">Pick time</label>
            <div className="flex flex-row items-center gap-x-4">
                <input type="time"
                    ref={timeRef}
                    disabled={editor.busy}
                    className="control"
                    defaultValue={currentTime} />
                {
                    !editor.busy && (<>
                        <a className="cursor-pointer" onClick={editor.cancel}>
                            <XIcon className="w-6 h-6 text-red-700" />
                        </a>
                        <a className="cursor-pointer" onClick={doSave}>
                            <CheckIcon className="w-6 h-6 text-green-700" />
                        </a>
                    </>)
                }
            </div>


        </div>
    }
    if (hasTime) {
        return <div>
            At <TimeRenderer value={currentTime} />
            {
                props.editable && (<a className="ml-4 cursor-pointer" title="Edit time" onClick={editor.edit}>
                    <PencilIcon className="w-6 h-6" /> 
                </a>)
            }
        </div>
    }
    return <div>
        {
            props.editable ? (<a className="cursor-pointer" title="Choose time" onClick={editor.edit}>
                Attended?
            </a>) : (<em className="text-yellow-600">Not marked as attended</em>)
        }
    </div>
};

const asSelectOptions = (items: string[]): Array<{ text: string; value: string }> => items.map(a => ({ text: a, value: a }));


const GuestNameRenderer: FC<{
    value: string;
    editable: boolean;
    className?: string;
    guestId: number;
}> = (props) => {
    return <EditableInput {...props} guestAttribute='name' />
}

const Row: FC<{
    label: string;
    icon?: any;
    render: () => any
}> = (props) => (<tr className="align-top">
    <th scope="row" className="bg-gray-50 text-left px-4 py-2">
        {props.label}
        {props.icon}
    </th>
    <td className="px-6 py-2">
        {props.render()}
    </td>
</tr>);


export const GuestView: FC<{
    value: CustomerEventGuest,
    editable: boolean;
}> = ({ value, editable }) => {
    return <GuestContext.Provider value={{id: value.id}}>
        <div>
        <table className="w-full border-separate">
            <tbody>
                <Row label="Name" render={() => (<GuestNameRenderer
                    guestId={value.id} className="text-lg font-bold" value={value.name} editable={editable} />)} />

                <Row label="Gender" render={() => (<EditableSelect
                    editable={editable}
                    value={value.gender || ''}
                    guestAttribute='gender'
                    guestId={value.id}
                    options={asSelectOptions(['Unknown', 'Male', 'Female'])}
                />)} />
                <Row label="Table" render={() => (<EditableInput
                    editable={editable}
                    value={value.table || ''}
                    emptyContent={(<em>N/A</em>)}
                    placeholder="Enter table name"
                    guestAttribute='table_name'
                    guestId={value.id}
                />)} />
                <Row label="Attended at" render={() => (<AttendanceRenderer
                    time={value.attended_at}
                    guestId={value.id}
                    editable={editable}
                />)} />
                <Row label="Barcode" render={() => (<span>
                    {value.barcode_image_url && (<img src={value.barcode_image_url} />)}
                    {!value.barcode_image_url && (<em>N/A</em>)}
                </span>)} />
                <Row label="Accepted" render={() => (<GuestAttachments
                    attribute="accepted"
                    value={value.accepted}
                    editable={editable}
                />)} />
                <Row label="Presented" render={() => (<GuestAttachments
                    attribute="presented"
                    value={value.presented}
                    editable={editable}
                />)} />
                
            </tbody>
        </table>
    </div>
    </GuestContext.Provider>
    
}