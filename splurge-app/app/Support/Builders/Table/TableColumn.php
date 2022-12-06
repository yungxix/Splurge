<?php

namespace App\Support\Builders\Table;


use Illuminate\Support\Arr;

abstract class TableColumn {
    private $sorted;
    private $sortKey;
    private $defaultSortOrder;

    public function getTag() {
        return 'td';
    }

    public abstract function render($model, $row_loop = NULL, $column_loop = NULL);

    public abstract function renderHead($loop);

    public function getCellClass($row, $column) {
        return '';
    }

    public function withOptions(array $options) {
        if (isset($options['sortKey'])) {
            return $this->sortsBy($options['sortKey'], Arr::get($options, 'order', 'asc'));
        }
        return $this;
    }

    public function sortsBy($key, $default_order = 'asc') {
        $this->sorted = true;
        $this->sortKey = $key;
        $this->defaultSortOrder = $default_order;
        return $this;
    }

    public function getDefaultSortOrder() {
        return $this->defaultSortOrder;
    }

    public function isSorted() {
        return $this->sorted;
    }

    public function getSortKey() {
        return $this->sortKey;
    }

}