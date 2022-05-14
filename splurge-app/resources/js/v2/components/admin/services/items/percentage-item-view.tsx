import React, {forwardRef, ForwardedRef} from "react";
import { ServiceItemViewProps } from "./types";
import classNames from "classnames";

export default forwardRef((props: ServiceItemViewProps, ref: ForwardedRef<any>) => {
    const {item, onDeleteRequested, onEditRequested, draggableHandleProps, draggableProps, className} = props;
    return (<div ref={ref} {...draggableProps} {...draggableHandleProps} className={classNames(className, 'flex flex-row justify-between')}>
    <div className="col-span-1">
        <h4 className="text-lg">
            {item.name}
        </h4>
        <span className="text-gray-500 block">
            ({item.category})
        </span>
    </div>
    <div className="text-right">
        Percentage rate:
        <h4 className="text-xl">
            {item.options?.rate}%
        </h4>
        <p>
            On {item.options?.base} items
        </p>
        <p className="mt-4">
            <a className="link mr-8" onClick={(e) => onEditRequested(item)}>
                Edit
            </a>
            <a className="link danger" onClick={(e) => onDeleteRequested(item)}>
                Delete
            </a>
        </p>
    </div>
</div>)


})