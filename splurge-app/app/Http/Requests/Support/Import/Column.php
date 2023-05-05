<?php 

namespace App\Http\Requests\Support\Import;

use Illuminate\Support\Str;


class Column {
    private $aiiases;
    private $index = -1;
    private $modelAttribute;

    public function __construct(array $aliases, string $modelAttribute)
    {
        $this->modelAttribute = $modelAttribute;
        $this->aiiases = $aliases;
    }

    public function isFound(): bool {
        return $this->index !== -1;
    }

    public function lookup($cells): bool {
        if ($this->isFound()) {
            return true;
        }
        foreach ($cells as $index => $cell) {
            $value = Str::lower((string)$cell->getValue());
            if (in_array($value, $this->aiiases)) {
                $this->index = $index;
                return true;
            }
        }
        return false;
    }

    public function getIndex() {
        return $this->index;
    }

    public function valueFrom($cells) {
        if (!$this->isFound()) {
            return null;
        }
        return $this->parseValue($cells[$this->index]->getValue());
    }

    public function writeTo($model, $cells) {
        if (!$this->isFound()) {
            return;
        }
        $model[$this->modelAttribute] = $this->valueFrom($cells);
    }

    protected function parseValue($value) {
        if (is_null($value)) {
            return null;
        }
        return trim($value);
    }
}