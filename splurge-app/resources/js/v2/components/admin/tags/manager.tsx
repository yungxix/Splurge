import React, { FC, useState, useEffect } from "react";

import Selector, { SelectableTag } from './selector';

import axios from '../../../../axios-proxy';

enum Status {
    idle = 0,
    loading = 1,
    loaded = 2,
    failed = -1,
    updating = 3,
}


interface Taggable {
    id: number;
    type: string;
}
export interface ManagerProps {
    taggable: Taggable,
    baseURL: string;
    indexUrl?: string;
}

const fetchTags = async (url: string, taggable: Taggable): Promise<SelectableTag[]> => {
    const resp = await axios.get<{ data: SelectableTag[] }>(url, {
        params: {
            attachment: '1',
            attachments: '1',
            type_name: taggable.type,
            type_id: String(taggable.id)
        }

    });
    return resp.data.data;
};

const attach = async (tag: SelectableTag, options: { baseUrl: string; taggable: Taggable }) => {
    const resp = await axios.post(`${options.baseUrl}/${tag.id}/attach`, {
        taggable: options.taggable
    });

    return resp.data;
};


const detach = async (tag: SelectableTag, options: { baseUrl: string; taggable: Taggable }) => {
    const resp = await axios.delete(`${options.baseUrl}/${tag.id}/attach`, {
        params: {
            'taggable[id]': options.taggable.id,
            'taggable[type]': options.taggable.type,
        }
    });
    return resp.data;
};

const Manager: FC<ManagerProps> = (props) => {
    const [tags, setTags] = useState<SelectableTag[]>([]);

    const [status, setStatus] = useState(Status.idle);

    useEffect(() => {
        setStatus(Status.loading);
        fetchTags(props.baseURL, props.taggable)
            .then((tags) => {
                setTags(tags);
                setStatus(Status.loaded);
            }, (err) => {
                setStatus(Status.failed);
            });
    }, []);


    const handleSelect = async (tag: { id: number }) => {
        const t = tags.find(u => u.id === tag.id);
        if (!t) {
            return;
        }
        try {
            await attach(t, { baseUrl: props.baseURL, taggable: props.taggable });
            setTags(tags.map((u) => {
                if (u.id === tag.id) {
                    return { ...u, attached: true };
                }
                return u;
            }))
        } catch (error) {

        }
    };

    const handleDelete = async (tag: { id: number }) => {
        const t = tags.find(u => u.id === tag.id);
        if (!t) {
            return;
        }
        try {
            await detach(t, { baseUrl: props.baseURL, taggable: props.taggable });
            setTags(tags.map((u) => {
                if (u.id === tag.id) {
                    return { ...u, attached: false };
                }
                return u;
            }))
        } catch (error) {

        }
    };


    return <div>
        {
            status > Status.idle && (<div className="flex flex-row items-center justify-end bg-white">
                <span className="text-gray-700 mr-8">Tags:</span>
                <Selector tags={tags} inputName="tags" onSelect={handleSelect} onRemove={handleDelete} />
            </div>)
        }

    </div>

}

export default Manager;