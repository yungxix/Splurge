
import React, {FC, useState, createRef, useRef, useEffect} from "react";

import axios from '../../../../axios-proxy';

import classNames from "classnames";

enum Status {
    idle = 0,
    busy = 2,
    failed = -1
};

interface TagModel {
    id?: number;
    name: string;
    category?: string;
}



export interface TableViewProps {
    tags: Array<TagModel>;
    baseUrl: string;
}

interface EditModel {
    index: number;
    data: TagModel;
    show: boolean;
}

const defaultEditModel = (): EditModel => ({show: false, index: -1, data: {name: ''}});

const saveTag = async (tag: TagModel, options: {baseURL: string}): Promise<TagModel> => {
    if (tag.id) {
        const resp = await axios.post<{data: TagModel}>(options.baseURL, tag);

        return resp.data.data;
    } 

    const resp2 = await axios.patch<{data: TagModel}>(`${options.baseURL}/${tag.id}`, tag);

    return resp2.data.data;

};


const destroyTag = async (tag: {id: number}, options: {baseURL: string}): Promise<any> => {
    const resp2 = await axios.delete(`${options.baseURL}/${tag.id}`);

    return resp2.data;
};



const TableView: FC<TableViewProps> = (props) => {

    const [tags, setTags] = useState(props.tags);
    const [editing, setEditing] = useState(defaultEditModel());
    const [status, setStatus] = useState(Status.idle);
    const [errorMessage, setErrorMessage] = useState('');

    const editorRef = createRef<HTMLInputElement>();

    const focusOnEditor = () => {
        setTimeout(() => {
            if (editorRef.current) {
                editorRef.current.focus();
            }
        }, 400);
    };


    const edit = (tag: TagModel, index: number) => {
        setEditing({show: true, data: tag, index});
        focusOnEditor();
    };



    const commitEdit = async () => {
        setStatus(Status.busy);
        setErrorMessage('');
        try {
            const r = await saveTag(editing.data, {baseURL: props.baseUrl});
            if (editing.index >= 0) {
                setTags(tags.map((t) => {
                    if (t.id === r.id) {
                        return r;
                    }
                    return t;
                }));
            } else {
                setTags([...tags, r]);
            }
            setEditing(defaultEditModel());
            setStatus(Status.idle);
        } catch (error: any) {
          setStatus(Status.failed);  
          setErrorMessage(error.message);
        } 
    };

    const deleteTag = async (tag: TagModel) => {
        setStatus(Status.busy);
        setErrorMessage('');
        try {
            await destroyTag({id: tag.id || 0}, {baseURL: props.baseUrl});
            setTags(tags.filter(t => t.id !== tag.id));
            if (tag.id === editing.data.id) {
                setEditing(defaultEditModel());
            }
        } catch (ex: any) {
            setStatus(Status.failed);
            setErrorMessage(ex.message);
        }
    };

    return <div className="flex flex-col">
    <div className="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div className="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div className="shadow overflow-hidden border-b border-pink-200 sm:rounded-lg">
            <p v-if="errorMessage" className="text-center p-4 text-red-700" v-text="errorMessage">

            </p>
          <table className="min-w-full divide-y divide-pink-200">
            <thead className="bg-pink-100">
              <tr>
                  <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    #
                 </th>
                <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                 </th>
                 <th scope="col" className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        
                 </th>      
              </tr>
              
            </thead>
            <tbody className="bg-white divide-y divide-pink-200">
                <tr>
                  <td colSpan={3}>
                      {
                          errorMessage && (<div className="w-full m-4 rounded-md p-8 text-center bg-red-200 text-red-900">
                              <a className="float-right hover:font-bold" onClick={(e) => setErrorMessage('')}>
                                &times;  
                              </a>
                              <em>{errorMessage}</em>
                              <br className="clear-both" />
                          </div>)
                      }
                      <div className="w-full flex flex-row items-center px-6 py-4">
                          <input disabled={status === Status.busy} onKeyUp={(e) => {
                              if ((/enter/i).test(e.key)) {
                                  commitEdit();
                              }
                          }} ref={editorRef} value={editing.data.name}
                          onChange={(e) => {
                              setEditing({...editing, data: {...editing.data, name: e.target.value}})
                          }}
                           placeholder={editing.index === -1 ? 'New Tag' : 'Edit Tag'}
                           className="grow rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                           {
                               status !== Status.busy && (<div className="ml-4 flex flex-row items-center justify-end gap-2">
                             
                               <a onClick={commitEdit} v-if="editing.name" className="link text-sm">
                                   Save {
                                       editing.index !== -1 && (<span className="ml-2">
                                           tag #{editing.index + 1}
                                       </span>)
                                   }
                               </a>
                               <a onClick={(e) => setEditing(defaultEditModel())} className="link text-sm">
                                   Cancel
                               </a>
                           </div>)
                           }

                           {
                               status === Status.busy && ( <svg xmlns="http://www.w3.org/2000/svg" className="ml-4 h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                             </svg>)
                           }
                      </div>
                  </td>
              </tr>
              {
                  tags.map((tag, index) => (<tr key={tag.id}>
                      <td className="px-6 py-4 whitespace-nowrap">#{index + 1}</td>
                      <td className={classNames(
                          'px-6 py-4 whitespace-nowrap', {
                              'font-bold': editing.data.id === tag.id
                          }
                      )}>
                          {tag.name}
                          {
                              editing.data.id === tag.id && ( <svg xmlns="http://www.w3.org/2000/svg" className="ml-8 h-6 w-6 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                              </svg>)
                          }
                      </td>
                      <td>
                          {
                              status !== Status.busy && (<>
                                <a onClick={(e) => edit(tag, index)} className="link mr-8">Edit</a>
                            
                            <a  onClick={(e) => deleteTag(tag)} className="link">Delete</a>
                              </>)
                          }
                      </td>
                  </tr>))
              }
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>





};


export default TableView;