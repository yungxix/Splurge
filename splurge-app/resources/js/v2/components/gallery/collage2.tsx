import React, { FC, useEffect, Component, Fragment, useRef, useState } from "react";
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation } from 'swiper';
import { Gallery } from "./types";
import classNames from "classnames";
import Lines from '../lines';
import partition from 'lodash/partition';
import noop from "lodash/noop";


import { Transition, Dialog } from '@headlessui/react';

import 'swiper/css';

import 'swiper/css/navigation';

import 'swiper/css/autoplay';

export interface CollageProps {
    item: Gallery;
    groupSize?: number;
    className?: string;
}





interface CollageItem {
    imageUrl: string;
    name: string;
    width: number;
    height: number;
    thumbnailUrl: string | null;
    spanX: number;
    spanY: number;
    offsetX: number;
    offsetY: number;
    id: any;
}

const GRID_CUTOFFS = [
    {
        maxWidth: 1800,
        columnWidth: 5
    },
    {
        maxWidth: 1200,
        columnWidth: 4
    },
    {
        maxWidth: 1024,
        columnWidth: 3
    }, {
        maxWidth: 706,
        columnWidth: 2
    }, {
        maxWidth: 506,
        columnWidth: 2
    }, {
        maxWidth: 0,
        columnWidth: 1
    },
];

const calculateUnitSize = (referenceWidth: number, columnLength: number): number => {
    if (columnLength === 1) {
        return referenceWidth;
    }
    const grid = GRID_CUTOFFS.find(x => x.columnWidth === columnLength);
    if (!grid || grid.maxWidth === 0) {
        return referenceWidth;
    }
    return Math.ceil(grid.maxWidth / grid.columnWidth);
};




const calculatePreferredGridSize = (width: number) => {
    for (const iterator of GRID_CUTOFFS) {
        if (iterator.maxWidth >= width) {
            return iterator.columnWidth;
        }
    }
    return 1;
};


const ThumbnailView2: FC<{item: CollageItem; onSelect: () => void; className?: string}> = (props) => {
    const [loaded, setLoaded] = useState(false);
    
    const imageRef = useRef<HTMLImageElement | null>(null);

    const checkLoaded = (attempt: number) => {
        if (attempt < 1 || loaded) {
            return noop;
        }
        if (imageRef.current && imageRef.current.complete ) {
           setLoaded(true);
           return noop;
        }

       const handle = setTimeout(() => {
            checkLoaded(attempt - 1);
        }, 300);

        return () => {
            clearTimeout(handle);
        };
    };

    useEffect(() => {
      return checkLoaded(4);
    }, []);
    const width = 300;
    const height = 300;
    return <a onClick={props.onSelect} className={classNames(props.className, 'cursor-pointer')}>
        <img ref={imageRef} className={classNames({
            'hidden': !loaded
        })} src={props.item.thumbnailUrl || props.item.imageUrl} alt={`${props.item.name} thumbnail`} loading='lazy' onLoad={(e) => setLoaded(true)} />
        {
            !loaded && (<svg xmlns="http://www.w3.org/2000/svg"  fill="rgb(79 10 64 / 1)"
                viewBox={`0 0 ${width} ${height}`} className={classNames('block', 'w-full', 'animate-pulse')}>
                <rect width={width} height={height}  fill="rgb(79 10 64 / 1)"></rect>
            </svg>)
        }

    </a>


}

interface ContainerProps {
    group: CollageItemGroup; selectedIndex?: number; className?: string;
    onClose: () => void;
}

