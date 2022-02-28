<?php

namespace App\Support\Builders\Table;

use Illuminate\Contracts\Support\Renderable;

use Illuminate\Support\HtmlString;

class TableBuilder  {

    private $data;
    private $columns;
    private $paginated;

    public function __construct()
    {
        $this->columns = collect([]);
        $this->data = [];
    }


    public function render()
    {
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
}