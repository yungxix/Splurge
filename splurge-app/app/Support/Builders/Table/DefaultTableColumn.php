<?php

namespace App\Support\Builders\Table;

use Illuminate\Support\Arr;

use Illuminate\Support\Str;

use Illuminate\Support\HtmlString;

class DefaultTableColumn extends TableColumn {
    private $text;
    private $modelAttribute;
    private $headerTemplate;
    private $cellTemplate;
    private $formatter;
    private $tag;
    private $viewArguments;

    public function __construct($options = [])
    {
        $this->text = Arr::get($options, 'text', Arr::get($options, 'title', Arr::get($options, 'name', '')));
        $this->modelAttribute = Arr::get($options, 'attribute');
        $this->headerTemplate = Arr::get($options, 'header_template', null);
        $this->viewArguments = Arr::get($options, 'view_arguments', []);
        $this->cellTemplate = Arr::get($options, 'view', Arr::get($options, 'template'));
        $this->formatter = Arr::get($options, 'formatter');
        $this->tag = Arr::get($options, 'tag', 'td');
    }


    public function render($model, $row_loop = NULL, $column_loop = NULL)
    {
        if (!is_null($this->cellTemplate)) {

            return view($this->cellTemplate, array_merge($this->viewArguments, ['model' => $model, 'row' => $row_loop, 'column' => $column_loop]));
        }
        if (is_callable($this->modelAttribute)) {
            return call_user_func($this->modelAttribute, $model);
        }
        if (is_null($this->formatter)) {
            return Arr::get($model, $this->modelAttribute);    
        }
        return call_user_func($this->formatter,  Arr::get($model, $this->modelAttribute));
    }

    public function renderHead($loop)
    {
        if (!is_null($this->headerTemplate)) {
            return view($this->headerTemplate, ['text' => $this->text, 'column' => $loop]);
        }
        
        return $this->text;
    }

    public function getTag()
    {
        return $this->tag;
    }

    public static function of(array $options) {
        return new DefaultTableColumn($options);
    }

    public static function simple(string $text, string $attribute) {
        return new DefaultTableColumn(['text' => $text, 'attribute' => $attribute]);
    }

    public static function ofAttribute(string $attribute) {
        $title = implode(' ', array_map(function ($text) {return Str::ucfirst($text);}, explode('_', $attribute)));
        return new DefaultTableColumn(['text' => $title, 'attribute' => $attribute]);
    }

    public static function withView($text, $view, $headerView = NULL) {
        return new DefaultTableColumn(['text' => $text, 'view' => $view, 'header_template' => $headerView]);
    }

}