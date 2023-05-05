import { isNumber, get, uniqueId, noop } from 'lodash';
import React, {
    useRef,
    useState,
    PropsWithChildren,
    useContext,
    useEffect
} from 'react';
import { XIcon, CheckIcon, PencilIcon, TrashIcon, PlusIcon } from '@heroicons/react/outline';
import { useCommiter2 } from './hooks';
import { RefreshIcon } from '@heroicons/react/solid';
import { GuestContext } from './contextx';
import { ActionButtons } from './components';

interface Option {
    label: string;
    value: any;
}

interface OptionWithId extends Option {
    id: string;
}

type ValueType = 'yes' | 'no' | 'count' | 'text';

const VALUE_TYPE_OPTIONS: Record<ValueType, string> = {
    'yes': 'Yes',
    'no': 'No',
    'count': 'Enter count',
    'text': 'Type comment',
}

const translateValueType = (value: any): ValueType => {
    if (isNumber(value)) {
        return 'count';
    }
    if ((/^yes$/i).test(value)) {
        return 'yes';
    }

    if ((/^no$/i).test(value)) {
        return 'no';
    }

    if ((/\d+/).test(value)) {
        return 'count';
    }

    return 'text';

};

function StandaloneItemEditor(props: PropsWithChildren<{
    value: Option;
    onChange: (value: Option) => void;
    className?: string;
    disabled?: boolean;
    onCancel: () => void;
    name?: string;
}>) {
    const labelRef = useRef<HTMLInputElement | null>(null);
    const inputRef = useRef<HTMLInputElement | null>(null);
    const [valueType, setValueType] = useState(translateValueType(props.value.value));

    

    const doCommit = () => {
        if ((/yes|no/).test(valueType)) {
            props.onChange({
                label: labelRef.current?.value || '',
                value: valueType
            });
        } else if (valueType === 'count') {
            props.onChange({
                label: labelRef.current?.value || '',
                value: inputRef.current?.valueAsNumber || 0
            });
        } else {
            props.onChange({
                label: labelRef.current?.value || '',
                value: inputRef.current?.value
            });
        }

    };

    useEffect(() => {
        doCommit();
    }, [valueType]);

    return <div className=''>
        <input ref={labelRef} type="text" defaultValue={props.value.label} placeholder="What is it?" className='control w-full'  onBlur={doCommit} />
        <div className='flex flex-row justify-start gap-4 flex-wrap mt-4'>
            <select  disabled={props.disabled} value={valueType} className="control"
            onChange={e => setValueType(e.target.value as ValueType)}>
                {
                    Object.keys(VALUE_TYPE_OPTIONS).map((typeName) => (<option key={typeName} value={typeName}>
                        {get(VALUE_TYPE_OPTIONS, typeName)}
                    </option>))
                }
            </select>
            {
                valueType === 'count' && (<input  disabled={props.disabled} type="number"
                defaultValue={props.value.value} placeholder="Enter number" className='control' ref={inputRef} onBlur={doCommit} />)
            }
            {
                valueType === 'text' && (<input  disabled={props.disabled} type="text"
                defaultValue={props.value.value} placeholder="Enter value" className='control flex-grow' ref={inputRef} onBlur={doCommit} />)
            }
        </div>
        {props.children}
    </div>

}

