import React, { useRef, useState, FC, useCallback } from "react";
import CreditCardNumberInput from "../forms/credit-card-number";
import MonthAndYearInput, { MonthAndYear, getCurrent, compareMonthAndYears } from "../forms/credit-card-month-year";
import { FormBuilder, FieldGroup, FieldControl, AbstractControl, Validators, ValidatorFn } from "react-reactive-form";
import { Customer } from "./types";
import Amount from "../amount";
import FormContainer from "../forms/vertical-container";
import { getRandomString } from "../../utils";

enum FormStatus {
    normal = 0,
    sending = 1,
    succeeded = 2,
    failed = -1
}

export interface Payment {
    reference: string;
    amount: number;
    
}

export interface PaymentFormProps {
    amount: number;
    customer: Customer;
    onComplete: (payment: Payment) => void;

    onCancel: () => void;
}



interface FormDataSpec {
    creditCardNumber: string;
    expiry: MonthAndYear;
    cvc: string;
    amount: number
}

const currentMonthAndYear = getCurrent();

const validExpiry: ValidatorFn = (ctrl) => {
    if (!ctrl.value) {
        return null;
    }
    if (compareMonthAndYears(currentMonthAndYear, ctrl.value) >= 0) {
        return {
            expiry: true
        };
    }
    return null;
};

const buildCreditCardSpecControls = (defaultAmount: number): Record<keyof FormDataSpec, AbstractControl> => ({
    creditCardNumber: FormBuilder.control("", [Validators.required,
    Validators.minLength(4 * 4),
    Validators.maxLength(4 * 4 + 2),
    Validators.pattern(/^\d+$/)]),
    expiry: FormBuilder.control({ month: 0, year: 0 }, [Validators.required, validExpiry]),
    cvc: FormBuilder.control("", [Validators.required, Validators.minLength(3), Validators.maxLength(5)]),
    amount: FormBuilder.control(defaultAmount, [Validators.required, Validators.max(defaultAmount)])
});


const errorMessagesMap: Record<keyof FormDataSpec, Record<string, string>> = {
    creditCardNumber: {
        required: 'Credit card number is required',
        minLength: "Too short",
        maxLength: "Too long",
        pattern: "Invalid credit card number"
    },
    expiry: {
        required: "Expiry month and year is required",
        expiry: "Invalid expiry month and year",
    },
    cvc: {
        required: "Please enter CVV code",
        minLength: "Too long",
        maxLength: "Too short"
    },
    amount: {
        required: "Amount is required",
        max: "Must not be more than :max"
    }
};

const renderErrorMessages = (ctrl: AbstractControl, messageMap: Record<string, string>) => {
    if (ctrl.valid || !ctrl.touched) {
        return null;
    }
    const keys = Object.keys(messageMap).filter(x => ctrl.hasError(x));

    const getErrorMessages = (key: string) => {
        if (!messageMap[key]) {
            return `Error: ${key}`;
        }

        const env = ctrl.errors[key];

        const v = messageMap[key];

        return v.replace(/\:(\w+)/g, (m, g) => env[g]);
    };
    return <>
        {
            keys.map((errorKey) => (<p key={errorKey} className="text-red-700">
                {getErrorMessages(errorKey)}
            </p>))
        }
    </>

};

