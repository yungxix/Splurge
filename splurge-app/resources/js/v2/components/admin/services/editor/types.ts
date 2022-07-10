import { FC } from 'react';

export interface PricingOptionsEditorProps {
    value?: Record<string, any> | null;
    onChange: (v: Record<string, any>) => void;
    onFocus?: (e: any) => void;
}
