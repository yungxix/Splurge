import React, {forwardRef, ForwardedRef} from "react";
import { ServiceItemViewProps } from "./types";
import IncrementalView from './incremental-item-view';
import PercentageView from './percentage-item-view';
import FixedView from './fixed-item-view';


export default forwardRef((props: ServiceItemViewProps, ref:  ForwardedRef<any>) => {
    switch (props.item.pricing_type) {
        case 'incremental':
            return <IncrementalView ref={ref} {...props} />;
        case 'percentage':
            return <PercentageView ref={ref} {...props} />;
        default:
            return <FixedView ref={ref} {...props} />

    }

})
