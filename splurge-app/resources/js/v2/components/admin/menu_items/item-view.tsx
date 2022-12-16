import { CheckIcon, PencilIcon, RefreshIcon, TrashIcon, XIcon } from "@heroicons/react/solid";
import React, {PropsWithChildren, useState} from "react";
import { useMenuItemCommiter, useMenuItemDeleter } from "./hooks";

import { MenuItem } from "./types";

export default function MenuItemView(props: PropsWithChildren<{
    value: MenuItem,
    onDeleteRequested: (item: MenuItem) => void;
}>) {
    const [name, setName] = useState(props.value.name);
    const [oldName, setOldName] = useState(props.value.name);
    const [commit, saving, error] = useMenuItemCommiter();
    const [remove, removing, removeError] = useMenuItemDeleter(props.onDeleteRequested);
    const [edting, setEditing] = useState(false);

    const doEdit = () => setEditing(true);

    const doDelete = () => {
        remove(props.value);
        
    };

    const doSave = async () => {
        const item = await commit({
            id: props.value.id,
            name
        });    
        if (item) {
            setOldName(name);
            setEditing(false);
        }
    };

    const doCancel = () => {
        setName(oldName);
        setEditing(false);
    };

    const errorRender =  <>
        {error && <p className="text-red-600 my-2"><em>{error}</em></p>}
        {removeError && <p className="text-red-600 my-2"><em>{removeError}</em></p>}
    </>

    if (saving || removing) {
        return <tr>
            <td className="py-4 px-6">
                {errorRender}
                <RefreshIcon className="w-4 h-4 animate-spin" />
                {props.children}
            </td>
            <td className="py-4">

            </td>
        </tr>
    }
    if (edting) {
        return <tr>
            <td className="py-4 px-6">
                {errorRender}
                <input type="text" value={name} onChange={e => setName(e.target.value)} className="control w-full"
                    
                />
            </td>
            <td className="py-4">
                <a className="link mr-4" onClick={doSave} title="Save changes">
                    <CheckIcon className="inline w-4 h-4" />
                </a>
                <a className="link" onClick={doCancel} title="Cancel change">
                    <XIcon  className="inline w-4 h-4" />
                </a>
            </td>
        </tr>
    }
    return <tr>
        <td className="py-4  px-6">
            {name}
            {props.children}
            {errorRender}
        </td>
        <td className="py-4">
            <a className="link mr-4" onClick={doEdit} title="Edit item">
                <PencilIcon className="inline w-4 h-4" />
            </a>
            <a onClick={doDelete} className="cursor-pointer" title="Delete item">
                <TrashIcon  className="text-red-700 inline w-4 h-4" />
            </a>
        </td>
    </tr>
}