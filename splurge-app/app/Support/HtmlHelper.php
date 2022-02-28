<?php
namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Arr;


class HtmlHelper {
    public static function toParagraphs(string $text, $class_name = NULL) {
        $class_str = is_null($class_name) ? '' : e($class_name);

        $lines1 = array_filter(preg_split("/\r\n|\n|\r/", e($text)), fn ($str) => !empty($str));

        $lines =  implode("", array_map(function ($str) use ($class_str) {
            return "<p class=\"$class_str\">$str</p>";
        }, $lines1));

        return new HtmlString($lines);
    }

    public static function translateFlashTypeToCss($type) {
        switch ($type) {
            case 'success':
                return 'bg-green-900 text-white';
            case 'warning':
            case 'warn':
                return 'bg-orange-700 text-white';
            case 'info':
                return 'bg-blue-800 text-white';
            default:
                return 'bg-gray-800 text-white';
        }
    }
}