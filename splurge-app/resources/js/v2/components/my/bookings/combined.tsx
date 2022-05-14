import React, {FC, useState} from "react";
import ResponsiveLayout from "../../responsive-layout";
import { ExtendedBooking } from "./types";
import DetailsView from './main';
import ServiceView from './service';
import { Transition } from "@headlessui/react";


export interface BookingDetailsProps {
    booking: ExtendedBooking;
    url: string;
    editable?: boolean;
}

const DesktopView: FC<BookingDetailsProps> = (props) => {
    return <div className="flex flex-row">
        <div className="w-3/5">
            <DetailsView {...props} />
        </div>
        <div className="w-2/5 pl-4">
            <ServiceView tier={props.booking.service_tier} />
        </div>
    </div>
};


const MobileView: FC<BookingDetailsProps> = (props) => {
    const [showService, setShowService] = useState(false);

    const toggle = () => setShowService(!showService)

    return <div>
        <Transition show={showService}>
            <div className="bg-white rounded p-4">
                <p className="text-right mb-4">
                    <a className="link" onClick={toggle}>
                        Close
                    </a>
                </p>
                <ServiceView tier={props.booking.service_tier} />
            </div>
        </Transition>
        <Transition show={!showService}>
            <DetailsView {...props}>
                <a className="link mb-8" onClick={toggle}>
                    <h4 className="text-xl font-bold">
                        {props.booking.service_tier.service.name} Service&hellip;
                    </h4>
                </a>
            </DetailsView>
        </Transition>
    </div>
};

export default function BookingCombinedView(props: BookingDetailsProps) {
    return <ResponsiveLayout>
        {(env) => {
            if (env.mobile) {
                return <MobileView {...props} />
            }
            return <DesktopView  {...props} />
        }}
    </ResponsiveLayout>
}