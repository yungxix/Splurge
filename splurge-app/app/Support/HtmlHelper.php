<?php
namespace App\Support;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Arr;


class HtmlHelper {
    public static function toParagraphs(string $text, $class_name = NULL) {
        $class_str = is_null($class_name) ? '' : e($class_name);

        $lines =  implode(", ", array_map(function ($str) use ($class_str) {
            return "<p class=\"$class_str\">$str</p>";
        }, explode('\n', e($text))));

        return new HtmlString($lines);
    }
}