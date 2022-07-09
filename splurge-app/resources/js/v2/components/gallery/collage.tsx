import React, {FC, useEffect, useState, useCallback} from "react";
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Autoplay} from 'swiper';
import { Gallery } from "./types";
import classNames from "classnames";
import Lines from '../lines';
import uniqueId from "lodash/uniqueId";
import debounce from "lodash/debounce";

import 'swiper/css';

import 'swiper/css/navigation';

import 'swiper/css/autoplay';

export interface CollageProps {
    item: Gallery;
    groupSize?: number;
    className?: string;

}

interface CollageItem {
    id: number;
    url: string;
    span: number;
    name: string;
    index: number;
    className: string;
}


interface CollagePage {
    id: string;
    heading: string;
    content: string;
    group: CollageItem[];

}

const CollagePageComponent: FC<{page: CollagePage; gridClass: string;}> = ({page, gridClass}) => {
    const gridItemClass = (item: CollageItem): string => 
    classNames('block', item.className, item.span > 1
     ? `col-span-${item.span} text-center` : '');



    return  (<div className="collage-content">
    {
        page.heading && (<div className="heading">
            <h4>{page.heading}</h4>
        </div>)
    }
    <div className={gridClass}>
        {
            page.group.map((item) => (<figure key={item.id} className={gridItemClass(item)}>
                <img src={item.url} alt={item.name} />
                <figcaption>{item.name}</figcaption>
            </figure>))
        }
    </div>
    
    {
        page.content && (<div className="footer">
            <Lines text={page.content} />
        </div>)
    }
</div>);
}


const deriveItemExtraClass = (index: number, gridSize: number) => {
    const gridIndex = index % gridSize;
    if (gridIndex === 0) {
        return '';
    }
    if (gridIndex === gridSize - 1) {
        return 'ml-auto';
    }

    return 'mx-auto';
};


const calculatePreferredGridSize = (width: number) => {
    if (width > 1800) {
        return 4;
    }
    if (width > 1024) {
        return 3;
    }
    if (width > 706) {
        return 2;
    }
    return 1;

};



const Collage: FC<CollageProps> = (props) => {
    const [pages, setPages] = useState<CollagePage[]>([]);

    const [gridClass, setGridClass] = useState('');



    const itemsCalculator = useCallback(() => {
        
        const bounds = document.querySelector('body')?.getBoundingClientRect();

        if (!bounds) {
            return {
                pages: [],
                gridClass: ''
            };
        }

        const result: CollagePage[] = [];

        const columnCount = calculatePreferredGridSize(bounds.width);

        const gridClass = `grid grid-cols-${columnCount} gap-2 md:gap-4`;

        const groupSize = props.groupSize || 5;

        props.item.items.forEach((item) => {
            let offset = 0;
            const mediaItems = item.mediaItems || item.media_items || [];
            const len = mediaItems.length;

            while (offset < len) {
                const chunk = mediaItems.slice(offset, offset + groupSize);
                const group: CollageItem[] = chunk.map((mediaItem, mediaIndex) => {
                    if (mediaItem.image_options && mediaItem.image_options.width >= bounds.width) {
                        return {
                            url:  mediaItem.url,
                            span: columnCount,
                            name: mediaItem.name,
                            id: mediaItem.id,
                            index: mediaIndex + offset,
                            className: deriveItemExtraClass(mediaIndex, columnCount)
                        }
                    }
                    const percentageWidth = mediaItem.image_options ? (100 * mediaItem.image_options.width / bounds.width) : 0;
                    const localSpan = percentageWidth > 60 ? 2 : 1;
                    return {
                        url:  mediaItem.url,
                        span: localSpan,
                        name: mediaItem.name,
                        id: mediaItem.id,
                        index: mediaIndex + offset,
                        className: deriveItemExtraClass(mediaIndex, columnCount)
                    };
                });

                result.push({
                    heading: item.heading,
                    content: item.content,
                    group,
                    id: uniqueId('collage_page_')
                });

                offset += groupSize;
            }

        });

        return {
            gridClass,
            pages: result
        };
    }, [props.groupSize, props.item.id]);

    const generate = useCallback(() => {
        const result = itemsCalculator();
        setGridClass(result.gridClass);
        setPages(result.pages);
    }, []);

    useEffect(() => {
        generate();
        const handler = debounce((e: any) => {
            generate();
        }, 500);

        window.addEventListener('resize', handler);

        return () => {
            window.removeEventListener('resize', handler);
        };

    }, [props.item.id]);
    return <div className={props.className || ''}>
        {
            pages.length > 0 && (<Swiper autoplay={true} modules={[Navigation, Autoplay]} navigation>
                {
                    pages.map((page) => <SwiperSlide key={page.id}>
                        <CollagePageComponent page={page} gridClass={gridClass} />
                    </SwiperSlide>)
                }
            </Swiper>)
        }
        
    </div>
};


export default Collage;