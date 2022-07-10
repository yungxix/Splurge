import React, {FC, useState, useEffect} from "react"
import { PricingOptionsEditorProps } from "./types";
import get from 'lodash/get';
import pick from 'lodash/pick';

const E: FC<PricingOptionsEditorProps> = (props) => {
    const [rate, setRate] = useState(props.value ?  get(props.value, 'rate', 0) : 0);
    const [base, setBase] = useState(props.value ? get(props.value, 'base', 'default') : 'default');
    const sharedAttrs = pick(props, 'onFocus');
    useEffect(() => {
        props.onChange({
            rate,
            base
        });
    }, [rate, base])
    return <div>
        <div className="flex flex-row justify-start">
            <label htmlFor="percentage_rate_field" className="w-2/3">
            Rate(%)
            </label>
            <input className="control ml-2 w-20" min={0} {...sharedAttrs} id="percentage_rate_field" type="number" value={rate} onChange={(e) => {
                setRate(e.target.valueAsNumber)
            }} />
        </div>

        <div className="flex flex-row justify-start mt-2">
            <label className="w-2/3">
            Based on category
            </label>
            <input autoComplete="off" className="control ml-2" {...sharedAttrs} type="text" value={base} onChange={(e) => {
                setBase(e.target.value)
            }} />
        </div>

        <p className="mt-8 text-sm text-gray-700 bg-gray-100 rounded-md">
            <em>
                &quot;default&quot; percentage base will apply percentage on non-percentage pricing items.
                 WARNING! Do not set the base to a category that is also a percentage.
            </em>
        </p>

    </div>

};


export default E;