import React, {FC, useState, useMemo} from "react";
import uniqueId from "lodash/uniqueId";
import { TrashIcon, PlusIcon } from '@heroicons/react/outline';
import isArray from 'lodash/isArray';

interface TextItem {
    text: string;
    html_text?: string;
}

export interface ConcreteItem extends TextItem {
    items?: TextItem[];
}


interface TextItemWithId extends TextItem {
    id: string;
}

interface ConcreteItemWithId extends TextItemWithId {
    items?: TextItemWithId[];
}


const appendIds = (items: ConcreteItem[]): ConcreteItemWithId[] => items.map((a, idx) => ({
    id: uniqueId(`opt_item_${idx}_`),
    text: a.text,
    html_text: a.html_text,
    items: a.items && isArray(a.items) ? a.items.map((k, j) => ({...k, id: uniqueId(`opt_child_${j}_`)})) : undefined,
}));



export interface ServiceTierOptionsProps {
    name: string;
    value: Array<ConcreteItem> | null
}




const newItem = (): ConcreteItemWithId => ({text: '', id: uniqueId('ni_v_')}); 

const PlainItemEditor2: FC<{
    value: TextItemWithId;
    prefix: string;
    tag?: string;
}> = ({value, prefix, tag, children}) => {
    const [text, setText] = useState((value.html_text ? value.html_text : value.text) || '');
    const [html, setHtml] = useState(value.html_text ? true : false);
    const localName = useMemo(() => {
        return `${prefix}[${html ? 'html_text' : 'text'}]`;
    }, [html]);
    return <div className="">
        {
            html && (<input type="hidden" name={`${prefix}[text]`} value="" />)
        }
        {
            'textarea' === tag && (<textarea name={localName} className="control w-full" value={text} onChange={(e) => {
                setText(e.target.value)
            }}>


            </textarea>)
        }

        {
            'textarea' !== tag && (  <input name={localName} type="text" className="control w-full" value={text} onChange={(e) => {
                setText(e.target.value)
            }} />)
        }

      
        <div className="flex flex-row justify-end items-center gap-4">
            <label>
                <input type="checkbox" checked={html} onChange={(e) => setHtml(e.target.checked)} />
                HTML?
            </label>
            {children}
        </div>
        
    </div>


};


const ItemEditor2: FC<{value: ConcreteItemWithId;  onChange: (value: ConcreteItemWithId) => void; prefix: string; onDelete: (id: string) => void}> = (props) => {
    const itemTag = props.value.items ? 'textarea' : 'input';

    return <div className="border rounded-md mt-2 mx-2 mb-4 p-4"><div className="flex flex-row mb-4">
            <div className="flex-grow">
                <PlainItemEditor2 prefix={props.prefix} value={props.value} tag={itemTag} />
            </div>
            <div className="w-1/5">
                <div className="flex flex-row justify-end items-center gap-2">
                    <a className="link" title="Remove item" onClick={(e) => props.onDelete(props.value.id)}>
                        <TrashIcon  className="w-4 h-4" />
                    </a>
                    <a title="Add a sub-item" className="link" onClick={(e) => {
                        const source = props.value.items || [];
                        props.onChange({
                            ...props.value,
                            items: [...source, newItem()]
                        });
                    }}>
                        <PlusIcon className="w-4 h-4" />
                    </a>
                    {props.children}
                </div>
            </div>
        </div>
        {
            props.value.items && props.value.items.length > 0 && (<div>
                <div className="bg-gray-400 px-6 py-4">
                    <p>
                        This item has the following sub-items...
                    </p>
                    <ul>
                        {
                            props.value.items.map((item, j) => (<li className="pb-4" key={item.id}>
                                <PlainItemEditor2 value={item} prefix={`${props.prefix}[items][${j}]`}>
                                    <a title="Remove sub-item" className="link" onClick={(e) => {
                                            props.onChange({
                                                ...props.value, items: props.value.items?.filter(i => i.id !== item.id)
                                            })
                                    }}>
                                        <TrashIcon className="w-4 h-4" />
                                    </a>
                                </PlainItemEditor2>

                            </li>))
                        }
                    </ul>
                </div>
            </div>)
        }
    </div>
};



const OptionsEditor: FC<ServiceTierOptionsProps> = props => {
    const [options, setOptions] = useState(appendIds(props.value || []));

    const handleChange = (data: ConcreteItemWithId) => setOptions(options.map(o => o.id === data.id ? data : o));

    const handleDelete = (id: string) =>  setOptions(options.filter(o => o.id !== id));


    const handleInsert = (at: number) => {
        setOptions([
            ...options.slice(0, at), newItem(), ...options.slice(at)
        ]);
    };
    return <div>
        <div className="mb-4 flex flex-row justify-end items-center">
            <a className="link" onClick={(e) => {
                setOptions([...options, newItem()])
            }}>
                <PlusIcon className="w-4 h-4" />
            </a>
        </div>
        {
            options.map((option, index) => (<ItemEditor2 key={option.id}
                value={option}
                onChange={handleChange}
                onDelete={handleDelete}
                prefix={`${props.name}[${index}]`}
                >
                    <a className="link" title="Insert an item here" onClick={(e) => handleInsert(index)}>
                        Insert
                    </a>
                </ItemEditor2>))
        }
    </div>
};

export default OptionsEditor;