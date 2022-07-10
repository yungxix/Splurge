import React, {FC, useMemo, useState} from "react";
import Amount from "../../../amount";
import Lines from "../../../lines";
import {Booking, Payment} from "./types";
import EditableDate from "../../../editable/editable-date";
import axios from "../../../../../axios-proxy";
import { parseISO, formatISO, format, formatDistanceToNow } from "date-fns";
import sumBy from "lodash/sumBy";
import toNumber from 'lodash/toNumber';



import {
    LocationMarkerIcon,
    CalendarIcon,
    IdentificationIcon,
    CashIcon,
    UserIcon,
    RefreshIcon,
    CheckIcon,
    XIcon
} from "@heroicons/react/outline";

import { TableView, Column } from '../../../table';
import { PencilIcon } from "@heroicons/react/solid";

declare const PRODUCTION: boolean;

const IconWrapper: FC = (props) => (<div className="rounded-full bg-splarge-800 w-12 p-2 flex flex-col justify-center items-center">
    {props.children}
</div>);

const ICON_CLASS = 'w-8 h-8 text-white';

const paymentColumns: Array<Column<Payment>> = [
    {
        text: "#",
        render: (p) => <em>{p.code}</em>,
        keyOf: (p) => 'code_' + p.id
    },{
        text: "Statement",
        render: (p) => <span>{p.statement}</span>,
        keyOf: (p) => 'stm_' +  p.id
    },{
        text: "Date",
        render: (p) => <span>{format(parseISO(p.created_at), 'MMM d, yyyy h:mm a')}</span>,
        keyOf: (p) => 'date_' +  p.id
    },{
        text: "Amount",
        render: (p) => <Amount value={p.amount} />,
        keyOf: (p) => 'amount_' +  p.id
    }

];

export interface BookingDetailsProps {
    booking: Booking;
    url: string;
}

type AmountChangeCallback = (amount: number) => void | Promise<any>;

enum PriceChangeStatus {
    normal = 0,
    take = 1,
    changing = 2,
    saving = 4,
    error = -1
}

const NUM_PATTERN = /^\d+$/;


const PriceView: FC<{booking: Booking, onChange: AmountChangeCallback}> = (props) => {
    const [status, setStatus] = useState(PriceChangeStatus.normal);
    const [newPrice, setNewPrice] = useState(0);

    const commit = () => {
        setStatus(PriceChangeStatus.saving);
        const result = props.onChange(newPrice);
        if (result) {
            result.then((r) => {
                setStatus(PriceChangeStatus.normal);
            }, (err) => {
                if (!PRODUCTION) {
                    console.error(err);
                }
                setStatus(PriceChangeStatus.error);
            });
        } else {
            setStatus(PriceChangeStatus.normal);
        }
    };

    const cancel = () => setStatus(PriceChangeStatus.normal);

    if (status === PriceChangeStatus.saving) {
        return <RefreshIcon className="w-6 h-6 animate-spin" />
    }

    if (status === PriceChangeStatus.error) {
        return <div className="flex items-center flex-row justify-between">
            <p className="text-red-700">
                An error occured while trying to save price of booking
            </p>
            <a className="text-red-700" onClick={(e) => setStatus(PriceChangeStatus.normal)}>
                <XIcon className="w-4 h-4" />
            </a>
        </div>
    }

    if (status === PriceChangeStatus.changing) {
        return <div className="flex items-center flex-row">
            <p>
                This price cannot be changed after you save it. Do you want to continue?
            </p>
            <a onClick={commit} className="mx-4 text-green-700 cursor-pointer hover:text-green-900">
                Yes
            </a>
            <a onClick={cancel} className="text-red-700 cursor-pointer hover:text-red-900">
                Not yet
            </a>
        </div>
    }

    if (status === PriceChangeStatus.take) {
        return <div className="flex items-center flex-row flex-wrap">
            <label className="text-gray-500 mr-2">Set booking price:</label>
            <input type="text" className="control" size={7} placeholder="Booking price"
            value={newPrice} onChange={(e) => {
                if (NUM_PATTERN.test(e.target.value)) {
                    setNewPrice(parseFloat(e.target.value))
                }
            }} />
            <button type="button" onClick={(e) => setStatus(PriceChangeStatus.changing)} className="btn mx-4" disabled={newPrice < 10}>
                <CheckIcon className="w-4 h-4" />
            </button>
            <button type="button" onClick={cancel} className="btn">
                <XIcon className="w-4 h-4" />
            </button>
        </div>
    }


    if (props.booking.current_charge && props.booking.current_charge > 0) {
        return <Amount value={props.booking.current_charge} />
    }

    return <div className="flex flex-row items-center">
        <em>You have not set a price on this booking.</em>
        <a onClick={(e) => setStatus(PriceChangeStatus.take)} className="ml-8 link">
            <PencilIcon className="w-4 h-4" />
        </a>
    </div>


};


