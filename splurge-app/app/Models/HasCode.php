<?php

namespace App\Models;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

trait HasCode
{
    static $CODE_BASE = 'ABCGHYAOUU01234567890PLMNOQRSTUWXYZ';

    public function generateCode()
    {
        return $this->generateImpl(0);
    }

    private function generateImpl($attempt)
    {
        $samples = static::generateSamples();
        $col = $this->getCodeColumn();
        $table = $this->getTable();

        $existing = DB::table($table)->select($col)->whereIn($col, $samples)->pluck($col);

        $lookup = Arr::first($samples, fn ($n) => !$existing->contains($n));
        if (is_null($lookup)) {
            if ($attempt === 5) {
                throw new Exception("Failed to generate a unique code for $table");
            }
            return $this->generateImpl($attempt + 1);
        }
        return $lookup;
    }

    protected function getCodeColumn()
    {
        return "code";
    }

    private static function generateSamples($count = 6, $code_length = 8)
    {
        $buffer = [];
        $limit = strlen(self::$CODE_BASE);
        for ($i = 0; $i < $count; $i++) {
            $item = '';
            for ($j = 0; $j < $code_length; $j++) {
                $index = random_int(0, $limit) % $limit;
                $item .= static::$CODE_BASE[$index];
            }
            $buffer[] = $item;
        }

        return $buffer;
    }
}
