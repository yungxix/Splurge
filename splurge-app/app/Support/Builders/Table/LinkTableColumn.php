<?php

namespace App\Support\Builders\Table;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LinkTableColumn extends TableColumn {
    private $urlFactory;
    private $anchorText;
    private $text;
    private $target;
    private $htmlAttrs;

    public function __construct(array $options)
    {
        $this->text = Arr::get($options, 'text', Arr::get($options, 'name', ''));
        $this->urlFactory = Arr::get($options, 'url');
        $this->anchorText = Arr::get($options, 'attribute', Arr::get($options, 'item_text'));
        $this->target = e(Arr::get($options, 'target', '_self'));
        $this->htmlAttrs = Arr::get($options, 'attributes', Arr::get($options, 'html_attributes', [])); 
    }

    public function render($model, $row_loop = NULL, $column_loop = NULL)
    {
        $url = call_user_func($this->urlFactory, $model);
        $itemText = e(is_string($this->anchorText) ? Arr::get($model, $this->anchorText) : call_user_func($this->anchorText, $model));
        $title = e(Arr::get($this->htmlAttrs, 'title', ''));
        $class = e(Arr::get($this->htmlAttrs, 'class', ''));
        return Str::of(sprintf('<a href="%s" class="%s" title="%s" target="%s">%s</a>',
            $url,
            $class,
            $title,
            $this->target,
            $itemText
        ))->toHtmlString();
    }

    public function renderHead($loop)
    {
        return $this->text;       
    }

    public static function of(array $options) {
        return new LinkTableColumn($options);
    }
}