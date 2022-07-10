
import React, {useState, forwardRef} from "react";
import noop from "lodash/noop";

export interface MonthAndYear {
    month: number;
    year: number;
}

const getDefault = (): MonthAndYear => {
    const d = new Date();
    return {
        month: d.getMonth() + 1,
        year: d.getFullYear()
    };
}


export interface MonthAndYearInputProps {
    value?: MonthAndYear;
    onChange: (my: MonthAndYear) => void;
    onCanReleaseFocus?: () => void;
    className?: string;
}

const compare = (my1: MonthAndYear, my2: MonthAndYear): number => {
    if (my1.year === my2.year) {
        return my1.month - my2.month;
    }
    return my1.year - my2.year;
};

const serialize = (my: MonthAndYear): string => {
    if (my.month < 1) {
        return "";
    }
    let buffer = String(my.month);
    if (my.month > 1) {
        buffer += "/";
    }
    if (my.year > 0) {
        if (my.year > 99) {
            const y = String(my.year);
            buffer += y.substring(y.length - 2);
        } else {
            buffer += String(my.year);
        }
    }
    return buffer;
};


const parse = (my: string): MonthAndYear => {

    if (!my) {
        return {month: 0, year: 0};
    }
    const digit = /\d+/;
    const parts = my.split(/\D/).filter(x => digit.test(x));
    if (parts.length === 0) {
        return {month: 0, year: 0};
    }
    if (parts.length === 1) {
        return {month: parseInt(parts[0], 10) % 13, year: 0};
    }
    return {month: parseInt(parts[0], 10) % 13,
         year: parseInt(parts[1], 10)};

};

export const parseToMonthAndYear = parse;

export const getCurrent = getDefault;

export const compareMonthAndYears = compare;

const normalized = (my: MonthAndYear): MonthAndYear => {
    if (my.month < 1) {
        return my;
    }
    if (my.year > 9999) {
        return {month: my.month, year: my.year % 10000};
    }
    if (my.year < 999) {
        return {month: my.month, year: 2000 + my.year};
    }
    return my;
};



const MonthAndYearInput = forwardRef<HTMLInputElement, MonthAndYearInputProps>((props: MonthAndYearInputProps, ref) => {
    const [currentValue, setCurrentValue] = useState(props.value ? serialize(props.value) : "");
    return <input ref={ref} type="tel" className={props.className} onBlur={(e) => {
        const my = parse(currentValue);
        props.onChange(normalized(my));
    }} value={currentValue} placeholder="M/YY" onChange={(e) => {
        const parsed = parse(e.target.value.trim());
        setCurrentValue(serialize(parsed));
        if (parsed.year > 9 && parsed.month > 0 ) {
            props.onChange(normalized(parsed));
            setTimeout(() => {
                (props.onCanReleaseFocus || noop)();
            }, 100);
        }   
    }} />
});


export default MonthAndYearInput;