import React, { FC, useState } from "react";

import { Transition } from "@headlessui/react";

import Lines from './lines';

import Amount from './amount';

import classNames from 'classnames';

interface TierOptions extends Record<string, any> {
    text: string;
}

export interface ServiceTier {
    id: number;
    name: string;
    description: string;
    options: Array<TierOptions> | null;
    footer_message: string;
    price?: number;
}

export interface TierSelectionProps {
    inputName: string;
    tiers: Array<ServiceTier>;
    onChange: (t: number) => void;
    value?: number
}

interface TierItemProps {
    tier: ServiceTier;
    selected: boolean;
    onSelect: () => void;
    inputName: string;
}

const DesktopItem: FC<TierItemProps> = ({tier, selected, inputName, onSelect}) => (<a onClick={onSelect} className={classNames(
    'block rounded-md shadow-md bg-white shadow-gray-500 p-4 cursor-pointer',
    selected ? "-translate-y-2 duration-200 ring ring-splarge-600" : null
    )}>

        <h4 className="block text-xl w-full mb-8">
            {tier.name}
        </h4>

        <Lines text={tier.description} className="text-gray-500"/>

        {
            tier.price && (<h4 className="text-center my-4 text-3xl">
                <Amount value={tier.price} />
            </h4>)
        }

        {
            tier.options && (<ol className="mb-8">
                {
                    tier.options.map((opt, i) => (<li key={`opt_${i}`} className="mb-4">
                        <div className="flex flex-row">
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 flex-shrink-0 text-green-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                <p className="ml-4 text-sm">
                                    {opt.text}
                                </p>
                        </div>
                        
                    </li>))
                }
            </ol>)
        }
      
        {
            tier.footer_message && (<div>
                <Lines text={tier.footer_message} className="text-gray-500" />

            </div>)
        }


</a>);


const MobileItem: FC<TierItemProps> = ({tier, selected, inputName, onSelect}) => {
    const [detailsShown, setShowDetails] = useState(false);
    let sampleDescription = tier.description.substring(0, 100);
    if (tier.description.length > 100) {
        sampleDescription += "...";
    }
    const toggleOptionElement = <div className="flex justify-end">
        <a onClick={(e) => setShowDetails(!detailsShown)}>
            {
                !detailsShown && (<svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M19 9l-7 7-7-7" />
            </svg>)
            }

            {
                detailsShown && ( <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M5 15l7-7 7 7" />
                </svg>)
            }
        </a>
    </div>
    return <div className={classNames(
        "rounded-md shadow-md bg-white shadow-gray-500 p-4", {
            "ring ring-splarge-600": selected
        })}>
        {
            !detailsShown && tier.price && (<Amount className="float-right text-lg" value={tier.price} />)
        }
        <a onClick={onSelect} className="block link text-lg w-full mb-2">
            {tier.name}
        </a>

        {
            !detailsShown && (<div className="mt-4">
                <Lines text={sampleDescription} className="text-gray-700" />
                {toggleOptionElement}
            </div>)
        }
        <Transition show={detailsShown} enter="transition-transform duration-300 ease-out"
         leave="transition-transform duration-100"
          enterFrom="scale-0"
           enterTo="scale-100"
            leaveFrom="scale-100"
             leaveTo="scale-0">
                 {toggleOptionElement}
            <Lines text={tier.description} className="text-gray-500"/>

            {
                tier.price && (<h4 className="text-center my-4 text-3xl">
                    <Amount value={tier.price} />
                </h4>)
            }

            {
                tier.options && (<ol className="mb-8">
                    {
                        tier.options.map((opt, i) => (<li key={`opt_${i}`} className="mb-4">
                            <div className="flex flex-row">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 flex-shrink-0 text-green-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p className="ml-4 text-sm">
                                        {opt.text}
                                    </p>
                            </div>
                            
                        </li>))
                    }
                </ol>)
            }

            {
                tier.footer_message && (<div>
                    <Lines text={tier.footer_message} className="text-gray-500" />

                </div>)
            }
            {toggleOptionElement}
        </Transition>
    </div>
};



const SelectionWidget2: FC<TierSelectionProps> = (props) => {

    return <div className="bg-gray-50 md:p-8">
            <div className="hidden md:block">
                <div className="grid grid-cols-3 gap-4">
                {
                    props.tiers.map((tier) => (<DesktopItem 
                        selected={tier.id === props.value} tier={tier} inputName={props.inputName} key={tier.id} onSelect={() => {
                            props.onChange(tier.id)
                        }} />))
                }
                </div>
            </div>
            <div className="md:hidden">
                <div className="flex flex-col justify-start items-start gap-y-8">
                    {
                        props.tiers.map((tier) => (<MobileItem 
                            selected={tier.id === props.value} tier={tier} inputName={props.inputName} key={tier.id} onSelect={() => {
                                props.onChange(tier.id)
                            }} />))
                    }
                </div>
            </div>
    </div>
}
export default SelectionWidget2;