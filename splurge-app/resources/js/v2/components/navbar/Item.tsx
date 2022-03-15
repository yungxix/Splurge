import React, {FC, Fragment} from "react";
import { Menu, Transition } from '@headlessui/react';
import { ChevronDownIcon } from '@heroicons/react/solid';
import { NavBarItem } from "./types";
import classNames from "classnames";

export interface ItemProps {
    item: NavBarItem;
    onSelect: (item: NavBarItem) => void;
    className?: string
}

export const DropdownItems: FC<ItemProps> = (props) => {
  return (
    <div className="w-56 text-right fixed top-16">
      <Menu as="div" className="relative inline-block text-left">
        <div>
          <Menu.Button className={classNames('inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-black rounded-md bg-opacity-20 hover:bg-opacity-30 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75', props.className)}>
            {props.item.text}
            <ChevronDownIcon
              className="w-5 h-5 ml-2 -mr-1 text-violet-200 hover:text-violet-100"
              aria-hidden="true"
            />
          </Menu.Button>
        </div>
        <Transition
          as={Fragment}
          enter="transition ease-out duration-100"
          enterFrom="transform opacity-0 scale-95"
          enterTo="transform opacity-100 scale-100"
          leave="transition ease-in duration-75"
          leaveFrom="transform opacity-100 scale-100"
          leaveTo="transform opacity-0 scale-95"
        >
          <Menu.Items className="absolute right-0 w-56 mt-2 origin-top-right bg-white divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
            {
                props.item.items?.map((child) => (<div className="px-2 py-1">
                    <Menu.Item>
                        {
                            ({active}) => <a href={child.url} onClick={(e) => {
                                e.preventDefault();
                                props.onSelect(child);
                                return false;
                            }} className={classNames('block', 'cursor-pointer', {
                                'font-bold': active,
                            })}>
                                {child.text}
                            </a>
                        }
                    </Menu.Item>
                </div>))
            }
          </Menu.Items>
        </Transition>
      </Menu>
    </div>
  )
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