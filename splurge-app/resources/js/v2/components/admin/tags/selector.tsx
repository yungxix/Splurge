import React, { FC, Fragment, useEffect, useState, useMemo } from "react";


import { Popover, Transition } from '@headlessui/react';
import { ChevronDownIcon } from '@heroicons/react/solid';

export interface SelectableTag {
    id: number;
    name: string;
    attached?: boolean;
}


export interface SelectorProps {
    tags: SelectableTag[];
    inputName?: string;
    onRemove: (tag: { id: number }) => void;
    onSelect: (tag: { id: number }) => void;
}


const TagSelector: FC<SelectorProps> = (props) => {
    const [tags, setTags] = useState(props.tags);

    const [search, setSearch] = useState('');

    const selectedTags = useMemo(() => {
        return tags.filter(x => x.attached === true);
    }, [tags]);



    const availableTags = useMemo(() => {
        return tags.filter(x => x.attached !== true);
    }, [tags]);


    const filteredTags = useMemo(() => {
        const s = (search || '').toLowerCase();
        return availableTags.filter(x => {
            if (!s) {
                return true;
            }
            return x.name.toLowerCase().includes(s);
        });
    }, [availableTags, search]);


    const removeTag = (tag: { id: number }) => {
        setTags(tags.map(t => {
            if (t.id === tag.id) {
                return { ...t, attached: false };
            }
            return t;
        }));
        props.onRemove(tag);
    };

    const addTag = (tag: { id: number }) => {
        setTags(tags.map(t => {
            if (t.id === tag.id) {
                return { ...t, attached: true };
            }
            return t;
        }));
        props.onSelect(tag);
    };

    return <div className="flex flex-row flex-wrap items-center p-2 gap-4">
        {
            props.inputName && (
                <div>
                    {
                        selectedTags.map((tag) => (<input key={tag.id} name={props.inputName} type="hidden" value={tag.id} />))
                    }
                </div>
            )
        }
        
        
        {
            availableTags.length > 0 && (<div>
                <Popover className="relative">
                    {({ open }) => (
                        <>
                            <Popover.Button
                                className={`
                ${open ? '' : 'text-opacity-90'}
                text-white group bg-splarge-600 px-3 py-2 rounded-md inline-flex items-center text-base font-medium hover:text-opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75`}
                            >
                                <span>Add</span>
                                <ChevronDownIcon
                                    className={`${open ? '' : 'text-opacity-70'}
                  ml-2 h-5 w-5 text-splurge-300 group-hover:text-opacity-80 transition ease-in-out duration-150`}
                                    aria-hidden="true"
                                />
                            </Popover.Button>
                            <Transition
                                as={Fragment}
                                enter="transition ease-out duration-200"
                                enterFrom="opacity-0 translate-y-1"
                                enterTo="opacity-100 translate-y-0"
                                leave="transition ease-in duration-150"
                                leaveFrom="opacity-100 translate-y-0"
                                leaveTo="opacity-0 translate-y-1"
                            >
                                <Popover.Panel className="absolute z-10 bg-white w-screen max-w-sm px-4 mt-3 transform sm:px-0 lg:max-w-3xl">
                                    <div className="overflow-hidden rounded-lg shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div className="p-4">
                                            <input type="search" value={search}
                                                onChange={(e) => setSearch(e.target.value)}
                                                className="rounded-md shadow-sm border-gray-300 focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Search" />
                                        </div>
                                        <div className="relative grid gap-8  p-7 lg:grid-cols-2">
                                            {
                                                filteredTags.map((tag) => (<div key={tag.id} className="hover:bg-gray-50">
                                                    <span className="">
                                                        {tag.name}
                                                        <a className="link ml-4" onClick={(e) => addTag(tag)}>
                                                            Add
                                                        </a>
                                                    </span>

                                                </div>))
                                            }
                                        </div>
                                    </div>
                                </Popover.Panel>
                            </Transition>
                        </>
                    )}
                </Popover>


            </div>)
        }
        {
            selectedTags.map((tag, idx) => (<span key={`selected_tag_${tag.id}_${idx}`} className="bg-gray-200
            inline-flex
            items-center
            rounded-md
            ring
            ring-gray-400
            px-4 py-2
            content-between
            justify-center">
                <span>{tag.name}</span>
                <a onClick={(e) => removeTag(tag)} className="ml-4 cursor-pointer hover:font-bold">
                    &times;
                </a>
            </span>))
        }
    </div>



};


export default TagSelector;
