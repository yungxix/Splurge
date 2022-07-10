import React, {FC, useState, useEffect} from "react"
import { PricingOptionsEditorProps } from "./types";
import get from 'lodash/get';
import pick from 'lodash/pick';

const E: FC<PricingOptionsEditorProps> = (props) => {
    const [minimum, setMinimum] = useState(props.value ?  get(props.value, 'minimum', 0) : 0);
    const [maximum, setMaximum] = useState(props.value ?  get(props.value, 'maximum', 0) : 0); 
    const [prompt, setPrompt] = useState(props.value ?  get(props.value, 'prompt', "") : ""); 
    const sharedAttrs = pick(props, 'onFocus');

    useEffect(() => {
        props.onChange({
            minimum,
            maximum,
            prompt
        });
    }, [minimum, maximum, prompt])
    return <div>
        Multiple of
        <div className="flex flex-row">
            <label className="w-2/3">
            Minimum
            </label>
            <input autoComplete="off" className="control ml-2 w-20" {...sharedAttrs} type="number" value={minimum} onChange={(e) => {
                setMinimum(e.target.valueAsNumber)
            }} />
        </div>

        <div className="flex flex-row mt-2">
            <label className="w-2/3">
            Maximum
            </label>
            <input  autoComplete="off"  className="control ml-2 w-20" {...sharedAttrs} type="number" value={maximum} onChange={(e) => {
                setMaximum(e.target.valueAsNumber)
            }} />
        </div>

        Prompt message
        <input  autoComplete="off"  className="control w-full" {...sharedAttrs} type="text" value={prompt} onChange={(e) => {
                setPrompt(e.target.value)
            }} />
            <p className="text-sm text-gray-700">
                The prompt message will assist customers 
                to select the right number for this pricing. E.g. 'Enter number of guests'
            </p>

    </div>

};


export default E;