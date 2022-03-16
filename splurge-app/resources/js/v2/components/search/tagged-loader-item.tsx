import React, {FC, useState, useEffect} from "react";

import axios from "axios";

enum LoadState {
    loading = 1,
    pending = 0,
    loaded = 4,
    error = -1
};

export interface ItemProps {
    query?: string;
    tag: string;
    type: string;
    onLoad: (opts: {empty: boolean}) => void;
    showLoading: boolean;
}


const ItemRenderer: FC<ItemProps> = (props) => {
    const [content, setContent] = useState("");
    const [state, setState] = useState(LoadState.pending);

    useEffect(() => {
        setState(LoadState.loading);
        axios.get<string>('/search/tagged', {
            params: {
                tag: props.tag,
                q: props.query,
                type: props.type
            },
            headers: {
                'Accept': 'text/html'
            }
        }).then((resp) => {
            setState(LoadState.loaded);
            const c = resp.data || '';
            setContent(c);
            props.onLoad({empty: c.length < 10});
        }, (err) => {
            setState(LoadState.error);
            props.onLoad({empty: true});
        });
    }, [props.tag, props.type, props.query]);

    if (state === LoadState.error || (state === LoadState.loaded && !content)) {
        return null;
    }

    return <div>
        {
            props.showLoading && state === LoadState.loading && (<div className="flex flex-row justify-center p-10">
                <svg xmlns="http://www.w3.org/2000/svg" className="animate-spin h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>)
        }

        <div className="search_content" dangerouslySetInnerHTML={{__html: content}}>


        </div>
        {
            state ===  LoadState.loaded && (<div>
                {props.children}
            </div>)
        }
    </div>
};

export default ItemRenderer;