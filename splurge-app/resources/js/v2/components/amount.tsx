import React, {FC} from "react";

const formatter = new Intl.NumberFormat("en", {minimumFractionDigits: 2, useGrouping: true});

export default function Amount (props: {value: number; className?: string}) {
    return <span className={props.className || ''}>
        &#8358;{formatter.format(props.value)}
    </span>
}