import React, {FC} from "react";


import { Swiper, SwiperSlide } from 'swiper/react';

import { EffectFade } from 'swiper';


import 'swiper/css';

import 'swiper/css/effect-fade';

export interface  SlidesProps {
    items: Array<{caption: string; image_url: string}>
}


const SlidesRenderer: FC<SlidesProps> = (props) => {
    return <Swiper modules={[EffectFade]} effect="fade" autoplay={true}>
        {
            props.items.map((item, i) => (<SwiperSlide key={`slide_${i}`}>
                <figure className="media-item-figure">
                    <img src={item.image_url} />
                    <figcaption>{item.caption}</figcaption>
                </figure>
            </SwiperSlide>))
        }
    </Swiper>

};

export default SlidesRenderer;