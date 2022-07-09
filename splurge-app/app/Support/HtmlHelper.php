<?php
namespace App\Support;

use Illuminate\Support\HtmlString;

use Illuminate\Support\Arr;

use Illuminate\Support\Facades\Request;


class HtmlHelper {
    public static function toParagraphs($text, $class_name = NULL) {
        if (is_null($text)) {
            return '';
        }
        $class_str = is_null($class_name) ? '' : e($class_name);

        $lines1 = array_filter(preg_split("/\r\n|\n|\r/", e($text)), fn ($str) => !empty($str));

        $lines =  implode("", array_map(function ($str) use ($class_str) {
            return "<p class=\"$class_str\">$str</p>";
        }, $lines1));

        return new HtmlString($lines);
    }

    public static function insertServiceLinks($items, $services) {
        $result = [];

        $map_function = function ($service) {
            return [
                'text' => $service->name,
                'url' => route('services.show', ['service' => $service->id]),
                'active' => Request::is('/services/' . $service->id . '*')
            ];
        };

        
        $service_items = is_array($services) ? array_map($map_function, $services) : $services->map($map_function)->all();

        foreach ($items as $item) {
            if (is_null($item)) {
                continue;
            }
            if (is_array($item)) {
                $result[] = $item;
            }
            

            if ((is_string($item) && preg_match('/service/', $item)) || (is_array($item) && preg_match('/services/i', $item['url']))) {
                foreach ($service_items as $si) {

                    $result[] = $si;
                }
            }
        }

        return $result;


    }

    public static function renderParagraphs($text, $class_name = NULL) {
        return static::toParagraphs($text, $class_name);
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

    public static function toOrdinalString(int $value) {
        $mod = $value % 10;
        switch ($mod) {
            case 1:
                return "st";
            case 2:
                return "nd";
            case 3:
                return "rd";
            default:
                return "th";
        }
    }

    public static function renderAmount($amount) {
        if (is_null($amount)) {
            return '';
        }
        return new HtmlString(sprintf('&#8358;%s',  number_format($amount, 2)));
    }

    public static function renderListSeparator($index, $count) {
        if ($index > 0) {
            if ($index === $count - 1) {
                return ' and ';
            }
            return ', ';
        }
        return '';
    }

    public static function renderList($items, $tag = 'span', $class_name = '', $getter = NULL) {
        $size = count($items);
        $buffer = '';
        $tag = e($tag ?: 'span');

        foreach ($items as $index => $item) {
            $separator = static::renderListSeparator($index, $size);
            $text = '';
            if (!is_null($getter)) {
                if (is_callable($getter)) {
                    $text = $getter($item, $index);
                } else {
                    $text = Arr::get($item, $getter);
                }
            } else {
                $text = (string)$item;
            }
            $buffer .= sprintf('%s<%s class="%s">%s</%s>',
             $separator, $tag, e($class_name), e($text), $tag);
        }
        return new HtmlString($buffer);
    }
}