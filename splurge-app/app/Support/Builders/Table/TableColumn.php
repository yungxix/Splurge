<?php

namespace App\Support\Builders\Table;

abstract class TableColumn {
    public function getTag() {
        return 'td';
    }

    public abstract function render($model, $row_loop = NULL, $column_loop = NULL);

    public abstract function renderHead($loop);

    public function getCellClass($row, $column) {
        return '';
    }

}