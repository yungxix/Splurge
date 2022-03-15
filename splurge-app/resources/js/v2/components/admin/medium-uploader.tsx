import React, {FC, useReducer, useCallback} from "react";
import * as yup from 'yup';

import axios,  {AxiosError} from '../../../axios-proxy';

import ControlsContainer from '../forms/horizontal-container';

import classNames from "classnames";

import has from 'lodash/has';

import get from 'lodash/get';

import omit from 'lodash/omit';

import noop from 'lodash/noop';

enum StatusType {
    idle = 0,
    uploading = 1,
    error = -1,
    formError = -2,
    uploaded = 2
}

interface MediumOwner {
    id: number;
    type: string;
}

interface UploadForm {
    name: string;
    file: File | null;
}

const formValidationSchema = yup.object().shape({
    name: yup.string().label('Name/Title').required(),
    file: yup.mixed().label('Picture').required().test({
        name: 'picture',
        test: (value: File) => (/image\/.+/).test(value.type),
        message: 'Not an image file'
    })
});


interface LocalState {
    status: StatusType;
    progress: number;
    errorMessages: string[];
    form: UploadForm,
    validationErrors?: Record<string, string[]>;
    owner: MediumOwner;
    uploadUrl: string;
    valid: boolean;

}

const defaultState = (owner: MediumOwner, url: string): LocalState => ({
    status: StatusType.idle,
    progress: 0,
    errorMessages: [],
    form: {
        name: '',
        file: null
    },
    owner,
    uploadUrl: url,
    valid: false
});


const EMPTY_MESSAGES: string[] = [];

type Dispatcher = (action: LocalAction) => void;


const ErrorMessgesRenderer: FC<{messages: string[]}> = ({messages}) => messages.length === 0 ? null : (<>
    {
        messages.map((m, i) => 
        (<p key={`error_message_${i}`} className="text-red-700 text-base">{m}</p>))
    }
</>)


type ActionType = 'RESET' | 'START_UPLOAD' | 'VALIDATION_ERROR' | 'UPLOAD_FAILED' | 'COMPLETED_UPLOAD' | 'PROGRESS_CHANGED' | 'UPDATE_FORM';

interface LocalAction {
    type: ActionType;
    payload?: any;
}

type DispatchFactory = () => Dispatcher;



const uploadForm = async (form: UploadForm,
     owner: MediumOwner,
     url: string,
      dispatch: Dispatcher) => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");
    if (!csrfToken) {
        return dispatch({type: 'COMPLETED_UPLOAD', payload: {
            error: 'No valid session for upload'
        }});
    }


    const formData = new FormData();

    formData.append('name', form.name);
    if (form.file) {
        formData.append('medium_file', form.file);
    }
    Object.keys(owner).forEach((key) => {
        formData.append(`owner_${key}`, get(owner, key));
    });

    try {
        const resp = await axios.post(url, formData, {
            headers: {
                'Accept': 'application/json'
            },
            onUploadProgress: (e) => {
                dispatch({
                    type: 'PROGRESS_CHANGED',
                    payload: {
                        progress: (100 * (e.loaded/e.total))
                    }
                });
            },
            withCredentials: true
        });

        dispatch({type: 'COMPLETED_UPLOAD', payload: {}});
    } catch (err:  any) {
        const aerror = err as AxiosError;
        dispatch({type: 'UPLOAD_FAILED', payload: {error: aerror.message,
             errorData: aerror.response ? aerror.response.data : null}});
    }
    


};

const validateForm = (state: LocalState): boolean | Record<string, string[]> => {
    try {
        formValidationSchema.validateSync(state.form);
        return true;    
    } catch (error) {
        const tError = error as yup.ValidationError;
        const messageMap: Record<string, string[]> = {};
        return tError.inner.reduce((carry, error2) => {
            carry[error2.path || error2.name] = [error2.message];
            return carry;
        }, messageMap);
    }
};

