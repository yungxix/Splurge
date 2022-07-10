import { AbstractControl } from "react-reactive-form";

export type ErrorMessageFactory = (key: string, args?: any) => string;

export interface ControlMeta extends Record<string, any> {
    label?: string;
    errors?: Record<string, string | ErrorMessageFactory>;
    className?: string;
    controlClassName?: string;
}

export type ControlRenderProps = AbstractControl;