import React, {useState} from 'react';
import axios from '../../../../axios-proxy';
import { getErrorMessage } from '../../../utils';
import { MenuItem } from './types';

export type Commit = (item: MenuItem) => Promise<MenuItem | null>;

export type Deleter = (item: MenuItem) => Promise<void>;

export type Removed = (item: MenuItem) => void;

export type ErrorMessage = string | null;

interface ResponseType {
    data: MenuItem;
}



export function useMenuItemCommiter(): [Commit, boolean, ErrorMessage] {
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState<ErrorMessage>(null);

    const commit: Commit = async (item) => {
        setSaving(true);
        setError(null);
        try {
            const response = item.id ?
            await axios.patch<ResponseType>(`/admin/menu_items/${item.id}`, {
                name: item.name
            }) : await axios.post<ResponseType>(`/admin/menu_items`, {
                name: item.name
            });
            return response.data.data;    
        } catch (error) {
            setError(getErrorMessage(error) || 'Failed to save menu item');
        } finally {
            setSaving(false);
        }
        return null;
    };

    return [
        commit,
        saving,
        error
    ];
}

export function useMenuItemDeleter(removed: Removed): [Deleter, boolean, ErrorMessage] {
    const [saving, setSaving] = useState(false);
    const [error, setError] = useState<ErrorMessage>(null);

    const commit: Deleter = async (item) => {
        setSaving(true);
        setError(null);
        try {
            await axios.delete(`/admin/menu_items/${item.id}`);
            removed(item);
        } catch (error) {
            // setError(getErrorMessage(error) || 'Failed to delete menu item');
        } finally {
            setSaving(false);
        }
    };

    return [
        commit,
        saving,
        error
    ];
}