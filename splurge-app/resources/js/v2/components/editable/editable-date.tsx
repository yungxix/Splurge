import React from "react";
import DynamicEditable from "./dynamic";

export interface EditableDateProps {
    value: string | Date;
    onEditing?: (editing: boolean) => void;   
    onChange: (value: Date | null) => void | Promise<any>;
    prompt?: string;
    validation?: (value: Date | null) => string | null;
    children?: any;
}

export default function EditableDate(props: EditableDateProps) {
    return <DynamicEditable {...props} as="date">
        {props.children}
    </DynamicEditable>
}

