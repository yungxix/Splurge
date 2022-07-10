import 'swiper/css';

import 'swiper/css/navigation';

import React, {FC, useState} from "react";
import { Swiper as ReactSwiper, SwiperSlide } from 'swiper/react';
import Swiper, { Navigation} from 'swiper';
import classNames from "classnames";
import Lines from "./lines";

import Amount from "./amount";

import { ArrowRightIcon } from '@heroicons/react/solid';

import { ServiceTier } from "./booking-tier-selector";


export interface ServicePackagesProps {
    packages: Array<ServiceTier>;
    bookUrlTemplate?: string;
}

const PackageView: FC<{tier: ServiceTier, renderName: boolean, bookUrlTemplate?: string}> = ({tier, renderName, bookUrlTemplate}) =>
 (<div className='service-package'>

    <div className='service-package-content'>
    {
        tier.image_url && (<div>
            <img className="w-full" alt={`${tier.name} package picture`} src={tier.image_url} />
        </div>)
    }
    <div className="mb-2 p-4">
        {
            renderName && (
                <h4 className="text-xl text-center md:text-2xl font-semibold">
                    {tier.name}
                </h4>
            )
        }
        {
            tier.price && (<div className="text-center mt-2">
                <Amount value={tier.price} className="text-lg md:text-xl" />
            </div>)
        }
    </div>
    <div className="px-4 py-4">
        <Lines text={tier.description} />
    </div>
    {
        tier.options && (<div className="ml-4 mb-4">
            <ol className="list-none">
            {
                tier.options.map((opt) => (<li>
                    <div className="flex flex-row items-center py-2">
                          <ArrowRightIcon className='w-6 h-6 mr-4' />  
                          <p>
                              {opt.text}
                          </p>
                    </div>
                </li>))
            }
            </ol>
        </div>)
    }

    {
        bookUrlTemplate && (<div className='flex flex-row justify-end items-center px-8 mb-4'>
            <a className='link' href={bookUrlTemplate.replace(':tier', String(tier.id))}>
                Book &quot;{tier.name}&quot; package
            </a>
        </div>)
    }

    {
        tier.footer_message && (<div className='p-4'>
            <Lines text={tier.footer_message} />
        </div>)
    }
    </div>
    
   
</div>);


export default function ServicePackagesSlides(props: ServicePackagesProps) {
    const [selectedIndex, setSelectedIndex] = useState(0);
    const [swiper, setSwiper] = useState<Swiper | null>();
    

    return <div>
        <div className='flex flex-row justify-center items-center gap-x-6 flex-wrap gap-y-4 mb-4'>
            {
                props.packages.map((tier, i) => (<a className={classNames('text-splarge-400 text-xl md:text-2xl', {
                    'font-bold': selectedIndex === i,
                    'text-splarge-700': selectedIndex === i,
                    'underline': selectedIndex === i
                })} key={tier.id} onClick={(e) => {
                    if (swiper && i !== selectedIndex) {
                        swiper.slideTo(i);
                    }
                }}>
                    {tier.name}
                </a>))
            }
        </div>

        <ReactSwiper modules={[Navigation]} navigation centeredSlides={true} onSlideChangeTransitionEnd={(sl) => {
            setSelectedIndex(sl.activeIndex)
        }} onAfterInit={(sw) => {
            setSwiper(sw);
        }}>
            {
                props.packages.map((tier) => <SwiperSlide key={`tier_slide_${tier.id}`}>
                    <PackageView renderName={false} tier={tier} bookUrlTemplate={props.bookUrlTemplate} />
                </SwiperSlide>)
            }

        </ReactSwiper>

    </div>
}