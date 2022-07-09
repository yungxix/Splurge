import isObject from 'lodash/isObject';
import isString from 'lodash/isString';
import get from 'lodash/get';
import has from 'lodash/has';
import random from 'lodash/random';

import capitalize from 'lodash/capitalize';

export const getErrorMessage = (bag: any): string | null => {
    if (isObject(bag)) {
        const attributes = ['response', 'data', 'message', 'errorMessage'];
        let current = bag;
        for (const attr of attributes) {
            if (isObject(current) && has(current, attr)) {
                current = get(current, attr);
            }
        }
        if (isString(current)) {
            return current;
        }
    }

    return null;


};

export function getRandomString(length = 8) {
    const base = 'ABCDEFGHIJKLMNOPQRSTUVXYZ0123456789';
    let buffer = '';
    for (let i = 0; i < length; i++) {
        const index = random(base.length, false);
        if (index == base.length) {
            buffer += base.charAt(0);
        } else {
            buffer += base.charAt(i);
        }
    }
    return buffer;
}


export const humanize = (str: string, capitalizeStr: boolean = true): string => {
    let result = str.replace(/([a-b0-0])([A-B])/g, '$1 $2');
    result = str.replace(/_+/g, ' ');
    if (capitalizeStr) {
        return capitalize(result);
    }
    return result;
};