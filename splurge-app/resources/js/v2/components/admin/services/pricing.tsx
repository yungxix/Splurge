import React, {FC, useState, useRef} from "react";

import { useConfirmation } from "../../hooks/confirmation";

import axios from '../../../../axios-proxy';

import { ServiceItem } from './types';

import ItemRender from './items/index';

import maxBy from 'lodash/maxBy';

import uniqueId from "lodash/uniqueId";

import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";

const nextSortNumber = (items: ServiceItem[]): number => {
    const m = maxBy(items, i => i.sort_number || 0);
    if (m && m.sort_number) {
        return m.sort_number + 1;
    }
    return 1;
};


import Editor from './editor';

export interface PricingProps {
    baseURL: string;
    items: ServiceItem[];
}

type ServiceItemCallback = (item: ServiceItem) => void;


const useEditor = (options: {baseURL: string; onCreated: ServiceItemCallback, onUpdated: ServiceItemCallback}): [ServiceItemCallback, any] => {
    const [show, setShow] = useState(false);

    const [edited, setEdited] = useState<ServiceItem>();

    const open = (item: ServiceItem) => {
        setEdited(item);
        setShow(true);
    };

    const modal = edited ? (<Editor baseURL={options.baseURL} show={show} serviceItem={edited} onSave={(d) => {
        if (edited.id) {
            options.onUpdated(d);
        } else {
            options.onCreated(d);
        }
    }} onCancel={() => {
        setShow(false);
        setEdited(undefined);
    }}>

    </Editor>) : null;

    




    return [
        open,
        modal
    ];
};

const sortItems = (items: Array<ServiceItem>, fromIndex: number, toIndex: number): ServiceItem[] =>  {
    const dest = Array.from(items);
    const moved = dest.splice(fromIndex, 1);
    for (let i = 0;  i < moved.length; i++) {
        dest.splice(toIndex + i, 0, moved[i]);
    }
    return dest;
};

const reportSort = (items: ServiceItem[], options: {baseURL: string}) => {
    const ids = items.map(x => x.id);
    return axios.patch(`${options.baseURL}/sort`, {
        ids
    }).then(r => r.data);
}

const PricingView: FC<PricingProps> = (props) => {
    const [items, setItems] = useState(props.items);

    const draggableIds = useRef<Map<string, string>>(new Map());
    
    const [promptDelete, deleteModal] = useConfirmation<ServiceItem>({
        message: 'Are you sure you want to delete this item',
        title: 'Confirm Delete',
        showProgress: true,
        onApproved: async function (item) {
            const res = await axios.delete(`${props.baseURL}/${item.id}`);
            setItems(items.filter(m => m.id !== item.id));
        }
    });

    const [open, editor] = useEditor({
        baseURL: props.baseURL,
        onCreated: (item) => {
            setItems([...items, item]);
        },
        onUpdated: (item) => {
            setItems(items.map(o => o.id === item.id ? item : o));
        }
    });

    const createNew = () => open({
        name: '',
        price: 0,
        pricing_type: 'fixed',
        category: 'default',
        required: items.length === 0,
        options: {},
        sort_number: nextSortNumber(items)
    });

    const getDraggableId = (si: ServiceItem, index: number): string => {
        const id = String(si.id || (index + 1));

        if ((draggableIds.current.has(id))) {
            return draggableIds.current.get(id) as string;
        }
        const di = uniqueId('draggable_service_id');
        draggableIds.current.set(id, di);

        return di;
    };
    
    return <div>
        {
            items.length === 0 && (<div className="py-8 text-center">
                <em>
                    You do not have pricing models for this service yet. <a onClick={createNew} className="link">
                        Add your first model
                    </a>
                </em>
            </div>)
        }   
        {
            items.length > 0 && (<div>
                <p className="mb-4 text-right">
                    <a className="btn" onClick={createNew}>
                        Add pricing model
                    </a>
                </p>

                <DragDropContext onDragEnd={(e) => {
                    if (!e.destination) {
                        return;
                    }
                    const sortedItems = sortItems(items, e.source.index, e.destination.index);
                    reportSort(sortedItems, {baseURL: props.baseURL});
                    setItems(sortedItems);
                }}>

                    <Droppable droppableId="serviceItemsList">
                        {
                            (provided) => (
                                <div ref={provided.innerRef} {...provided.droppableProps} className="mt-4 mb-8 flex flex-col justify-start items-stretch gap-y-2 divide-y divide-splarge-200">
                                    {
                                        items.map((item, i) => (<Draggable index={i} draggableId={getDraggableId(item, i)} key={item.id} >
                                            {
                                                (provided2) => (
                                                    <ItemRender ref={provided2.innerRef}
                                                        draggableHandleProps={provided2.dragHandleProps}
                                                        draggableProps={provided2.draggableProps}
                                                      item={item} onDeleteRequested={(e) => {
                                                        promptDelete(e)
                                                    }} onEditRequested={(e) => {
                                                        open(e)
                                                    }} />
                                                )
                                            }
                                        </Draggable>))
                                    }
                                    {provided.placeholder}
                                </div>
                            )
                        }
                    </Droppable>

                </DragDropContext>

                
                
            </div>)
        }
        {deleteModal}
        {editor}
    </div>
};


export default PricingView;