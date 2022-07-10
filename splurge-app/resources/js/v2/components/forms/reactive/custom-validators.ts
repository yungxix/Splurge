import { ValidatorFn} from 'react-reactive-form';

const PHONE_NUMBER_PATTERN = /^\+?\d+([\s-]\d+)*$/;


export const phoneNumber: ValidatorFn = (ctrl) => {
    if (!ctrl.value) {
        return null;
    }
    if (!PHONE_NUMBER_PATTERN.test(ctrl.value)) {
        return {
            phoneNumber: true
        };
    }

    return null;
};