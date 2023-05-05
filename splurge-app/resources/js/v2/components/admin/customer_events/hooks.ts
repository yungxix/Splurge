import { useContext, useState } from "react";
import { GuestOptionsContext } from "./contextx";
import axios from "../../../../axios-proxy";

export interface GuestCommitParams {
    attribute: string;
    value: any;
    id: number;
}



export type Committer = (o: GuestCommitParams) => Promise<boolean>;

export interface CommitterContext {
    busy: boolean;
    failed: boolean;
    editing: boolean;
    commit: Committer;
    edit: () => void;
    cancel: () => void;
}


export const useCommiter2 = function (): CommitterContext {
    const [failed, setFailed] = useState(false);
    const [busy, setBusy] = useState(false);
    const { baseUrl } = useContext(GuestOptionsContext);
    const [edit, setEdit] = useState(false);
    const committer = async (params: GuestCommitParams): Promise<boolean> => {
        setBusy(true);
        setFailed(false);
        try {
            await axios.patch(`${baseUrl}/guests/${params.id}`, {
                guest: {
                    [params.attribute]: params.value
                }
            });
            setBusy(false);
            setEdit(false);
            return true;
        } catch (error) {
            setFailed(false);
            setBusy(false);
        }
        return false;
    };

    return {
        busy,
        failed,
        editing: edit,
        edit() {
            setEdit(true);
            setFailed(false);
        },
        commit: committer,
        cancel() {
            setEdit(false);
            setBusy(false);
            setFailed(false);
        }
    };
};