import React, {FC} from "react";
import { PencilIcon, TrashIcon, PlusIcon } from '@heroicons/react/outline';
import { noop } from "lodash";
export type ButtonAction = () => void;


const buttonClassFactory = (color?: string): string => {
    const classes = ['h-6', 'w-6', 'inline'];
    if (color) {
        classes.push(`text-${color}-700`);
    }
    return classes.join(' ');

};

export interface ActionButtonsProps {
    showDelete?: boolean;
    showEdit?: boolean;
    showAdd?: boolean;
    showAll?: boolean;

    onDeleteClicked?: ButtonAction;
    onEditClicked?: ButtonAction;
    onAddClicked?: ButtonAction;
    className?: string;
}



export const ActionButtons: FC<ActionButtonsProps> = (props) => (<div className={props.className || 'flex flex-row items-center gap-4'}>
    {
        (props.showAdd || props.showAll)  && (<a className="cursor-pointer" onClick={props.onAddClicked || noop}>
            <PlusIcon className={buttonClassFactory('blue')} />
        </a>)
    }
    {
        (props.showEdit || props.showAll)  && (<a className="cursor-pointer" onClick={props.onEditClicked || noop}>
            <PencilIcon className={buttonClassFactory()} />
        </a>)
    }
    {
        (props.showDelete || props.showAll)  && (<a className="cursor-pointer" onClick={props.onDeleteClicked || noop}>
            <TrashIcon className={buttonClassFactory('red')} />
        </a>)
    }
    {props.children}
</div>)