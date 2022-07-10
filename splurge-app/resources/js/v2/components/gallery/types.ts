
export interface Gallery {
    caption: string;
    id: number;
    description: string;
    items: GalleryItem[];
    image_options?: ImageOptions;
}


export interface GalleryItem {
    content: string;
    heading: string;
    id: number;
    mediaItems?: MediaItem[];
    media_items?: MediaItem[];
}


export interface MediaItem {
    id: number;
    url: string;
    name: string;
    thumbnail_url: string;
    media_type: string;
    image_options?: ImageOptions;
}

export interface ImageOptions extends Record<string, any> {
    width: number;
    height: number;
    thumbnail_width?: number;
    thumbnail_height?: number;   
}