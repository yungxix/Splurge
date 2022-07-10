import React, {FC, forwardRef, ForwardedRef} from "react";
import { ServiceItemViewProps } from "./types";
import Amount from "../../../amount";
import classNames from "classnames";


export default forwardRef((props: ServiceItemViewProps, ref: ForwardedRef<HTMLDivElement>) => {

    const {item, onDeleteRequested, onEditRequested, draggableHandleProps, draggableProps, className} = props;

    return (<div ref={ref} {...draggableProps} {...draggableHandleProps} className={classNames(className, 'lex flex-row justify-between')}>
    <div className="col-span-1">
        <h4 className="text-lg">
            {item.name}
        </h4>
        <span className="text-gray-500 block">
            ({item.category})
        </span>
    </div>
    <div className="text-right">
        <h4 className="text-xl">
            <Amount value={item.price} /> each
        </h4>
        <p>
            {
                (item.options?.minimum > 0)  && (<>
                From <Amount value={item.options?.minimum * item.price} />
                {" "}to{" "} <Amount value={item.options?.maximum * item.price} />
                </>)
            }

            {
                (item.options?.minimum <= 0) && (<>
                Up to <Amount value={item.options?.maximum * item.price} />
                </>)
            }
            
        </p>
        {
            item.options?.prompt && (<p>
                Prompt: <em className="text-splarge-700 text-sm">
                {item.options.prompt}
                </em>
            </p>)
        }
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

});
