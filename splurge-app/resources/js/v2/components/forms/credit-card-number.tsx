import React, { useState, forwardRef } from "react";
import noop from "lodash/noop";

export interface CreditCardNumberInputProps {
    value: string;
    autoFocus?: boolean;
    onChange: (n: string) => void;
    onCanReleseFocus?: () => void;
    className?: string;
}

const clean = (v: string) => {
    if (!v) {
        return v;
    }
    return v.replace(/\D/g, '').trim();
};

const breakup = (v: string) => {
    if (!v) {
        return [];
    }
    const candidate = clean(v);
    const len = candidate.length;
    const buffer = [];
    for (let i = 0; i < len; i += 4) {
        buffer.push(candidate.substring(i, i + 4));
    }
    return buffer;
};

const isComplete = (chunks: string[]) => chunks.length >= 4 && chunks.every(x => x.length >= 4);

const  CreditCardNumberInput = forwardRef<HTMLInputElement, CreditCardNumberInputProps>((props: CreditCardNumberInputProps, ref) => {
    const [display, setDisplay] = useState(breakup(props.value).join(" "));
    return <input placeholder="Card number" ref={ref} type="tel" onBlur={(e) => {
        props.onChange(clean(display));
    }} className={props.className || ""} value={display} onChange={(e) => {
        const chunks = breakup(e.target.value);
        setDisplay(chunks.join(" "));
        if (isComplete(chunks) && props.onCanReleseFocus) {
            setTimeout(() => {
                (props.onCanReleseFocus || noop)();
            }, 200);
            
        }
    }} />
});

export default CreditCardNumberInput;


