import React from "react";
import DynamicEditable from "./dynamic";

export interface EditableTextProps {
    value: string;
    onEditing?: (editing: boolean) => void;   
    onChange: (value: string | null) => void | Promise<any>;
    prompt?: string;
    validation?: (value: string | null) => string | null;
    children?: any;
}

export default function EditableText(props: EditableTextProps) {
    return <DynamicEditable {...props} as="text">
        {props.children}
    </DynamicEditable>
}

