import React, {FC} from "react";
import isString from "lodash/isString";
export interface ContainerProps {
    label: any;
    className?: string;
}

const Container: FC<ContainerProps> = (props) => (<div className={`form-group ${props.className || ''}`}>
     <div className="label-wrapper">
          {
              isString(props.label) && (<label className="control-label">
                  {props.label}
              </label>)
          }
          {
              !isString(props.label) && (<>{props.label}</>)
          }
      </div>
      <div className="control-wrapper">{props.children}</div>

</div>);


export default Container;

