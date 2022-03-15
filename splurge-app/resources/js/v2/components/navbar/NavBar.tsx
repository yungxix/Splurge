import React, { FC, Fragment, useState, useRef, createRef, useEffect } from "react";
import ReactDOM from 'react-dom';
import { DropdownItems, SingleItem } from './Item';
import { NavBarItem } from "./types";
import { Transition } from '@headlessui/react';
import get from 'lodash/get';
import classNames from "classnames";

const createFormContainer = () => {
    const el = document.createElement('div');

    document.body.appendChild(el);

    return el;

};


const ItemForm: FC<{ item: NavBarItem }> = (props) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || "";
    const formRef = createRef<HTMLFormElement>();

    const containerRef = useRef(createFormContainer());

    useEffect(() => {
        if (formRef.current) {
            formRef.current.submit();
        }
        return () => {
            if (containerRef.current) {
                document.body.removeChild(containerRef.current);
            }
        };
    }, []);
    return ReactDOM.createPortal(<form ref={formRef} className="hidden w-0 h-0" method="POST" action={props.item.url}>
        {
            'POST' !== props.item.form && (<input type="hidden" name="_method" value={props.item.form} />)
        }
        <input type="hidden" name="_token" value={token} />
        {
            props.item.formParams && (<div>
                {
                    Object.keys(props.item.formParams).map((attr, index) =>
                        (<input key={`${attr}_index`} type="hidden" name={attr} value={get(props.item.formParams, attr)} />))
                }

            </div>)
        }
    </form>, containerRef.current);
}

export interface NavBarProps {
    logo: string;
    logoUrl: string;
    authenticated: boolean,
    username: string;
    items: Array<NavBarItem>;
    userDropdownItems: Array<NavBarItem>;
    loginUrl: string;
}

const ItemRenderer: FC<{ item: NavBarItem; className: string; onSelect: (item: NavBarItem) => void }> = (props) => {
    if (props.item.items && props.item.items.length > 0) {
        return <DropdownItems {...props} />
    }

    return <SingleItem {...props} />
};

const DesktopItems: FC<NavBarProps & {
    showMobile: boolean;
    onSelect: (item: NavBarItem) => void;
    onShowMobileItems: (show: boolean) => void;
}> = (props) => {

    const createUserItem = (): NavBarItem => ({
        text: `Hi, ${props.username}`,
        url: '#',
        items: props.userDropdownItems
    });

    return <div className="w-full mx-auto py-2 px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
            <div className="flex items-center justify-between">
                <div className="flex-shrink-0">
                    <a href={props.logoUrl}>
                        <img className="h-12 w-12" src={props.logo} alt="Splurge Logo" />
                    </a>
                </div>
                <div className="hidden md:block grow">
                    <div className="ml-10 flex items-baseline w-full justify-end content-end space-x-4 justify-items-end">
                        {
                            props.items.map((item, idx) => (<ItemRenderer
                                onSelect={props.onSelect}
                                className={classNames('desktop', item.className, { active: item.active === true })} key={`desk_${idx}`} item={item} />))
                        }
                    </div>
                </div>

            </div>

            {
                props.authenticated && (<div className="hidden md:block">
                    <DropdownItems item={createUserItem()} onSelect={props.onSelect}>

                    </DropdownItems>
                </div>)
            }
            {
                !props.authenticated && (<div className="hidden md:block">
                    <a className="desktop" href={props.loginUrl}>Sign In</a>
                </div>)
            }
            <div className="-mr-2 flex md:hidden">
                <button type="button" onClick={(e) => {
                    props.onShowMobileItems(!props.showMobile)
                }} id="mobile-menu-trigger">

                    <span className="sr-only">Open main menu</span>
                    {
                        props.showMobile && (
                            <svg className="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        )
                    }

                    {
                        !props.showMobile && (
                            <svg className="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        )
                    }
                </button>
            </div>
        </div>
    </div>


}


const MobileItems: FC<NavBarProps & { show: boolean; onSelect: (item: NavBarItem) => void }> = (props) => {
    return <Transition
        as={Fragment}
        show={props.show}
        enter="origin-top-left duration-200 ease-out"
        enterFrom="opacity-0 scale-y-50"
        enterTo="opacity-100 scale-y-100"
        leave="origin-top-left duration-200 ease-in"
        leaveFrom="opacity-100 scale-y-100"
        leaveTo="opacity-0 scale-y-50"
    >

        <div id="mobile-menu">
            <div className="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                {
                    props.items.map((item, i) => (<ItemRenderer
                        key={`mobile_item_${i}`}
                        item={item}
                        onSelect={props.onSelect}
                        className={classNames('mobile', item.className, { active: item.active === true })}
                    />))
                }
            </div>
            {
                props.authenticated && (<>
                    <p className="ml-4 mb-4">Hi, {props.username}</p>
                    {
                        props.userDropdownItems.map((item, i) =>
                        (<SingleItem className={classNames(item.className,
                            'mobile', { active: item.active === true })}
                            onSelect={props.onSelect} item={item} />))
                    }
                </>)
            }
            {
                !props.authenticated && (
                    <a className="mobile" href={props.loginUrl}>
                        Sign In
                    </a>
                )
            }
        </div>

    </Transition>



}

const Bar: FC<NavBarProps> = (props) => {
    const [showMobile, setShowMobile] = useState(false);
    const [formItem, setFormItem] = useState<NavBarItem | null>(null);
    const handleSelect = (item: NavBarItem) => {
        if (item.form) {
            setFormItem(item)
        } else {
            window.location.href = item.url;
        }
    }
    return <div className="w-full">
        <DesktopItems {...props} onSelect={handleSelect} showMobile={showMobile} onShowMobileItems={(e) => setShowMobile(e)} />
        <MobileItems {...props} onSelect={handleSelect} show={showMobile} />
        {
            formItem && <ItemForm item={formItem} />
        }
    </div>

};

export default Bar;