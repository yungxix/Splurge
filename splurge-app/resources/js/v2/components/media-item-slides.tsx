import React, {FC, useEffect, useState} from "react";


import { Swiper, SwiperSlide } from 'swiper/react';

import { Navigation, Autoplay } from 'swiper';

import {
    chunk
} from "lodash";


import 'swiper/css';


import 'swiper/css/navigation';

import 'swiper/css/autoplay';

interface SlideItem {
    caption: string; image_url: string;
}

export interface  SlidesProps {
    items: SlideItem[];
}

const determineChunkSize = () => {
    const wSize = window.innerWidth;
   
    if (wSize >= 1024) {
        return 4;
    }

    if (wSize > 850) {
        return 3;
    }

    return 2;

};

const SlidesRenderer: FC<SlidesProps> = (props) => {
    const [chunks, setChunks] = useState<Array<Array<SlideItem>>>(() => {
        const size = determineChunkSize();
        return chunk(props.items, size);
    });
    useEffect(() => {
        const handler = () => {
            const size = determineChunkSize();
            setChunks(chunk(props.items, size));
        };
        window.addEventListener('resize', handler, false);
        return () => {
            window.removeEventListener('resize', handler);
        };
    }, [props.items, setChunks]);
    return <Swiper modules={[Autoplay, Navigation]} autoHeight={true} navigation autoplay={true}>
        {
            chunks.map((group, i) => (<SwiperSlide key={`chukg_i_${i}`}>
                <div className="flex flex-row justify-center gap-2">
                    {
                        group.map((item, j) => (<figure key={`item_${i}_${j}`} className="media-item-figure h-full">
                            <img src={item.image_url} className="block h-5/6"/>
                            <figcaption>{item.caption}</figcaption>
                        </figure>))
                    }
                </div>
            </SwiperSlide>))
        }
    </Swiper>

};

export default SlidesRenderer;