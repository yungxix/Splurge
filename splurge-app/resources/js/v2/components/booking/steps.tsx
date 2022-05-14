import React, {useContext} from "react";
import { StepContext } from "./context";
import Step2 from './step2';
import Step1 from "./step1_1";
import Step3 from "./step3";

export default function StepsRenderer() {
    const context = useContext(StepContext);

    switch (context.step) {
        case 1:
            return <Step2 />
        case 2:
            return <Step3 />
        default:
            return <Step1 />    
    }
}