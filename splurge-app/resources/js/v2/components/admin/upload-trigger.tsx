import React, {FC, useState} from "react";

import UploadForm from "./medium-uploader";
import SplurgeModal from "../modal";





export interface UploadTriggerProps {
    title: string;
    uploadOptions: {
        url: string;
        owner: {
            id: number;
            type: string;
        }
    };
    message?: string;
    text?: string;
    reloadOnComplete?: boolean;
    className?: string;
}

const Trigger: FC<UploadTriggerProps> = (props) => {
    const [show, setShow] = useState(false);

    return <>
        <a onClick={(e) => setShow(true)} className={`${props.className || 'link'}`}>
            {props.text || 'Add a picture'}
        </a>
        <SplurgeModal show={show} onClose={() => setShow(false)} title={props.title}>
            <UploadForm onUploaded={() => {
                if (props.reloadOnComplete) {
                    window.location.reload();
                }
            }} {...props.uploadOptions}>

            </UploadForm>
        </SplurgeModal>
    </>


}

export default Trigger;