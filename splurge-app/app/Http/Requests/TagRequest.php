<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Tag;

use Illuminate\Support\Arr;

class TagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'category' => 'nullable|max:25'
        ];
    }

    public function storeTag() {
        return Tag::create($this->getTagAttrs());
    }

    private function getTagAttrs() {
        $attrs = $this->safe()->only(['name', 'category']);
        if (empty(Arr::get($attrs, 'category'))) {
           return array_merge([], $attrs, ['category' => 'general']);
        }
        return $attrs;
    }

    public function updateTag(Tag $tag) {
        $tag->update($this->safe()->only(['name', 'category']));
        return $tag;
    }
}
