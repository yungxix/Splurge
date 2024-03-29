import React, {FC, useState, useRef, useEffect} from "react";

import uniqueId from "lodash/uniqueId";

import classnames from 'classnames';

export interface FlashMessage {
    message: string;
    type: string;
    important?: boolean;
}

interface FlashMessageWithId extends FlashMessage {
    id: string;
    closing: boolean;
}


export interface MessagesProps {
    messages: FlashMessage[];
}

const translateTypeToClass = (type: string): string => {
    switch (type) {
        case 'success':
            return 'bg-green-700 text-white';
        case 'warn':
        case 'warning':
            return 'bg-yellow-700 text-black';
        case 'error':
        case 'fail':
        case 'failure':
            return 'bg-red-700 text-white';
        default:
            return 'bg-gray-700 text-white';
    }


}


const renderer: FC<MessagesProps> = (props) => {
    const [messages, setMessages] = useState<FlashMessageWithId[]>(props.messages.map((m) => {
        return {...m, id: uniqueId('flash_'), closing: false}
    }));

    if (messages.length === 0) {
        return null;
    }

    const closeMessage = (message: FlashMessageWithId): Promise<void> => new Promise((resolve, reject) => {
        if (message.closing) {
            return resolve();
        }
        setMessages(messages.map(m => {
            if (m.id === message.id) {
                return {...m, closing: true};
            }
            return m;
        }));

        setTimeout(() => {
            setMessages(messages.filter(x => x.id !== message.id));
            resolve();
        }, 2000);
    });


    const containerRef = useRef(null);

    const autoCloseAll = () => {
        setTimeout(() => {
            const m = messages.find(x => !x.closing && x.important !== true);
            if (m) {
                closeMessage(m).then(autoCloseAll);
            }
        }, 3000);
    };

    useEffect(() => {
        if (containerRef.current) {
            (containerRef.current as HTMLElement).style.marginTop = `${window.screenTop + 10}px`; 
        }
        autoCloseAll();
    }, []);

    return <div ref={containerRef} className="relative md:w-52">
        {
            messages.map((msg) => (<div key={msg.id} className={classnames('ease-out flex flex-row justify-between items-center rounded px-4 py-2 w-full',
            'rounded mb-2 duration-700 transition-opacity bg-opacity-70', translateTypeToClass(msg.type), {
                'opacity-100': !msg.closing,
                'opacity-0': msg.closing,
            })}>
                <span>
                {msg.message}
                </span>
                <a className="closing" onClick={(e) => {
                    closeMessage(msg);
                }}>
                    &times;
                </a>
            </div>))
        }

    </div>




}


export default renderer;