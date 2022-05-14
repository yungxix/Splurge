import React, {useState} from 'react';
import SplurgeModal from '../modal';
import isFunction from 'lodash/isFunction';
import { getErrorMessage } from '../../utils';
import { RefreshIcon } from '@heroicons/react/outline';
export interface ConfirmationCallback<T> {
    (arg: T): Promise<any>;
}

type MessageFactory<T> = (d?: T) => string;


export interface ConfirmationOptions<T> {
    onApproved: ConfirmationCallback<T>;
    message: string | MessageFactory<T>;
    title?: string;
    onCancel?: () => void;
    yesText?: string;
    noText?: string;
    showProgress?: boolean;
}

interface ConfirmationContext<T> {
    data?: T;
    show: boolean;
    responded: boolean;
    error?: string;
    processing?: boolean;

}

export function useConfirmation<T>(options: ConfirmationOptions<T>): [(t: T) => void, any] {
    const [ctx, setCtx] = useState<ConfirmationContext<T>>({show: false, responded: false});
    const performWithProgress = async (d: T) => {
        try {
            setCtx({...ctx, error: '', processing: true});
            await options.onApproved(d);
            setCtx({...ctx, error: '', processing: false, responded: true, show: false});
        } catch (ex) {
            setCtx({...ctx, error: getErrorMessage(ex) || 'Failed to process request', processing: false});
        }

    };


    const render = <SplurgeModal onClose={() => {
        if (options.onCancel && !ctx.responded) {
            setCtx({...ctx, responded: true, show: false});
            options.onCancel();
        }
    }} show={ctx.show} title={options.title || 'Confirm'}>
        
        <p className='mt-4'>
            {
                isFunction(options.message) && <>{options.message(ctx.data)}</>
            }
            {
                !isFunction(options.message) && (<>{options.message}</>)
            }
        </p>

        {
            ctx.processing && (<RefreshIcon className='mx-auto w-8 h-8 animate-spin'></RefreshIcon>)
        }

        {
            ctx.error && (<p className='text-center text-red-800'> 
                {ctx.error}
            </p>)
        }

        <p className='mt-4 flex flex-row justify-end items-center p-2 gap-x-4' >
            <button type='button' disabled={ctx.processing} className='btn' onClick={(e) => {
            if (ctx.data) {
                if (options.showProgress) {
                    return performWithProgress(ctx.data);
                }
              options.onApproved(ctx.data);
            }
            setCtx({responded: true, show: false})
        }}>
                {options.yesText || 'Yes'}
            </button>

            <button disabled={ctx.processing} type='button' className='btn' onClick={(e) => {
                if (options.onCancel) {
                    options.onCancel();
                }
            setCtx({responded: true, show: false})
        }}>
                
                {options.noText || 'No'}
            </button>
        </p>
    </SplurgeModal>

    const prompt = (data: T) => {
        setCtx({data, responded: false, show: true});
    };

    return [
        prompt,
        render
    ];
}
