import React, {FC, useMemo, useState} from "react";
import Amount from "../../amount";
import Lines from "../../lines";
import {Booking} from "../../admin/booking/details/types";
import { parseISO, format, formatDistanceToNow } from "date-fns";
import axios from '../../../../axios-proxy';
import DynamicEditable from "../../editable/dynamic";
import * as yup from 'yup';

import {
    LocationMarkerIcon,
    CalendarIcon,
    IdentificationIcon,
    CashIcon,
    UserIcon
} from "@heroicons/react/outline";
import { ExtendedBooking } from "./types";

const IconWrapper: FC = (props) => (<div className="rounded-full bg-splarge-800 w-12 p-2 flex flex-col justify-center items-center">
    {props.children}
</div>);

const ICON_CLASS = 'w-8 h-8 text-white';

export interface BookingDetailsProps {
    booking: ExtendedBooking;
    url: string;
    children?: any;
    className?: string;
    editable?: boolean;
}

const emailValidator = yup.string().required().email();

const phoneValidator = yup.string().required().matches(/^\d+([\s\-\d])*\d+$/);

const descriptionValidation = yup.string().required().max(255);

const customerNameValidator = yup.string().required().max(30 * 2 + 2).matches(/\w+\s+\w+/);

export default function BookingDetails(props: BookingDetailsProps) {
    const [currentBooking, setCurrentBooking] = useState(props.booking);

    const eventDate = useMemo(() => parseISO(currentBooking.event_date), [currentBooking.event_date])

    const createdOn = useMemo(() => parseISO(props.booking.created_at), [props.booking.created_at]);

    const updateBooking = async (payload: Record<string, any>) => {
        const {data} = await axios.patch<{data: Partial<ExtendedBooking>}>(props.url, payload);
        const result = {...currentBooking, ...data.data};
        setCurrentBooking(result);    
    };

    if (props.editable === false) {
        return <div className="flex flex-col bg-white p-4 rounded">
        <p className="text-right">
            Posted {formatDistanceToNow(createdOn, {addSuffix: true})}
        </p>
        {props.children}
        <table className="w-full border-collapse">
            <tbody>
                <tr className="align-top">
                    <td className="py-4 font-bold">
                            <IconWrapper>
                                <CalendarIcon className={ICON_CLASS} />
                            </IconWrapper>
                    </td>
                    <td className="px-4 py-4 text-gray-800">
                            {format(eventDate, 'EEEE MMM d, yyyy')}
                            <span className="block">
                                ({formatDistanceToNow(eventDate, {addSuffix: true})})
                            </span>
                    </td>
                </tr>
                {
                    currentBooking.current_charge && (<tr  className="align-top">
                        <td className="py-4 font-bold">
                            <IconWrapper>
                                <CashIcon className={ICON_CLASS} />
                            </IconWrapper>
                        </td>
                        <td className="px-4 py-4 text-gray-800">
                           <Amount value={currentBooking.current_charge} />
                        </td>
                    </tr>)
                }
                <tr className="align-top">
                    <td className="py-4 font-bold">
                            <IconWrapper>
                                <IdentificationIcon className={ICON_CLASS} />
                            </IconWrapper>
                    </td>
                    <td className="px-4 py-4 whitespace-wrap text-gray-800">
                    <Lines text={currentBooking.description} />
                    </td>
                </tr>
                
                <tr className="align-top">
                    <td className="py-4 font-bold">
                        <IconWrapper>
                            <UserIcon className={ICON_CLASS} />
                        </IconWrapper>
                    </td>
                    <td className="px-4 py-4 text-gray-800">
                        <div className="sm:mb-4">
                        {currentBooking.customer.first_name} {currentBooking.customer.last_name}
                        </div>

                        <div className="md:flex flex-row flex-wrap items-center gap-x-5">
                            <div>
                                Email: <span>
                                        {currentBooking.customer.email}
                                    </span>
                            </div>
                            <div>
                            Phone: <span>
                                        {currentBooking.customer.phone}
                                    </span>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr className="align-top">
                    <td className="py-4 font-bold">
                    <IconWrapper>
                        <LocationMarkerIcon className={ICON_CLASS} />
                    </IconWrapper>
                    </td>
                    <td className="px-4 py-4 text-gray-800">
                        <address>
                            {currentBooking.location.name && (<strong className="block">{currentBooking.location.name}</strong>)}
                            {currentBooking.location.line1}<br />
                            {currentBooking.location.line2}<br />
                            {currentBooking.location.state}
                            {currentBooking.location.zip && (<span className="ml-2">{currentBooking.location.zip}</span>)}
                        </address>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
    }

    

    return <div className="flex flex-col bg-white p-4 rounded">
        <p className="text-right">
            Posted {formatDistanceToNow(createdOn, {addSuffix: true})}
        </p>
        {props.children}
        <table className="w-full border-collapse">
            <tbody>
                <tr className="align-top">
                    <td className="py-4 font-bold">
                            <IconWrapper>
                                <CalendarIcon className={ICON_CLASS} />
                            </IconWrapper>
                    </td>
                    <td className="px-4 py-4 text-gray-800">
                            {format(eventDate, 'EEEE MMM d, yyyy')}
                            <span className="block">
                                ({formatDistanceToNow(eventDate, {addSuffix: true})})
                            </span>
                    </td>
                </tr>
                {
                    currentBooking.current_charge && (<tr  className="align-top">
                        <td className="py-4 font-bold">
                            <IconWrapper>
                                <CashIcon className={ICON_CLASS} />
                            </IconWrapper>
                        </td>
                        <td className="px-4 py-4 text-gray-800">
                           <Amount value={currentBooking.current_charge} />
                        </td>
                    </tr>)
                }
                <tr className="align-top">
                    <td className="py-4 font-bold">
                            <IconWrapper>
                                <IdentificationIcon className={ICON_CLASS} />
                            </IconWrapper>
                    </td>
                    <td className="px-4 py-4 whitespace-wrap text-gray-800">
                        <DynamicEditable  alignment="vertical" as="textarea" prompt="Edit description" 
                        validation={(e) => {
                            try {
                                descriptionValidation.validateSync(e);
                                return null;
                            } catch (error) {
                                const te = error as yup.ValidationError;
                                return te.errors.join(", ");
                            }
                        }}
                        value={currentBooking.description} onChange={(e) => {
                            return updateBooking({
                                description: e
                            })
                        }}>
                            <Lines text={currentBooking.description} />
                        </DynamicEditable>
                        
                    </td>
                </tr>
                
                <tr className="align-top">
                    <td className="py-4 font-bold">
                        <IconWrapper>
                            <UserIcon className={ICON_CLASS} />
                        </IconWrapper>
                    </td>
                    <td className="px-4 py-4 text-gray-800">
                        <div className="sm:mb-4">
                            <DynamicEditable alignment="vertical" as="text" inputClassName="block control w-full" placeholder="Firstname Lastname" prompt="Edit full name" 
                            value={`${currentBooking.customer.first_name} ${currentBooking.customer.last_name}`} onChange={(fullName) => {
                                const [first_name, last_name] = String(fullName).split(/\s+/);
                                return updateBooking({
                                    customer: {
                                        first_name,
                                        last_name
                                    }
                                });
                            }} validation={(fullName) => {
                                try {
                                    customerNameValidator.validateSync(fullName);
                                    return null;
                                } catch (error) {
                                    return (error as yup.ValidationError).message;
                                }
                            }}>
                                {currentBooking.customer.first_name} {currentBooking.customer.last_name}
                            </DynamicEditable>

                        </div>

                        <div className="md:flex flex-row flex-wrap items-center gap-x-5">
                            <div>
                            <DynamicEditable alignment="vertical" as="email" prompt="Edit email address" onChange={(email) => {
                                return updateBooking({
                                    customer: {
                                        email
                                    }
                                })
                            }} value={currentBooking.customer.email} validation={(email) => {
                                try {
                                    emailValidator.validateSync(email);
                                    return null;
                                } catch (error) {
                                    const te = error as yup.ValidationError;
                                    return te.message;
                                }
                            }}>
                                    E: <span>
                                        {currentBooking.customer.email}
                                    </span>
                            </DynamicEditable>
                            </div>
                            <div>
                            <DynamicEditable  alignment="vertical" as="phone" prompt="Edit phone number" onChange={(phone) => {
                                return updateBooking({
                                    customer: {
                                        phone
                                    }
                                })
                            }} value={currentBooking.customer.phone} validation={(phone) => {
                                try {
                                    phoneValidator.validateSync(phone);
                                    return null;
                                } catch (error) {
                                    const te = error as yup.ValidationError;
                                    return te.message;
                                }
                            }}>
                                    Phone: <span>
                                        {currentBooking.customer.phone}
                                    </span>
                            </DynamicEditable>
                            </div>
                           


                           
                        </div>

                    </td>
                </tr>
                <tr className="align-top">
                    <td className="py-4 font-bold">
                    <IconWrapper>
                        <LocationMarkerIcon className={ICON_CLASS} />
                    </IconWrapper>
                    </td>
                    <td className="px-4 py-4 text-gray-800">
                        <address>
                            {currentBooking.location.name && (<strong className="block">{currentBooking.location.name}</strong>)}
                            {currentBooking.location.line1}<br />
                            {currentBooking.location.line2}<br />
                            {currentBooking.location.state}
                            {currentBooking.location.zip && (<span className="ml-2">{currentBooking.location.zip}</span>)}
                        </address>
                    </td>
                </tr>
                
            </tbody>
        </table>
    </div>
}