class FullViewContainer extends Component<ContainerProps> {
    /**
     *
     */
    constructor(props: ContainerProps) {
        super(props);

    }

    
    render(): React.ReactNode {
        return (
            <Transition appear show={true} as={Fragment}>
            <Dialog as="div" className="relative z-10" onClose={this.props.onClose}>
              <Transition.Child
                as={Fragment}
                enter="ease-out duration-300"
                enterFrom="scale-0"
                enterTo="scale-100"
                leave="ease-in duration-200"
                leaveFrom="scale-100"
                leaveTo="scale-0"
              >
                <div className="fixed inset-0 bg-black bg-opacity-25" />
              </Transition.Child>
    
              <div className="fixed inset-0 overflow-y-auto">
                <div className="flex min-h-full items-center justify-center p-4 text-center">
                  <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0 scale-95"
                    enterTo="opacity-100 scale-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100 scale-100"
                    leaveTo="opacity-0 scale-95"
                  >
                    <div className="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
                      <Dialog.Title
                        as="h3"
                        className="text-lg font-medium leading-6 text-gray-900"
                      >
                        <a className="link text-2xl ml-2 float-right" onClick={this.props.onClose}>
                            &times;
                        </a>
                        {this.props.group.description}
                      </Dialog.Title>
                      <div className="mt-2">
                        <GroupView group={this.props.group} selectedIndex={this.props.selectedIndex} />
                      </div>
    
                      <div className="mt-4 flex flex-row items-center justify-end">
                        <button
                          type="button"
                          className="btn"
                          onClick={this.props.onClose}
                        >
                         Close
                        </button>
                      </div>
                    </div>
                  </Transition.Child>
                </div>
              </div>
            </Dialog>
          </Transition>
        )
    }
}

const FullView: FC<{ item: CollageItem; className: string }> = (props) => {
    const [loaded, setLoaded] = useState(false);
    const width = 700;
    const height = 600;
    const imageRef = useRef<HTMLImageElement | null>(null);

    const checkLoaded = (attempt: number) => {
        if (attempt < 1 || loaded) {
            return noop;
        }
        if (imageRef.current && imageRef.current.complete ) {
           setLoaded(true);
           return noop;
        }

       const handle = setTimeout(() => {
            checkLoaded(attempt - 1);
        }, 300);

        return () => {
            clearTimeout(handle);
        };
    };

    useEffect(() => {
      return checkLoaded(4);
    }, []);


    return <div className={classNames(props.className)}>
        <img ref={imageRef} className={classNames('mx-auto', {
            'block': loaded,
            'hidden': !loaded
        })} src={props.item.imageUrl} alt={`${props.item.name} picture`} loading="lazy" onLoad={(e) => setLoaded(true)} />
        {
            !loaded && (<svg xmlns="http://www.w3.org/2000/svg" fill="rgb(79 10 64 / 1)"
                viewBox={`0 0 ${width} ${height}`} className={classNames('block w-full', 'animate-pulse')}>
                <rect width={width} height={height} fill="rgb(79 10 64 / 1)"></rect>
            </svg>)
        }
        <p className="text-center mt-4">
            {props.item.name}
        </p>
    </div>
};

const GroupView: FC<{ group: CollageItemGroup; selectedIndex?: number; className?: string }> = (props) => {

    return <div>
        <div className="mb-4">
            <Lines className="text-center font-bold" text={props.group.description} />
        </div>
        <Swiper navigation centeredSlides={true} lazy={true} modules={[Navigation]} onSwiper={(sw) => {
            if (props.selectedIndex) {
                sw.slideTo(props.selectedIndex);
            }
        }}>
            {
                props.group.items.map((item) => (<SwiperSlide key={item.id}>
                    <FullView item={item} className="" />
                </SwiperSlide>))
            }
        </Swiper>
    </div>
};

interface SelectedGroup {
    show: boolean;
    item?: CollageItemGroup;
    index?: number;
}

interface SpanInfo {
    spanX: number;
    spanY: number;
}

const UNIT_SPAN: SpanInfo = { spanX: 1, spanY: 1 };

const calculateSpan = (unitWidth: number, item: CollageItem): SpanInfo => {
    if (unitWidth) {
        return UNIT_SPAN;
    }

    if (item.width > 0 || item.height > 0) {
        return {
            spanX: Math.ceil(item.width / unitWidth),
            spanY: Math.ceil(item.height / unitWidth)
        };
    }

    if (item.width > 0) {
        return {
            spanX: Math.ceil(item.width / unitWidth),
            spanY: 1
        };
    }
    if (item.height > 0) {
        return {
            spanX: 1,
            spanY: Math.ceil(item.height / unitWidth)
        };
    }

    return UNIT_SPAN;

};


