import React from "react";
import { ValidationMessages } from "./types";

export interface ValidationMessageRendererProps {
    messages: ValidationMessages | null | undefined;
    attribute: string;
    className?: string;
}


export const hasError = (key: string, messages: undefined | null | ValidationMessages): boolean => {
    if (!messages) {
        return false;
    }
    
    return messages.some(x => x.path === key);
};



export function ValidationMessageRenderer(props: ValidationMessageRendererProps) {
    if (!props.messages) {
        return null;
    }
    if (!hasError(props.attribute, props.messages)) {
        return null;
    }
    const item = props.messages.find(x => x.path === props.attribute);
    const errorClass = props.className || 'text-red-700';
    return <>
        {item?.errors.map((m, i) => (<p className={errorClass} key={`error_${props.attribute}_${i}`}>{m}</p>))}
    </>
}