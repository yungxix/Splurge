import React, {FC, useState, useRef} from "react";
import uniqueId from "lodash/uniqueId";
export interface ServiceTierOptionsProps {
    name: string;
    value: Array<{text: string}> | null
}

interface OptionWitId {
    text: string;
    id: string;
}

const getDefaultOptions = (props: ServiceTierOptionsProps): Array<OptionWitId> => {
    if (!props.value) {
        return [];
    }
    return props.value.map((x) => ({text: x.text, id: uniqueId('st_op_')}));
};

const OptionsEditor: FC<ServiceTierOptionsProps> = props => {
    const [options, setOptions] = useState(getDefaultOptions(props));

    const [edited, setEdited] = useState<{index: number, text: string}>({index: -1, text: ''});

    const editorRef = useRef<HTMLInputElement | null>(null);

    const commitEdit = () => {
        if (!edited.text) {
            return;
        }
        if (edited.index < 0) {
            setOptions([...options, {text: edited.text, id: uniqueId("so_op")}]);
        } else {
            setOptions(options.map((opt, i) => {
                if (i == edited.index) {
                    return {...opt, text: edited.text};
                }
                return opt;
            }));
        }
        setEdited({index: -1, text: ''});
        if (editorRef.current) {
            editorRef.current.focus();
        }

    };

    const cancelEdit = () => setEdited({index: -1, text: ''});

    const removeOption = (id: string) => {
        if (edited.index > 0) {
            cancelEdit();
        }
        setOptions(options.filter((opt) => {
            return opt.id !== id;
        }));

    };



    return (<div>
        <div className="flex flex-row items-center justify-start w-full">
            <input ref={editorRef}  type="text" className="rounded-l-md  ring-splarge-700 flex-1" value={edited.text} onKeyDown={(e) => {
                if ("enter" === e.key.toLowerCase()) {
                    e.preventDefault();
                    commitEdit();
                }
            }} onChange={(e) => {
                setEdited({...edited, text: e.target.value})
            }} />
            <button className="bg-splarge-500 py-2 px-4 text-white rounded-r-md" type="button" onClick={commitEdit}>
                {edited.index < 0 ? 'Add' : 'Save'}
            </button>
            {
                edited.index >=0 && <button className="bg-splarge-500 ml-4 py-2 px-4 text-white rounded-md" type="button" onClick={cancelEdit}>
                Cancel
            </button>
            }
        </div>
        
        <ol className="mt-4 list-disc ml-4">
            {options.map((opt, index) => (<li key={opt.id}>
                <div className="flex flex-row items-center mb-4">
                    <input type="hidden"  name={`${props.name}[][text]`} value={opt.text}/>
                    <a className="link flex-1" onClick={(e) => {
                        setEdited({index, text: opt.text})
                        if (editorRef.current) {
                            editorRef.current.focus();
                        }
                    }}>
                        {opt.text}
                        {
                            index == edited.index && (<svg xmlns="http://www.w3.org/2000/svg"
                             className="h-4 w-4 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                            <path strokeLinecap="round" strokeLinejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                          </svg>)
                        }
                    </a>
                    
                    <a className="cursor-pointer" onClick={(e) => removeOption(opt.id)}>
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 text-red-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                        <path strokeLinecap="round" strokeLinejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </div>
            </li>))}
        </ol>

    </div>)
};

export default OptionsEditor;