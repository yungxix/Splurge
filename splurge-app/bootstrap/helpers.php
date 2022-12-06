<?php
use Carbon\Carbon;
if (!function_exists('splurge_asset')) {
    function splurge_asset($url) {
        if (preg_match('/^http/', $url)) {
            return $url;
        }
        return asset($url);
    }
}

if (!function_exists('splurge_date_to_string')) {
    function splurge_date_to_string($date, $date_format = NULL) {
        if (is_null($date)) {
            return null;
        }
        
        if (is_string($date)) {
            return $date;
        }
        
        $date_format = $date_format ?: 'Y-m-d H:i:s';

        if ($date instanceof Carbon) {
            return $date->format($date_format);
        }

        return date_format($date, $date_format);
    }
}

if (!function_exists('splurge_customer_full_name')) {
    function splurge_customer_full_name($customer) {
        if (is_null($customer)) {
            return '';
        }
        return sprintf('%s %s', $customer->first_name, $customer->last_name);
    }
}