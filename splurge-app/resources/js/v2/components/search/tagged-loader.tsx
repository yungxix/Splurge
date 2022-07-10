import React, {FC, useState} from "react";

import ItemLoader from './tagged-loader-item';

export interface LoaderProps {
    types: string[];
    query: string;
    tag: string;
    onNoContent: () => void;
}

const Loader: FC<LoaderProps> = (props) => {
    const [emptyCount, setEmptyCount] = useState(0);

    const handleLoad = (opts: {empty: boolean}) => {
        if (opts.empty) {
            setEmptyCount(emptyCount + 1);
        }
    };
    return <div>
        {
            props.types.map((type, i) =>
             (<ItemLoader showLoading={i === 0} key={`${type}_loader`} 
             type={type} tag={props.tag}
              query={props.query} onLoad={handleLoad} />))
        }
        {
            emptyCount === props.types.length && (<div>
                <p className="text-center py-12">
                    <em>
                        No result was found
                    </em>
                </p>
            </div>)
        }
    </div>

}

export default Loader;