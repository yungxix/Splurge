import { ServiceItem } from "../types";

import { DraggableProvidedDragHandleProps, DraggableProvidedDraggableProps } from "react-beautiful-dnd";

export interface ServiceItemViewProps  {
    item: ServiceItem;
    ref?: any;
    onEditRequested: (item: ServiceItem) => void;
    onDeleteRequested: (item: ServiceItem) => void;
    draggableHandleProps?: DraggableProvidedDragHandleProps;
    draggableProps?: DraggableProvidedDraggableProps; 
    className?: string;
}