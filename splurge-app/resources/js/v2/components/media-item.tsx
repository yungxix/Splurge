import React, {FC} from "react";
import { MediaItem } from "./gallery/types";

export interface MediaItemProps {
    item: MediaItem;
    thumbnailPreferred: boolean;
    className?: string;
}


const Renderer: FC<MediaItemProps> = (props) => {
    if (props.thumbnailPreferred && props.item.thumbnail_url) {
        return <img className={props.className || ''} src={props.item.thumbnail_url} alt={props.item.name} />
    }

    if ((/video/i).test(props.item.media_type)) {
        return <video title={props.item.name} controls src={props.item.url} className={props.className || ''}>

        </video>
    }

    return <img className={props.className || ''} src={props.item.url} alt={props.item.name} />

};

export default Renderer;