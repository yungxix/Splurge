import { PlusIcon, RefreshIcon } from "@heroicons/react/solid";
import React, {useState, PropsWithChildren, useRef} from "react";
import { useMenuItemCommiter } from "./hooks";
import { MenuItem } from "./types";


const ENTER_KEY = /enter/i;

export default function NewMenuItem(
    props: PropsWithChildren<{
        onCreated: (item: MenuItem) => void;
        onReplaceId: (arg: {
            oldId: number;
            newId: number;
            name: string;
        }) => void;
    }>
) {

    const inputRef = useRef<HTMLInputElement | null>(null);
    const [name, setName] = useState('');
    const [commit, saving, error] = useMenuItemCommiter();
    const [currentId, setCurrentId] = useState(-1);


    const doSave = async () => {
        props.onCreated({
            id: currentId,
            name
        });
        const n = name;
        setName('');
        const v = await commit({name: n});
        if (v) {
            props.onReplaceId({
                oldId: currentId,
                newId: v.id || 0,
                name: v.name
            });
            setCurrentId(currentId - 1);
            inputRef.current?.focus();
        }
        
    };
    

    return <tr>
        <td colSpan={2} className="py-4">
            {error && (<p className="mb-4 text-red-700 text-center">
                <em>
                    {error}
                </em>
            </p>)}
            <div className="flex flex-row border-splarge-300 border shadow-md rounded-md overflow-hidden">
                <input ref={inputRef} value={name} onChange={e => setName(e.target.value)} type="text" onKeyUp={(e) => {
                    if (name && ENTER_KEY.test(e.key)) {
                        doSave();
                    }
                }} className="flex-grow border-0 text-center text-lg" placeholder="Add new menu item" disabled={saving} />
                <button disabled={!name} onClick={doSave} className="bg-splarge-600 px-4 py-2 text-white">
                    <PlusIcon className="w-4 h-4" />
                </button>
            </div>
            {props.children}
        </td>
    </tr>

}