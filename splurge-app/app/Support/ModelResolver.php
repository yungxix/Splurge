<?php

namespace App\Support;

class ModelResolver {
    public static function fromRoute($arg, $model_class) {
        if (is_numeric($arg) || is_string($arg)) {
            return app($model_class)->findOrFail($arg);
        }
        return $arg;
    }
}