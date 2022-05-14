export type PricingType = 'fixed' | 'incremental' | 'percentage';

export interface ServiceItem {
    id?: number;
    name: string;
    category: string;
    description?: string | null;
    pricing_type: PricingType;
    price: number;
    options?: Record<string, any>;
    required: boolean;
    sort_number?: number;
}