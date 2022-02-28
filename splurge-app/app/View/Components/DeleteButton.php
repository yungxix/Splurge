<?php

namespace App\View\Components;

use Illuminate\View\Component;

use Illuminate\Support\Str;

class DeleteButton extends Component
{
    public $url;
    public $prompt;
    public $uniqueId;
    public $buttonClass;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($url, $prompt = NULL, $button_class = NULL)
    {
        $this->url = $url;
        $this->uniqueId = "u_" . Str::random();
        $this->buttonClass = $button_class ?: '';
        $this->prompt = $prompt ?: 'Are you sure you want to delete this item?';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.delete-button');
    }
}
