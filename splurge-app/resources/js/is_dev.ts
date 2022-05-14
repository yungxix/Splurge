declare const PRODUCTION: boolean;

export default function isDevelopment() {
    return PRODUCTION !== true;
}