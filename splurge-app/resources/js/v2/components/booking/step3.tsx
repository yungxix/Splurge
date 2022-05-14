import React, {FC, useState, useContext, useEffect} from "react";
import { BookingContext, BookingEnvContext, StepContext } from "./context";
import { useTier } from "./hooks";

import axios from '../../../axios-proxy';

import PaymentForm, { Payment } from "./payment";

import {formatISO} from "date-fns";
import SpinningRefreshIcon from "../spinning-refresh-icon";
import isDevelopment from "../../../is_dev";
import InDevelopment from "../dev";

enum Status {
    idle = 0,
    processing = 1,
    completed = 2,
    failed = -1
}


interface SubmitResult {
    code: string; 
    verificationCode?: string
}

const SubmitStep: FC = (props) => {
    const [status, setStatus] = useState(Status.idle);
    const [bookingCode, setBookingCode] = useState("");
    const [paying, setPaying] = useState(false);
    const [verificationCode, setVerificationCode] = useState("");
    const env = useContext(BookingEnvContext);
    const booking = useContext(BookingContext);
    const tier = useTier();

    

    const post = async () => {
        setStatus(Status.processing);
        try {
            const data: Record<string, any> = {
                customer: booking.data.customer,
                description: booking.data.description,
                service_tier_id: booking.data.selected_tier,
                service_id: booking.data.service_id,
                event_date: booking.data.eventDate ? formatISO(booking.data.eventDate) : null,
                address: booking.data.address
            };
            const response = await axios.post<{data: SubmitResult}>(env.postUrl, data);
            setBookingCode(response.data.data.code);
            setVerificationCode(response.data.data.verificationCode || "");
            setStatus(Status.completed);
        } catch (error) {
            if (isDevelopment()) {
                console.error(error);
            }
            setStatus(Status.failed);
        }
    };

    useEffect(() => {
        setTimeout(() => {
            post();
        });
    }, []);

    if (paying) {
        return <AcceptPayemntStep verificationCode={verificationCode}
        bookingReference={bookingCode} amount={tier!.price!}  />
    }


    
    return <div>
        {
            status === Status.processing && (<div className="py-4 flex flex-row justify-center items-center gap-8">
                <SpinningRefreshIcon></SpinningRefreshIcon>
                <p className="ml-8">
                    Please wait &hellip;
                </p>
            </div>)
        }

        {
            status === Status.failed && (<div className="py-4 flex flex-row items-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div className="ml-8">
                    <p>
                        Unfortunately, an error occurred while trying to record your booking.
                        Please do not hesitate to let us know. You can send a mail to <a className="link"
                         href={`mailto:${env.contact.email}`}>{env.contact.email}</a> or call us on <a className="link" href={`tel:${env.contact.phone}`}>
                             {env.contact.phone}
                         </a>.
                    </p>
                    <p>
                        Appologies for any inconvenience.
                    </p>

                    <InDevelopment>
                        <p className="pt-8">
                            <a className="btn" onClick={post}>
                                Re-try
                            </a>
                        </p>
                    </InDevelopment>
                </div> 
            </div>)
        }

        {
            status === Status.completed && (<div className="py-4 flex flex-row items-center">
                <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                </svg>
                <div className="ml-8">
                    <p>
                    Your booking has been successfully registered and your confirmation code is {" "}
                    <span className="font-bold px-4 bg-gray-700 rounded text-white">{bookingCode}</span>.
                     You will be notified via email on how to proceed.  
                    </p>
                    {
                        tier?.price && verificationCode && (<div>
                            <p>
                            Confirm your booking with full or a deposit payment
                            </p>
                            <button className="btn block w-full my-8" type="button" onClick={(e) => setPaying(true)}>
                                Pay
                            </button>
                        </div>)
                    }
                    {
                        env.catalogUrl && (<p>
                            Want to checkout our catalog of events? Go to <a className="link" href={env.catalogUrl}>this link</a>.
                        </p>)
                    }

                    {
                        !env.catalogUrl && (<p>
                            See more from <a href="/" className="link">Serene Events</a>
                        </p>)
                    }
                    
                </div>
            </div>)
        }

    </div>
};


const PostPaymentStep: FC<{payment:  Payment; bookingCode: string; verificationCode: string}> = (props) => {
    const [status, setStatus] = useState(Status.idle);
    const env = useContext(BookingEnvContext);

    const post = async () => {
        setStatus(Status.processing);
        try {
            await axios.post(env.paymentUrl, {
                reference: props.payment.reference,
                payment: props.payment,
                verification_code: props.verificationCode,
                amount: props.payment.amount
            });
            setStatus(Status.completed);
        } catch (error) {
            setStatus(Status.failed);
        }
    };

    useEffect(() => {
        post();
    }, []);

    

    switch (status) {
        case Status.failed:
            return (<div className="py-4 flex flex-row items-center">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div className="ml-8">
                <p>
                    Unfortunately, an error occurred while trying to the payment. Please contact us via email at {" "}
                    <a className="link" href={`mailto:${env.contact.email}`}>{env.contact.email}</a> or call {" "}
                    <a className="link" href={`tel:${env.contact.phone}`}>{env.contact.phone}</a> for a resolution
                </p>
                <p>
                    Appologies for any inconvenience.
                </p>

                <InDevelopment>
                    <p className="mt-8">
                        <button className="btn" type="button" onClick={post}>
                            Re-try
                        </button>
                    </p>
                </InDevelopment>
            </div> 
        </div>);

        case Status.processing:
            return (<div className="flex flex-row justify-center items-center">
                <SpinningRefreshIcon></SpinningRefreshIcon>
                <p className="ml-8">
                    Please wait&hellip;
                </p>
            </div>);
        case Status.completed:
            return (<div className="py-4 flex flex-row items-center">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
            <path strokeLinecap="round" strokeLinejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
            </svg>
            <div className="ml-8">
                <p>
                Your payment for booking #{props.bookingCode} has been accepted. {" "}
                You will receive a confirmation message
                </p>
                {
                    env.catalogUrl && (<p>
                        Want to checkout our catalog of events? Go to <a className="link" href={env.catalogUrl}>this link</a>
                    </p>)
                }

                {
                    !env.catalogUrl && (<p>
                        See more from <a href="/" className="link">Serene Events</a>
                    </p>)
                }
                
            </div>
        </div>)

    }

    return <div>


    </div>

};


const AcceptPayemntStep: FC<{amount: number; bookingReference: string; verificationCode: string}> = (props) => {
    const [payment, setPayment] = useState({complete: false, reference: '', amount: 0});
    const {customer} = useContext(BookingContext).data;
    const stepCtx = useContext(StepContext);
    if (payment.complete) {
        return <PostPaymentStep  payment={payment} bookingCode={props.bookingReference} verificationCode={props.verificationCode}/>
    }
    return  <PaymentForm amount={props.amount} onComplete={(p) => {
        setPayment({complete: true, reference: p.reference, amount: p.amount});
    }} customer={customer!} onCancel={() => {
        stepCtx.onChange(0);
    }} />
};




export default function Step3() {
    const tier  = useTier();
    if (!tier) {
        return <p>
            See more from <a href="/" className="link">Serene Events</a>
        </p>;
    }
    return <SubmitStep />
}