function ItemEditor(props: PropsWithChildren<{
    value: Option;
    onChange: (value: Option) => void;
    className?: string;
    disabled?: boolean;
    onCancel: () => void;
    name?: string;
}>) {
    const labelRef = useRef<HTMLInputElement | null>(null);
    const inputRef = useRef<HTMLInputElement | null>(null);
    const [valueType, setValueType] = useState(translateValueType(props.value.value));

    const doCommit = () => {
        if ((/yes|no/).test(valueType)) {
            props.onChange({
                label: labelRef.current?.value || '',
                value: valueType
            });
        } else if (valueType === 'count') {
            props.onChange({
                label: labelRef.current?.value || '',
                value: inputRef.current?.valueAsNumber || 0
            });
        } else {
            props.onChange({
                label: labelRef.current?.value || '',
                value: inputRef.current?.value
            });
        }

    };

    return <div className='mb-4'>
        <input disabled={props.disabled} ref={labelRef} type="text" defaultValue={props.value.label} placeholder="What is it?" className='control w-full block mb-2' />
        <div className='flex flex-row gap-4 flex-wrap'>
            <select  disabled={props.disabled} value={valueType} className="control" onChange={e => setValueType(e.target.value as ValueType)}>
                {
                    Object.keys(VALUE_TYPE_OPTIONS).map((typeName) => (<option key={typeName} value={typeName}>
                        {get(VALUE_TYPE_OPTIONS, typeName)}
                    </option>))
                }
            </select>
            {
                valueType === 'count' && (<input  disabled={props.disabled} type="number"
                defaultValue={props.value.value} placeholder="Enter number" className='control' ref={inputRef} />)
            }
            {
                valueType === 'text' && (<input  disabled={props.disabled} type="text"
                defaultValue={props.value.value} placeholder="Enter value" className='control' ref={inputRef} />)
            }
            {
                !props.disabled && (<p className='mt-2 flex flex-row items-center gap-4'>
                <a className='cursor-pointer' onClick={props.onCancel}>
                    <XIcon className='w-6 h-6 text-red-800' />
                </a>

                <a className='cursor-pointer' onClick={doCommit}>
                    <CheckIcon className='w-6 h-6 text-green-800' />
                </a>
                
            </p>)
            }
        </div>
        
        {props.children}
    </div>
}

function ItemRenderer(props: PropsWithChildren<{
    value: Option
}>) {
    const valueType = translateValueType(props.value);
    switch (valueType) {
        case 'yes':
            return (<p>
                <strong>{props.value.label}</strong>:
                <em className='text-green-800 mx-4'>Yes</em>
                {props.children}
            </p>);
        case 'no':
            return (<p>
                <strong>{props.value.label}</strong>:
                <em className='text-red-800 ml-x'>Yes</em>
                {props.children}
            </p>);
        case 'count':
            return (<p className='flex flex-row items-center'>
                <strong>{props.value.label}</strong>:
                <span className='rounded-full p-2 bg-slate-800 text-white font-bold mx-4'>{props.value.value}</span>
                {props.children}
            </p>);
        default:
            return <p>
                <strong>{props.value.label}</strong>:
                <em className='text-gray-800 ml-4'>
                    {props.value.value}
                </em>
                <br />
                {props.children}
            </p>
    }
}


function ItemView(props: PropsWithChildren<{
    editable: boolean;
    value: OptionWithId;
    onSaved: (opt: OptionWithId) => void;
    onBeforeSave: () => OptionWithId[];
    onDelete: (o: OptionWithId) => void;
    onAddClicked: () => void;
    attribute: string;
    editMode: boolean;
    showAdd: boolean;
}>) {
    const editor = useCommiter2();
    const [deleting, setDeleting] = useState(false);
    const {id} = useContext(GuestContext);

    const doDelete =  async () => {
        const data: Record<string, any> = {};
        props.onBeforeSave().forEach((item) => {
            if (item.id === props.value.id) {
                return;
            }
            data[item.label] = item.value;
        });

        const saved = await editor.commit({
            id,
            attribute: props.attribute,
            value: data
        });
        if (saved) {
            setDeleting(false);
            props.onDelete(props.value);
        }
    };

    const doSave =  async (opt: Option) => {
        const root: Record<string, any> = {};
        props.onBeforeSave().forEach((item) => {
            if (item.id === props.value.id) {
                root[opt.label] = opt.value;
            } else {
                root[item.label] = item.value;
            }
        }, root);

        const saved = await editor.commit({
            id,
            value: root,
            attribute: props.attribute
        });
        if (saved) {
            props.onSaved({...props.value, ...opt});
        }

    };

    if (editor.editing || props.editMode) {
        return <ItemEditor
            value={props.value}
            onCancel={editor.cancel}
            disabled={editor.busy}
            onChange={doSave}
        >
            {editor.busy && (<RefreshIcon className='w-6 h-6 animate-spin' />)}
        </ItemEditor>
    }
    if (deleting) {
        return <div className='bg-red-300 px-4 py-2 rounded-md flex flex-row items-center gap-4'>
            <em>
                Are you sure you want to delete this item?
            </em>
            {
                !editor.busy && (<>
                <a className='link cursor-pointer' onClick={(e) => setDeleting(false)}>
                No
            </a>
            <a className='link cursor-pointer' onClick={doDelete}>
                Yes
            </a>
                </>)
            }
            {editor.busy && (<RefreshIcon className='w-6 h-6 animate-spin' />)}
        </div>
    }
    return <ItemRenderer value={props.value}>
        <ActionButtons
            className='flex flex-row items-center justify-end gap-2 flex-wrap'
            showDelete={props.editable}
            showEdit={props.editable}
            onDeleteClicked={() => setDeleting(true)}
            onEditClicked={editor.edit}
            showAdd={props.showAdd}
            onAddClicked={props.onAddClicked}
        />
    </ItemRenderer>
}


