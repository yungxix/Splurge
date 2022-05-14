import React, {FC} from "react";
import isString from "lodash/isString";
import classNames from "classnames";
export interface ContainerProps {
    label: any;
    className?: string;
    labelTarget?: string;
}

const Container: FC<ContainerProps> = (props) => (<div className={classNames("form-group", props.className)}>
     <div className="label-wrapper">
          {
              isString(props.label) && (<label htmlFor={props.labelTarget} className="control-label">
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

