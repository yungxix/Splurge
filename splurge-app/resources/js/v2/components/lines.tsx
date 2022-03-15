import React, {FC, useMemo} from "react";
export interface LinesProps {
    text: string;
    className?: string;
}

const Lines: FC<LinesProps> = (props) => {
    const lines = useMemo(() => {
        if (!props.text) {
            return [];
        }
        return props.text.split(/\n/g);
    }, [props.text]);


    return <>
        {
            lines.map((line, index) => (<p key={index} className={props.className || ''}>
                {line}
            </p>))
        }
    </>

};

export default Lines;