const parseItems = (source: Record<string, any> | null | undefined): OptionWithId[] => {
    if (!source) {
        return [];
    }

    return Object.keys(source).map((x) => ({
        label: x,
        value: source[x],
        id: uniqueId('src_')
    }));

};



export default function GuestAttachments(props: {
    value: Record<string, any> | undefined | null;
    attribute: string;
    editable: boolean;
    standalone?: boolean;
    name?: string;
}) {
    const [items, setItems] = useState(parseItems(props.value));
    const [newItems, setNewItems] = useState<string[]>([]);
    const doAdd = () => {
        const item: OptionWithId = {
            value: 'yes',
            label: `<Type the name of what is ${props.attribute}>`,
            id: uniqueId('ni_')
        };
        setNewItems([...newItems, item.id]);
        setItems([...items, item]);
    };
    const doSaved = (item: OptionWithId) => {
        setNewItems(newItems.filter(x => x !== item.id));
        setItems(items.map(x => x.id === item.id ? item : x));

    };

    const doDelete = (item: OptionWithId) => {
        setItems(items.filter(x => x.id !== item.id ));
    };

    const isNew = (item: {id: string}) => newItems.includes(item.id);

    const newItemRender = () => props.editable ? (<div>
        <a className='cursor-pointer' onClick={doAdd} title={`Add to ${props.attribute}`}>
            <PlusIcon className='w-6 h-6' />
        </a>
    </div>) : (<span></span>);

    if (props.standalone) {
        return <div  className=''>
            {
                items.length === 0 && (<ActionButtons showAdd={true} showDelete={false} showEdit={false} onAddClicked={doAdd} />)
            }
            {
                items.length > 0 && (<div className='bg-stone-100 rounded-md p-4 flex flex-col gap-4 divide-y divide-solid divide-gray-600'>
                {
                    items.map((item, index) => (<div key={item.id}>
                        <input type="hidden" name={`${props.name}[${item.label}]`} value={item.value} />
                        <StandaloneItemEditor
                            value={item}
                            onCancel={noop}
                            onChange={(v) => {
                                doSaved({...item, ...v})
                            }}
                        >
                            <ActionButtons 
                            className='flex flex-row items-center gap-4 justify-end mt-2'
                            showAdd={index === items.length - 1}
                             showDelete={true}
                             onDeleteClicked={() => doDelete(item)}
                             onAddClicked={doAdd}
                             >
                            </ActionButtons>
                        </StandaloneItemEditor>
                    </div>))
                }
            </div>)
            }
            
        </div>
    }

    if (items.length === 0) {
        return (<div>
            <em className='block my-2'>Nothing</em>
            {newItemRender()}
        </div>)
    }
    return (<div className='bg-stone-100 rounded-md p-4'>
        <ul className='ml-8 list-disc'>
        {items.map((item, i) => <li key={item.id}><ItemView
            
            value={item}
            editMode={isNew(item)}
            editable={props.editable}
            attribute={props.attribute}
            onBeforeSave={() => items}
            onDelete={doDelete}
            onSaved={doSaved}
            showAdd={i === items.length - 1}
            onAddClicked={doAdd}
        /></li>)}
        
    </ul></div>);
}