const PaymentFormImpl: FC<{ onAccept: (data: FormDataSpec) => void; onCancel: () => void; toPay: number }> = ({ onAccept, onCancel, toPay }) => {
    const formGroupRef = useRef(FormBuilder.group(buildCreditCardSpecControls(toPay)));

    const expiryInputRef = useRef<HTMLInputElement | null>(null);

    const cvcInputRef = useRef<HTMLInputElement | null>(null);

    return <div className="md:flex flex-row">
        <div className="md:w-1/2">
            <FieldGroup control={formGroupRef.current} render={(ctrl) => (<form noValidate onSubmit={(e) => {
                e.preventDefault();
                onAccept(ctrl.value);
            }}>
                <label className="control-label">Card number</label>
                <FieldControl name="creditCardNumber" render={(actrl: AbstractControl) => (<div>
                    <CreditCardNumberInput className="control block w-full" value={actrl.value} onCanReleseFocus={() => {
                        if (expiryInputRef.current) {
                            expiryInputRef.current.focus();
                        }
                    }} onChange={(cardNumber) => {
                        actrl.setValue(cardNumber);
                        actrl.markAsTouched();
                    }} />
                    {renderErrorMessages(actrl, errorMessagesMap.creditCardNumber)}
                </div>)}>
                </FieldControl>

                <div className="mt-4 md:flex flex-row mb-4 gap-4">
                    <div className="md:w-2/3">
                        <label className="control-label">Exp.</label>
                        <FieldControl name="expiry" render={(actrl: AbstractControl) => (<div>
                            <MonthAndYearInput ref={expiryInputRef} className="control block w-full" onCanReleaseFocus={() => {
                                if (cvcInputRef.current) {
                                    cvcInputRef.current.focus();
                                }
                            }} value={actrl.value} onChange={(e) => {
                                actrl.setValue(e);
                                actrl.markAsTouched();
                            }} />
                            {renderErrorMessages(actrl, errorMessagesMap.expiry)}
                        </div>)}>

                        </FieldControl>
                    </div>
                    <div className="md:w-2/3">
                        <label className="control-label">CVC</label>
                        <FieldControl name="cvc" render={(actrl: AbstractControl) => (<div>
                            <input placeholder="CVC" ref={cvcInputRef} className="control block w-full" type="tel" {...actrl.handler()} />
                            {renderErrorMessages(actrl, errorMessagesMap.cvc)}
                        </div>)}>
                        </FieldControl>
                    </div>
                </div>

                <div className="md:flex flex-row">
                    <div className="md:w-1/2">
                        <label className="control-label">Amount</label>
                        
                        <FieldControl name="amount" render={(actrl: AbstractControl) => (<div>
                                <input type="tel" {...actrl.handler()} className="control w-44 block" placeholder="Amount to pay" />
                                {renderErrorMessages(actrl, errorMessagesMap.amount)}
                            </div>)}>
                        </FieldControl>

                    </div>
                    <div className="md:w-1/2">
                        {/** This is just to force a vertical space */}
                        <label className="control-label">&nbsp;</label>
                        <button type="submit" disabled={ctrl.invalid} className="btn block  w-full">
                                Pay
                        </button>
                    </div>

                </div>





            </form>)}>

            </FieldGroup>
        </div>
    </div>


};
interface PaymentState {
    status: FormStatus;
    payment: {
        reference: string;
        amount: number;
    };
    errorMessage?: string;

}
export default function PaymentForm(props: PaymentFormProps) {
    const [componentState, setComponentState] = useState<PaymentState>({ status: FormStatus.normal, payment: { reference: "", amount: props.amount } });

    const processPayment = useCallback((formData: FormDataSpec) => {
        setComponentState({ ...componentState, status: FormStatus.sending });
        // Simulate payment submit
        setTimeout(() => {
            const reference = `TEST_${getRandomString(6)}_${getRandomString(8)}`;
            setComponentState({
                status: FormStatus.succeeded,
                payment: {
                    reference,
                    amount: formData.amount
                }
            });
            props.onComplete({
                amount: formData.amount,
                reference: reference
            });
        }, 2000);

    }, [props.amount]);

    if (componentState.status === FormStatus.sending) {
        return <div className="px-12 py-8 flex flex-row justify-center items-center">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
            <p>
                Processing&hellip;
            </p>
        </div>
    }

    if (componentState.status === FormStatus.failed) {
        return <div className="flex flex-row justify-start items-center">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-8 w-8 text-red-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div className="ml-8">
                <p>
                    <em>{componentState.errorMessage}</em>
                </p>
                <p>
                    <button className="btn" type="button" onClick={(e) => {
                        setComponentState({
                            status: FormStatus.normal,
                            payment: { reference: "", amount: props.amount }
                        });
                    }}>
                        Re-try
                    </button>
                </p>

            </div>
        </div>
    }

    if (componentState.status == FormStatus.succeeded) {
        return <div className="flex flex-row justify-start items-center">
            <svg xmlns="http://www.w3.org/2000/svg" className="h-8 w-8 text-green-800" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                <path strokeLinecap="round" strokeLinejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div className="ml-8">
                <p>
                    Your payment of <Amount value={componentState.payment.amount} /> has been posted succeesfully. The reference number is
                    <span className="font-bold">{componentState.payment.reference}</span>. Please ensure you keep it for future purposes
                </p>
            </div>
        </div>
    }

    return <PaymentFormImpl onAccept={processPayment} onCancel={props.onCancel} toPay={props.amount} />
}