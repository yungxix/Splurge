import React from "react";
import isDev from "../../is_dev";

export default function InDevelopment(props: {children: any}) {
    if (isDev()) {
        return <>
            {props.children}
        </>
    }
    return null;
}