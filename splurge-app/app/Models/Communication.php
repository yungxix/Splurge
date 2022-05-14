<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Communication extends Model
{
    use HasFactory;

    public function previewText($length = 200) {
        $index1 = stripos($this->content, "<body");

        $index2 = stripos($this->content, "</body");

        $body_offset = stripos($this->content, ">", $index1 + 1);

        $pure = strip_tags(Str::substr($this->content, $body_offset + 1, $index2 - $body_offset));

        $pure = preg_replace('/\n{3,}/', "\n", $pure);

        return  Str::limit($pure, $length);
    }

    public function __get($key)
    {
        if ("preview" === $key) {
            return $this->previewText();
        }
        return parent::__get($key);
    }

    public function channel() {
        return $this->morphTo('channel');
    }
}
