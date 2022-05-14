import React, { forwardRef, ForwardedRef } from "react";
import { ServiceItemViewProps } from "./types";
import Amount from "../../../amount";
import classNames from "classnames";

export default forwardRef((props: ServiceItemViewProps, ref: ForwardedRef<HTMLDivElement>) => (<div ref={ref} {...props.draggableProps} {...props.draggableHandleProps} className={classNames(props.className, 'flex flex-row justify-between')}>
    <div className="col-span-1">
        <h4 className="text-lg">
            {props.item.name}
        </h4>
        <span className="text-gray-500 block">
            ({props.item.category})
        </span>
    </div>
    <div className="text-right">
        Fixed amount:
        <h4 className="text-xl">
            <Amount value={props.item.price} />
        </h4>
        {
            props.item.required && (<span className="mt-2 px-2 py-1 uppercase rounded bg-green-800 text-white text-xs">
                required
            </span>)
        }
        <p className="mt-4">
            <a className="link mr-8" onClick={(e) => props.onEditRequested(props.item)}>
                Edit
            </a>
            <a className="link danger" onClick={(e) => props.onDeleteRequested(props.item)}>
                Delete
            </a>
        </p>
    </div>
</div>))