const reducerFactory = (dispatchFactory: DispatchFactory, onUpoaded: () => void) =>
 (state: LocalState, action: LocalAction): LocalState => {
    switch (action.type) {
        case 'RESET':
            return defaultState(state.owner, state.uploadUrl);
        case 'PROGRESS_CHANGED':
            return {...state, progress: action.payload.progress};
        case 'START_UPLOAD':
            const validationResult = validateForm(state);
            if (true === validationResult) {
                uploadForm(state.form, state.owner,
                     state.uploadUrl, dispatchFactory());
                return {
                    ...state,
                    status: StatusType.uploading,
                    progress: 0
                };
            }
            if (false === validationResult) {
                return state;
            }

            return {...state, validationErrors: validationResult};
        case 'UPLOAD_FAILED':
            return {
                ...state,
                status: StatusType.idle,
                errorMessages: [action.payload.error],
                validationErrors: action.payload.errorData
            };
        case 'COMPLETED_UPLOAD':
            const error = action.payload ? action.payload.error : null;
            if (!error) {
                onUpoaded();
                setTimeout(() => {
                    dispatchFactory()({type: 'RESET'});
                }, 2000);
            } 

            return {
                ...state, status: error ? StatusType.error : StatusType.uploaded,
                progress: 100,
                validationErrors: {},
                errorMessages: error ? [error] : []
            }; 
        case 'UPDATE_FORM':
            const form = {...state.form, ...action.payload};
            return {
                ...state, form: form,
                valid: formValidationSchema.isValidSync(form)
            };
        case 'VALIDATION_ERROR':
            return {...state, validationErrors: action.payload};
        default:
            return state;
    }
};

export interface UploaderProps {
    url: string;
    owner: MediumOwner;
    onUploaded?: () => void;
}


const Uploader: FC<UploaderProps> = (props) => {
    const [state, dispatch] = useReducer(
        useCallback(reducerFactory(() => dispatch, props.onUploaded || noop), []),
     defaultState(props.owner, props.url));


     const insertError = (key: string, messages: string[]) => {
        const bag = state.validationErrors || {};
        dispatch({type: 'VALIDATION_ERROR', payload: {
            ...bag,
            [key]: messages
        }});
     };

     const deleteError = (key: string) => {
        if (state.validationErrors && has(state.validationErrors, key)) {
            dispatch({type: 'VALIDATION_ERROR', payload: omit(state.validationErrors, key) });
        }
     };


     const hasError = (key: string) => state.validationErrors &&
     has(state.validationErrors, key) && state.validationErrors[key].length > 0;

     const getErrorMessages = (key: string) => state.validationErrors ?
         get(state.validationErrors, key, EMPTY_MESSAGES) : EMPTY_MESSAGES;




    const validateFile = (input: HTMLInputElement) => {
        if (!input.files || input.files.length === 0) {
            return insertError('medium_file', ['Please select picture to upload']);
        }
        deleteError('medium_file');
        dispatch({type: 'UPDATE_FORM', payload: {file: input.files[0]}});
    }; 

    return <div>
        {
            state.errorMessages.length > 0 && (<div className="bg-red-400p-4">
                <ErrorMessgesRenderer messages={state.errorMessages} />
            </div>)
        }
        {
            (state.status === StatusType.error || state.status === StatusType.idle || state.status === StatusType.formError)  && (<div>
                <form onSubmit={(e) => {
                    e.preventDefault();
                    dispatch({type: 'START_UPLOAD'});
                }} noValidate encType="multipart/form-data">
                    <ControlsContainer label='Name/title of the picture' className={classNames({
                        error: hasError('name')
                    })}>
                        <input type="text" value={state.form.name} onChange={(e) => {
                            dispatch({
                                type: 'UPDATE_FORM',
                                payload: {
                                    name: e.target.value
                                }
                            });
                        }} />
                        <ErrorMessgesRenderer messages={getErrorMessages('name')} />
                    </ControlsContainer>
                    <ControlsContainer label='Picture' className={classNames({
                        error: hasError('medium_file')
                    })}>
                        <input type="file" onChange={(e) => {
                            validateFile(e.target);
                        }} />
                        <ErrorMessgesRenderer messages={getErrorMessages('medium_file')} />
                    </ControlsContainer>

                    <div className="flex flex-row justify-end items-center p-4">
                        <button className="btn" disabled={!state.valid} type="submit">
                            Upload
                        </button>
                    </div>

                </form>
            </div>)
        }

        {
            state.status === StatusType.uploading && (<div className="w-full flex flex-col justify-center items-center p-10">
                    <svg xmlns="http://www.w3.org/2000/svg" className="animate-spin h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                        <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>

                    <div className="w-full h-2 ring ring-splarge-700">
                        <div className={`bg-splarge-600 transition duration-200  text-white w-[${state.progress}%]`}>
                            {state.progress}%
                        </div>
                    </div>

            </div>)
        }


        {
            state.status === StatusType.uploaded && (<div className="flex flex-col justify-center items-center p-10">
                    <p className="text-green-800">
                        Uploaded successfully
                    </p>
                    <button type="button" className="btn" onClick={(e) => {
                        dispatch({type: 'RESET'})
                    }}>
                        Upload another
                    </button>

            </div>)
        }

    </div>

};


export default Uploader;