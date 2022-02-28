<?php

namespace App\View\Components\Admin;

use App\Repositories\TagRepository;
use Illuminate\View\Component;

class TagsSelector extends Component
{

    
    private $taggable;
    private $tagRepository;
    public $name;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(TagRepository $tagRepository, $taggable, $name)
    {
        $this->taggable = $taggable;
        $this->tagRepository = $tagRepository;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $tags = $this->tagRepository->findAllWithAttachmentsFor($this->taggable);


        return view('components.admin.tags-selector', [
            'tags' =>  $tags->map(function ($model) {
                return array_merge($model->toArray(), ['attached' => !is_null($model->taggeable_id)]);
            }),
            'name' => $this->name,
            'taggable' => $this->taggable
        ]);
    }
}
