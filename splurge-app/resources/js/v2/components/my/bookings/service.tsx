import React, {useState, FC} from "react";
import { ExtendedServiceTier } from "./types";
import { Transition } from '@headlessui/react';
import { ChevronUpIcon, ChevronDownIcon, ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/outline";
import Amount from "../../amount";
import Lines from "../../lines";
import classNames from "classnames";
import ResponsiveLayout from "../../responsive-layout";


export interface BookingServiceProps {
    tier: ExtendedServiceTier;
}

const TierOptionsView: FC<BookingServiceProps & {showByDefault: boolean}> = (props) => {
    const [showDetails, setShowDetails] = useState(props.showByDefault);
    if (!props.tier.options || props.tier.options.length === 0) {
        return null;
    }
    return <div className="">
        <a onClick={(e) => setShowDetails(!showDetails)} className="w-full link flex flex-row justify-end px-4">
            More
            {
                !showDetails && <ChevronDownIcon className="ml-4 inline w-6 h-6" />
            }
            {
                showDetails && <ChevronUpIcon className="ml-4 inline w-6 h-6" />
            }
        </a>
        <Transition show={showDetails} enter="transition-transform duration-300 ease-out"
         leave="transition-transform duration-100"
          enterFrom="scale-y-0"
           enterTo="scale-y-100"
            leaveFrom="scale-y-100"
             leaveTo="scale-y-0">
                 <ol className="ml-4">
                    {
                        props.tier.options.map((to, i) => (<li key={`tier_opt_${i}`}>
                            <div className="flex flex-row mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 flex-shrink-0 text-green-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <p className="ml-4 text-sm">
                                        {to.text}
                                    </p>
                            </div>
                        </li>))
                    }

                 </ol>
        </Transition>
        
    </div>
    
}

const TiewView: FC<BookingServiceProps & {showOptionsDefault: boolean}> = ({tier, showOptionsDefault}) => {
    return <div>
        <h3 className="text-gray-800 font-bold text-xl">
            {tier.service.name} Service
        </h3>
        <figure className="overflow-clip max-h-36">
            <img src={tier.service.image_url} alt={`${tier.service.name} image`} className="w-full" />
        </figure>
        <div className="py-4">
            <Lines text={tier.service.description} />
        </div>
        <h4 className="text-gray-800 my-4 font-bold text-lg">
            {tier.name} Tier
        </h4>
        {
            tier.price && (<p className="text-gray-800">
                Price: <Amount className="font-bold" value={tier.price} />
            </p>)
        }
        <div className="py-4">
            <Lines text={tier.description} />
        </div>
        <TierOptionsView tier={tier} showByDefault={showOptionsDefault} />
        {
            tier.footer_message && (<div className="p-4 mt-4 bg-gray-50">
                <Lines text={tier.footer_message} />
            </div>)
        }
    </div>
};

const BookingServiceMobileComponent: FC<BookingServiceProps> = (props) => {
    return <TiewView tier={props.tier} showOptionsDefault={false} />

};

const BookingServiceDesktopComponent: FC<BookingServiceProps> = (props) => {
    return <TiewView tier={props.tier} showOptionsDefault={true} />

};


export default function BookingServiceComponent(props: BookingServiceProps) {
    return <ResponsiveLayout>
        {(opt) => {
            if (opt.mobile) {
                return <BookingServiceMobileComponent {...props} />
            }
            return <BookingServiceDesktopComponent {...props} />
        }}
    </ResponsiveLayout>
}