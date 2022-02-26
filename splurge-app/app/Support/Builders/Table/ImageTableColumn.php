<?php
namespace App\Support\Builders\Table;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;


class ImageTableColumn extends TableColumn {

    private $text;
    private $urlGetter;
    private $imageAttributes;

    public function __construct($options, $getter)
    {
        $this->text = is_array($options) ? Arr::get($options, 'text', Arr::get($options, 'title', Arr::get($options, 'name', ''))) : $options;
        $this->urlGetter = $getter;
        $this->imageAttributes = is_array($options) ? Arr::get($options, 'attributes', $options) : [];       
    }
    

    public function render($model, $row_loop = NULL, $column_loop = NULL)
    {
        $url = is_callable($this->urlGetter) ? call_user_func($this->urlGetter, $model) : asset(Arr::get($model, $this->urlGetter));
        $class_name = Arr::get($this->imageAttributes, 'class', '');
        $alt = Arr::get($this->imageAttributes, 'alt', '');
        $template = "
        <img src=\"$url\" alt=\"$alt\" class=\"$class_name\" />
        ";
        return Str::of($template)->toHtmlString();
    }

    public function renderHead($loop)
    {
        return $this->text;    
    }
}