export default function BookingDetails(props: BookingDetailsProps) {
    const [currentBooking, setCurrentBooking] = useState(props.booking);

    const eventDate = useMemo(() => parseISO(currentBooking.event_date), [currentBooking.event_date])

    const createdOn = useMemo(() => parseISO(props.booking.created_at), [props.booking.created_at]);

    const updateBooking = async (payload: Record<string, any>) => {
        const {data} = await axios.patch<{data: Partial<Booking>}>(props.url, payload);
        const result = {...currentBooking, ...data.data};
        setCurrentBooking(result);    
    };

    const totalPayment = useMemo(() => {
        if (!currentBooking.payments) {
            return 0;
        }
        return sumBy(currentBooking.payments,x => toNumber(x.amount));

    }, [currentBooking.payments]);

    

    return <div className="flex flex-col">
        <p className="text-right">
            Posted {formatDistanceToNow(createdOn, {addSuffix: true})}
        </p>
        <table className="w-full border-collapse">
            <tbody>
                <tr className="align-top">
                    <td className="py-4 whitespace-nowrap font-bold">
                        Booking #
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap font-bold text-gray-800">
                        {currentBooking.code}
                    </td>
                </tr>
                <tr className="align-top">
                    <td className="py-4 whitespace-nowrap font-bold">
                            <IconWrapper>
                                <CalendarIcon className={ICON_CLASS} />
                            </IconWrapper>
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap text-gray-800">
                        <EditableDate value={eventDate} onChange={(e) => {
                            return updateBooking({
                                event_date: e ? formatISO(e) : null
                            })
                        }}>
                            {format(eventDate, 'EEEE MMM d, yyyy')}
                            <span className="ml-8">
                                ({formatDistanceToNow(eventDate, {addSuffix: true})})
                            </span>
                        </EditableDate>
                    </td>
                </tr>
                <tr  className="align-top">
                    <td className="py-4 whitespace-nowrap font-bold">
                        <IconWrapper>
                            <CashIcon className={ICON_CLASS} />
                        </IconWrapper>
                    </td>
                    <td className="px-4 py-4">
                        <PriceView booking={currentBooking} onChange={(amt) => updateBooking({price: amt})} />
                    </td>
                </tr>
                <tr className="align-top">
                    <td className="py-4 whitespace-nowrap font-bold">
                            <IconWrapper>
                                <IdentificationIcon className={ICON_CLASS} />
                            </IconWrapper>
                    </td>
                    <td className="px-4 py-4 whitespace-wrap text-gray-800">
                        <Lines text={currentBooking.description} />
                    </td>
                </tr>
                
                <tr className="align-top">
                    <td className="py-4 whitespace-nowrap font-bold">
                        <IconWrapper>
                            <UserIcon className={ICON_CLASS} />
                        </IconWrapper>
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap text-gray-800">
                        <p>
                            {currentBooking.customer.first_name} {currentBooking.customer.last_name} <br />

                            E: <a className="link" href={`mailto:${currentBooking.customer.email}`}>
                                {currentBooking.customer.email}
                            </a>
                            <span className="ml-12">P:</span>
                            <a className="link" href={`tel:${currentBooking.customer.phone}`}>
                                {currentBooking.customer.phone}
                            </a>
                        </p>
                    </td>
                </tr>
                <tr className="align-top">
                    <td className="py-4 whitespace-nowrap font-bold">
                    <IconWrapper>
                        <LocationMarkerIcon className={ICON_CLASS} />
                    </IconWrapper>
                    </td>
                    <td className="px-4 py-4 whitespace-nowrap text-gray-800">
                        <address>
                            {currentBooking.location.name && (<strong className="block">{currentBooking.location.name}</strong>)}
                            {currentBooking.location.line1}<br />
                            {currentBooking.location.line2}<br />
                            {currentBooking.location.state}
                            {currentBooking.location.zip && (<span className="ml-2">{currentBooking.location.zip}</span>)}
                        </address>
                    </td>
                </tr>
                {
                    currentBooking.payments && currentBooking.payments.length > 0 && (
                        <tr className="align-top">
                            <td colSpan={2}>
                               <TableView columns={paymentColumns} footer={<tr>
                                   <td colSpan={paymentColumns.length} className="text-right pr-6 font-bold">
                                     Total: <Amount className="ml-8" value={totalPayment} />
                                   </td>
                               </tr>} records={currentBooking.payments} caption="Payments"></TableView>
                            </td>
                        </tr>
                    )
                }

                
                
            </tbody>
        </table>
        {
            currentBooking.payments && currentBooking.payments.length === 0 &&
                (<div className="my-8 rounded-md shadow-md p-4 bg-red-300">
                    <p className="text-center">
                    <em>
                        No payment has been recorded
                    </em>
                    </p>

            </div>)
        }
    </div>
}

