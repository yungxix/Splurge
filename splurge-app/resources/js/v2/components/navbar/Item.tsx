import React, {FC, Fragment} from "react";
import { Popover, Transition } from '@headlessui/react';
import { ChevronDownIcon } from '@heroicons/react/solid';
import { NavBarItem } from "./types";
import classNames from "classnames";

export interface ItemProps {
    item: NavBarItem;
    onSelect: (item: NavBarItem) => void;
    className?: string
}




export const DropdownItems: FC<ItemProps> = (props) => {
  return (<Popover className="relative">
  {({ open }) => (
    <>
      <Popover.Button
        className={`
          ${open ? '' : 'text-opacity-90'}
          text-white group px-3 py-2 rounded-md inline-flex items-center text-base font-medium hover:text-opacity-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75`}
      >
        <span>{props.item.text}</span>
        <ChevronDownIcon
          className={`${open ? '' : 'text-opacity-70'}
            ml-2 h-5 w-5 text-white group-hover:text-opacity-80 transition ease-in-out duration-150`}
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
        <Popover.Panel className="shadow rounded-md w-[222px] absolute z-10 top-4 left-auto right-4 bg-white px-4 mt-4">
         {
           props.item.items?.map((child, index) => (<a
            key={`child_${index}`}
             href={child.url}
             onClick={(e) => {
               e.preventDefault();
              props.onSelect(child);
             }}
             className={classNames(child.className, 'block py-2 hover:font-bold')}
             >
               {child.text}
           </a>))
         }
        </Popover.Panel>
      </Transition>
    </>
  )}
</Popover>);

}


export const SingleItem: FC<ItemProps> = (props) => {
    return <a className={classNames(props.className, 'cursor-pointer')} href={props.item.url} onClick={(e) => {
        e.preventDefault();
        props.onSelect(props.item);
        return false;
    }}>
        {props.item.text}
        {props.children}
    </a>
}