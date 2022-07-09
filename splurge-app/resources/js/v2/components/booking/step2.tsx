import React, {FC, useContext, useState } from "react";
import { BookingEnvContext, BookingContext, StepContext } from "./context";
import classNames from "classnames";
import Lines from "../lines";
import Amount from "../amount";
import { Transition } from "@headlessui/react";
import { useTier } from "./hooks";
import { format, formatDistanceToNow } from "date-fns";
import { ServiceTierImage } from "../booking-tier-selector";

const CustomerRenderer2: FC = (props) => {
    const bookingCtx = useContext(BookingContext);
    const { customer, address } = bookingCtx.data;
    return <div className="">
        <h4 className="text-xl text-gray-700">
            {customer?.first_name} {customer?.last_name}
        </h4>
        <div className="mt-4">
            <p className="text-gray-700">Contact:</p>
            <p>
                Email: <span className="font-bold">{customer?.email}</span> <span className="ml-8">
                    Phone: <span className="font-bold">{customer?.phone}</span>
                </span>
            </p>
        </div>
        <div className="mt-4">
            <p className="text-gray-700">Location:</p>
            <address>
                <em className="block">
                    {address?.line1}
                </em>
                {
                    address?.line2 && (<em className="block">{address.line2}</em>)
                }
                <em className="block">{address?.state}{address?.zip && (<span className="mx-2">{address.zip}</span>)}</em>
            </address>
        </div>
    </div>

}

function BookingInfo() {
    const ctx = useContext(BookingContext).data!;


    return <div className="my-6">
        <p className="text-gray-700">
            Booking...
        </p>

        <blockquote>
            <Lines text={ctx.description!} />    
        </blockquote>
        <p className="text-gray-700 mt-4">
            On...
        </p>
        <p>
            {format(ctx.eventDate!, 'EEEE, MMMM do')} 
            <em className="ml-4">(in {formatDistanceToNow(ctx.eventDate!, {includeSeconds: false})})</em>
        </p>
    </div>
}



const TierRenderer: FC = (props) => {
    const tier = useTier();

    return <div className={classNames(
        'rounded-md shadow-md bg-white shadow-gray-500 p-4',
        "ring ring-splarge-600"
    )}>
        {props.children}

        <ServiceTierImage tier={tier} />
        <h4 className="font-bold text-2xl">{tier?.name}</h4>


        <Lines text={tier?.description || ""} className="text-gray-500" />

        {
            tier?.price && (<h4 className="text-center my-4 text-3xl">
                <Amount value={tier.price} />
            </h4>)
        }

        {
            tier?.options && (<ol className="mb-8 ml-4">
                {
                    tier.options.map((opt, i) => (<li key={`opt_${i}`} className="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4 inline text-green-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span className="ml-4 text-sm">
                            {opt.text}
                        </span>
                    </li>))
                }
            </ol>)
        }

        {
            tier?.footer_message && (<div>
                <Lines text={tier.footer_message} className="text-gray-500" />

            </div>)
        }


    </div>


};

const DesktopRenderer:FC = (props) => {
    return <div>
        <h5 className="text-xl text-gray-700 mb-8">
            Please confirm the information you provided.
        </h5>
        <div className="md:flex flex-row">
            <div className="md:w-1/3">
                <TierRenderer />
            </div>
            <div className="md:w-2/3 pl-8">
                <BookingInfo />
                <CustomerRenderer2 />
                
                {props.children}
            </div>
            
        </div>
    </div>
};

const MobileRenderer:FC = (props) => {
    const [showTier, setShowTier] = useState(false);
    const tier = useTier();
    return <div>
        <h5 className="text-xl text-gray-700 mb-8">
            Please confirm the information you provided.
        </h5>
        <Transition show={!showTier}>
            <div>
                <div className="flex flex-row justify-between items-center">
                    <h4 className="text-lg">
                        {tier?.name} Package
                    </h4>
                    <a className="link" onClick={(e) => setShowTier(true)}>
                        <span className="text-sm">(tap to see details)</span>
                    </a>
                </div>
                <BookingInfo />
                <CustomerRenderer2 />
                {props.children}
            </div>
        </Transition>
        <Transition show={showTier} 
        enter="transition-transform duration-300 ease-out"
         leave="transition-transform duration-100"
          enterFrom="scale-0"
           enterTo="scale-100"
            leaveFrom="scale-100"
             leaveTo="scale-0">
            <div>
                <TierRenderer>
                    <a className="float-right" onClick={(e) => setShowTier(false)}>
                            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                <path strokeLinecap="round" strokeLinejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                    </a>
                </TierRenderer>
            </div>
        </Transition>
    </div>
};

export default function Step2Renderer() {
    const stepContext = useContext(StepContext);
    const confirmed = () => stepContext.onChange(stepContext.step + 1);
    const buttons = <div className="mt-8 flex flex-row justify-end md:justify-start gap-4 items-center">
        <button type="button" className="btn" onClick={(e) => stepContext.onChange(stepContext.step - 1)}>
            Back
        </button>
        <button type="button" className="btn" onClick={confirmed}>
            Continue
        </button>
    </div>
        return <>
            <div className="hidden md:block">
                <DesktopRenderer>
                    {buttons}
                </DesktopRenderer>
            </div>
            <div className="md:hidden">
                <MobileRenderer>
                {buttons}
                </MobileRenderer>
            </div>
        </>
    
}