interface CollageItemGroup {
    items: CollageItem[];
    description: string;
    columns: number;
    id: any;
}


const distribute = (items: CollageItem[], options: { columnSize: number; unitWidth: number }): CollageItem[] => {
    const source = items.map(x => ({ ...x, ...calculateSpan(options.unitWidth, x) }));

    const [bigOnes, others] = partition(source, x => x.spanX * x.spanY > 1);




    const result: CollageItem[] = [];

    bigOnes.forEach((item) => {
        result.push(item);
        let x = item.offsetX, y = 0;
        let count = item.spanX;

        while (others.length > 0) {
            if (x >= options.columnSize) {
                if (item.spanX < options.columnSize) {
                    x = item.spanY > y ? item.spanX : 0;
                } else {
                    y = (
                        0 === y ? item.spanY : (
                            y + 1
                        )
                    )
                }
                count += 1;
            }

            const smaller = others.shift()!;
            smaller.offsetX = x;
            smaller.offsetY = y;
            x += smaller.offsetX;

            if (count >= options.columnSize) {
                break;
            }
        }
    });

    if (others.length > 0) {
        return [...result, ...others];
    }
    return result;
};



export default function Collage(props: CollageProps) {
    const [gridItems, setGridItems] = useState<Array<CollageItemGroup>>([]);

    const [selected, setSelected] = useState<SelectedGroup>({ show: false });

    const assignGridProperties = () => {
        const bounds = document.querySelector('body')?.getBoundingClientRect();

        if (!bounds) {
            return setGridItems([]);
        }

        const gridWidth = calculatePreferredGridSize(bounds.width);

        const source = props.item.items.map(x => {
            const g: CollageItemGroup = {
                description: x.heading,
                id: x.id,
                columns: gridWidth,
                items: ((x.mediaItems || x.media_items || [])).map(item => ({
                    height: item.image_options?.height || 0,
                    width: item.image_options?.width || 0,
                    imageUrl: item.url,
                    name: item.name,
                    thumbnailUrl: item.thumbnail_url,
                    spanX: 1,
                    spanY: 1,
                    offsetX: 0,
                    offsetY: 0,
                    id: item.id
                }))
            };
            return g;
        });

        if (gridWidth < 3) {
            return setGridItems(source);
        }


        const unitWidth = calculateUnitSize(1, gridWidth);


        source.forEach(grp => {

            grp.items.forEach((item) => {
                Object.assign(item, calculateSpan(unitWidth, item));
            });

            grp.items = distribute(grp.items, { columnSize: gridWidth, unitWidth });
        });

        setGridItems(source);
    };


    useEffect(() => {
        assignGridProperties();
    }, [props.item.id]);



    return <div>
        <div className="w-full">
            {
                gridItems.map((grp) => (<div key={grp.id} className="mb-8">
                    <a onClick={(e) => {
                        setSelected({ show: true, item: grp })
                    }} className="bg-splarge-700 p-4 mb-2 text-white block cursor-pointer">
                        <Lines text={grp.description} />
                    </a>
                    <div className="flex flex-col gap-2 pb-4 flex-1 flex-wrap max-h-[500px] md:max-h-[800px] overflow-x-auto">
                        {
                            grp.items.map((item, index) => (<ThumbnailView2 key={item.id}  onSelect={() => {
                                setSelected({ show: true, item: grp, index })
                            }} item={item} className="w-1/2 md:w-1/4" />))
                        }
                    </div>
                    <div className="clear-both"></div>
                </div>))
            }
        </div>
         {selected.item && selected.show && (<FullViewContainer onClose={() => {
            setSelected({item: undefined, index: -1, show: false})
         }} group={selected.item} selectedIndex={selected.index} />)}
    </div>
}