<?php

if (!function_exists('splurge_asset')) {
    function splurge_asset($url) {
        if (preg_match('/^http/', $url)) {
            return $url;
        }
        return asset($url);
    }
}