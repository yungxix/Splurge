<?php

namespace App\Support\Builders\Table;

use Illuminate\Support\HtmlString;
use App\Support\Builders\Table\LinkTableColumn;
use App\Support\Builders\Table\ImageTableColumn;
use App\Support\Builders\Table\DefaultTableColumn;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TableBuilder  {

    private $data;
    private $columns;
    private $paginated;
    private $emptyView;
    private $query;
    private $sortKey;
    private $url;
    private $caption;

    public function __construct()
    {
        $this->columns = collect([]);
        $this->data = [];
        $this->sortKey = 'sort';
    }

    public function withCaption($caption) {
        $this->caption = $caption;
        return $this;
    }

    public function getCaption() {
        return $this->caption;
    }

    public function hasCaption() {
        return !is_null($this->caption);
    }

    public function withSortKey($key) {
        $this->sortKey = $key ?: 'sort';
        return $this;
    }

    public function getSortUrl(TableColumn $column) {
        if (!$column->isSorted()) {
            return '#';
        }
        // Clone the query first
        $q = array_merge([],  $this->query ?: [], []);
        $order = $this->getSortOrder($column);
        $q[$this->sortKey] = sprintf('%s %s', $column->getSortKey(), $order);
        return url(sprintf('%s?%s', $this->url, Arr::query($q)));
    }

    public function getSortOrder(TableColumn $column) {
        $current_order = Arr::get($this->query ?: [], $this->sortKey);
        if (is_null($current_order)) {
            return $column->getDefaultSortOrder() ?: 'asc';
        }
        $parts = explode(' ', $current_order);
        if ($parts[0] !== $column->getSortKey()) {
            return $column->getDefaultSortOrder() ?: 'asc';
        }
        return $parts[1] === 'desc' ? 'asc' : 'desc';
    }

    public function useRequest(Request $request) {
        $this->query = $request->query();
        $this->url = $request->path();
        return $this;
    }

    public static function of($data, $columns = NULL) {
        $builder = new TableBuilder();
        $builder->setData($data);
        if (!is_null($columns)) {
            $builder->addColumns($columns);
        }
        return $builder;
    }

    public function isSorted($column) {
        if (!$column->isSorted()) {
            return false;
        }
        if (is_null($this->query)) {
            return false;
        }
        $sort = Arr::get($this->query, $this->sortKey);
        return strpos($sort, $column->getSortKey()) !== FALSE;

    }

    public static function ofSchema($data, $columns) {
        $builder = new TableBuilder();
        $builder->setData($data);
        foreach ($columns as $col) {
            switch (Arr::get($col, 'type', 'text')) {
                case 'link':
                    $builder->addColumn(LinkTableColumn::of($col)->withOptions($col));
                    break;
                case 'image':
                    $builder->addColumn(ImageTableColumn::of($col)->withOptions($col));
                    break;
                default:
                    $builder->addColumn(DefaultTableColumn::of($col)->withOptions($col));
                    break;
            }
        }
        return $builder;
    }

    public function isEmpty() {
        return count($this->getData()) === 0;
    }


    public function render()
    {
        if (count($this->getData()) === 0 && !is_null($this->emptyView)) {
            return new  HtmlString(view($this->emptyView)->render());
        }
        // if (empty($this->columns)) {
        //     return '';
        // }
        return new HtmlString(view('partials.table.table', ['table' => $this])->render());
    }

    public function addColumn($column, $attribute = NULL) {
        if (is_null($attribute)) {
            if (is_array($column)) {
                $this->columns->push(DefaultTableColumn::of($column));
            } else if (is_string($column)) {
                $this->columns->push(DefaultTableColumn::ofAttribute($column));
            } 
            else {
                $this->columns->push($column);
            }
        } else {
            $this->columns->push(DefaultTableColumn::simple($column, $attribute));
        }
        
        return $this;
    }

   
    public function addColumns($columns) {
       foreach ($columns as $col) {
           $this->addColumn($col);
       }
        return $this;
    }

    public function getColumns() {
        return $this->columns;
    }

    public function getData() {
        return $this->data ?: [];
    }

    public function setData($data) {
        $this->data = $data;
        return $this;
    }

    public function setPaginated($paginated) {
        $this->paginated = $paginated;
    }

    public function paged() {
        $this->paginated = true;
        return $this;
    }

    public function isPaginated() {
        return $this->paginated === true;
    }

    public function setEmptyView($view) {
        $this->emptyView = $view;
    }

    public function getEmptyView() {
        return $this->emptyView;
    }

    public function withEmptyView($view) {
        $this->emptyView = $view;
        return $this;
    }
}