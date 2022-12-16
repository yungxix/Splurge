import React, { useState } from 'react';
import { MenuItem } from './types';
import NewMenuItem from './new-item';
import MenuItemView from './item-view';

export default function MenuItemsApp(props: {
    value: MenuItem[]
}) {
    const [items, setItems] = useState(props.value);
    const acceptItem = (item: MenuItem) => setItems([item, ...items]);
    const removeItem = (item: MenuItem) => setItems(items.filter(a => a.id !== item.id));
    const doIdReplace = (arg: {oldId: number; newId: number; name: string}) => {
        const exists = items.some(e => e.id === arg.oldId);
        if (exists) {
            setItems(items.map(item => {
                if (item.id === arg.oldId) {
                    return {id: arg.newId, name: arg.name};
                }
                return item;
            }));
        } else {
            setItems([{id: arg.newId, name: arg.name}, ...items]);
        }
    };

    return <div>
        <div className="flex flex-col">
            <div className="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div className="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div className="shadow overflow-hidden border-b border-splarge-200 sm:rounded-lg">
                        <table className="min-w-full divide-y divide-splarge-200">
                            <tbody className='bg-white divide-y divide-splarge-200'>
                                <NewMenuItem onCreated={acceptItem} onReplaceId={doIdReplace} />
                                {items.length === 0 && (<tr>
                                    <td className='text-center' colSpan={2}>
                                        <em>
                                        Your list is empty
                                        </em>
                                    </td>
                                </tr>)}
                                {
                                    items.map((item, i) => <MenuItemView
                                        value={item}
                                        key={`item-${item.id}-${i}`}
                                        onDeleteRequested={removeItem}
                                    />)
                                }
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
}