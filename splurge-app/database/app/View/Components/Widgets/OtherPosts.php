<?php

namespace App\View\Components\Widgets;

use App\Repositories\PostRepository;
use Illuminate\View\Component;

class OtherPosts extends Component
{
    private $postId;
    public $title;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($postId, $title = 'Other Events')
    {
        $this->postId = $postId;
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $rep = app()->make(PostRepository::class);
        $posts = $rep->forWidgetExcept($this->postId);
        return view('components.widgets.other-posts', ['posts' => $posts]);
    }
}
