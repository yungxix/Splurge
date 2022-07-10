import React, { FC, useState, useCallback, Fragment, useMemo, useEffect } from "react";

import axios from 'axios';
import debounce from 'lodash/debounce';

import MediaItemRenderer from '../media-item';

import {
    Dialog,
    Transition
} from "@headlessui/react";

import { MediaItem } from "../gallery/types";

import SplurgeModal from "../modal";

enum BrowseState {
    loading = 1,
    pending = 0,
    loaded = 2,
    error = -1
}


type PreferredInputType = 'file' | 'select';


interface MediumResponse {
    data: MediaItem[];
    links: {
        next: string | null;
        prev: string | null;
    },
    meta: {
        current_page: number;
        from: number | null;
        to: number | null;
        total: number;
        per_page: number;
        last_page: number | null;
    }
}

export interface MediumSelectorProps {
    fileInputName: string;
    captionInputName: string;
    mediumInputName: string;
}


const browseMedia = async (options: { search: string; page: number }): Promise<MediumResponse> => {
    const resp = await axios.get<MediumResponse>('/admin/media', {
        params: {
            page: String(options.page),
            q: options.search
        },
        headers: {
            'Accept': 'application/json'
        }
    });

    return resp.data;
};

const defaultMediaList = (): MediumResponse => ({
    data: [],
    links: { next: null, prev: null },
    meta: {
        current_page: 0,
        total: 0,
        from: null,
        to: null,
        per_page: 0,
        last_page: 0
    }
});

const MediaBrowser: FC<{ show: boolean; onSelect: (item: MediaItem) => void; onClose: () => void }> = (props) => {
    const [state, setState] = useState(BrowseState.pending);
    const [response, setResponse] = useState(defaultMediaList());
    const [search, setSearch] = useState('');

    const fetchPage = useCallback((page: number, search: string) => {
        setState(BrowseState.loading);
        browseMedia({ search, page }).then((resp) => {
            setResponse(resp);
            setState(BrowseState.loaded);
        }, (err) => {
            setState(BrowseState.error);
        })
    }, []);

    const debouncedSearch = useMemo(() => {
        return debounce(async (term: string) => {
            fetchPage(1, term);
        }, 300);
    }, []);



    useEffect(() => {
        if (BrowseState.pending === state) {
            fetchPage(1, search);
        }
    }, []);

    const hasNext = response.meta.last_page &&
        response.meta.current_page < response.meta.last_page;

    const hasPrev = response.meta.current_page > 1;

    const footerView = <div className="flex flex-row justify-between">
    {
        hasPrev && (<a className="link" onClick={(e) => {
            fetchPage(response.meta.current_page - 1, search)
        }}>
            &larr;
        </a>)
    }
    {
        !hasPrev && (<span className="block text-center text-gray-800 cursor-not-allowed">
            &larr;
        </span>)
    }

    {
        hasNext && (<a className="link" onClick={(e) => {
            fetchPage(response.meta.current_page + 1, search)
        }}>
            &rarr;
        </a>)
    }
    {
        !hasNext && (<span className="block text-center text-gray-800 cursor-not-allowed">
            &rarr;
        </span>)
    }
</div>

    return <SplurgeModal footer={footerView} show={props.show}
         onClose={props.onClose} title="Browse pictures">
        <div>
                                    <input type="search" value={search} onChange={(e) => {
                                        setSearch(e.target.value)
                                        debouncedSearch(e.target.value)
                                    }} />
                                </div>
                                <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                    {
                                        response.data.map((item) => (<a key={item.id} onClick={(e) => props.onSelect(item)} className="block ring ring-gray-200 hover:ring-splarge-700 active:ring-splarge-700">
                                            <MediaItemRenderer item={item} thumbnailPreferred={true} />
                                        </a>))
                                    }
                                </div>
    </SplurgeModal>

};




const MediumSelector: FC<MediumSelectorProps> = (props) => {
    const [showBrowser, setShowBrowser] = useState(false);
    const [inputType, setInputType] = useState<PreferredInputType>('file');
    const [selectedMedia, setSelectedMedia] = useState<MediaItem>();
    const [imageCaption, setImageCaption] = useState<string>();



    return <div>
        {
            'file' === inputType && (<div className="mb-4">
                <input type="file" accept="image/*" name={props.fileInputName} />

                {
                    props.captionInputName && (<div>
                        <label htmlFor={props.captionInputName} className="block mb-2 text-gray-800">
                            Name/caption of image
                        </label>
                        <input type="text" name={props.captionInputName} value={imageCaption} onChange={(e) => {
                            setImageCaption(e.target.value)
                        }} className="control w-full" />
                    </div>)
                }
                <div>
                    <button type="button" className="btn" onClick={(e) => {
                        setInputType('select');
                        setShowBrowser(true);
                    }}>
                        Choose an existing picture
                    </button>
                </div>
            </div>)
        }

        {
            'select' === inputType && (<div>
                {
                    selectedMedia && (<div>
                        <input type="hidden" name={props.mediumInputName} value={selectedMedia.id} />
                        <figure>
                            <img src={selectedMedia.thumbnail_url || selectedMedia.url} />
                            <figcaption>
                                {selectedMedia.name}
                            </figcaption>
                        </figure>
                    </div>)
                }
                <div className="flex flex-row items-center gap-x-4">
                    {
                        selectedMedia && !showBrowser && (
                            <button type="button" className="btn" onClick={(e) => {
                                setShowBrowser(true);
                            }}>
                                Change
                            </button>
                        )
                    }

                    <button type="button" className="btn" onClick={(e) => {
                        setInputType('file');
                    }}>
                        Upload new picture
                    </button>
                </div>
            </div>)
        }
        <MediaBrowser show={showBrowser} onSelect={(me) => {
            setSelectedMedia(me);
            setShowBrowser(false);
        }} onClose={() => {
            setShowBrowser(false);
        }} />

    </div>
}


export default MediumSelector;