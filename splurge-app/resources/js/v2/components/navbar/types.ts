export type FormMethodType = 'POST' | 'PUT' | 'GET' | 'DELETE' | 'PATCH';

export interface NavBarItem {
    text: string;
    url: string;
    form?: FormMethodType;
    className?: string;
    items?: NavBarItem[];
    active?: boolean;
    formParams?: Record<string, string>;
}