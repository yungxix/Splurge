export type GuestOptions = null | Record<string, any>; 

export interface CustomerEventGuest {
    id: number;
    name: string;
    attended_at: string;
    accepted?: GuestOptions;
    presented?: GuestOptions;
    table?: string | null;
    gender?: string;
    barcode_image_url?: string | null;
}