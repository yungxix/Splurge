import React, {FC, useMemo} from "react";
import { Gallery } from "./types";

import { Swiper, SwiperSlide } from 'swiper/react';


import 'swiper/css';

export interface SimpleGalleryProps {
    gallery: Gallery;
    groupSize?: number;
}

interface GroupItem {
    name: string;
    imageUrl: string;
    id: any;
}

interface Group {
    id: any;
    items: GroupItem[];
}


const SimpleGallery: FC<SimpleGalleryProps> = (props) => {
    const groups = useMemo(() => {
        const groupSize = props.groupSize || 5;
        const result: Group[] = [];

        props.gallery.items.forEach((item, itemIndex) => {
            let offset = 0;
            const mediaItems = item.mediaItems || item.media_items || [];
            const len = mediaItems.length;
            while (offset < len) {
                const slice = mediaItems.slice(offset, offset + groupSize);
                offset += groupSize;

                result.push({
                    id: `${item.id}_${offset}`,
                    items: slice.map((ma) => ({imageUrl: ma.url, id: ma.id, name: ma.name}))
                });
            }
        });
        return result;
    }, [props.gallery.id, props.groupSize]);
    return <div>
        {
            groups.map((grp) => (<div key={grp.id}>
                <Swiper>
                    {
                        grp.items.map((item) => (<SwiperSlide key={item.id}>
                            <figure className="block">
                                <img src={item.imageUrl} />
                                <figcaption>{item.name}</figcaption>
                            </figure>
                        </SwiperSlide>))
                    }
                </Swiper>
            </div>))
        }
    </div>

};

export default SimpleGallery;


