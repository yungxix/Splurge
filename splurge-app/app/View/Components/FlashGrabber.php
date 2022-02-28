<?php

namespace App\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;

class FlashGrabber extends Component
{
    public $messages;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->messages = collect([]);
        foreach (['success', 'warning', 'error', 'info', 'warn'] as $type) {
            $message_key = "{$type}_message";
            if ($request->session()->has($message_key)) {
                $this->messages->push([
                    'type' => $type,
                    'message' => $request->session()->get($message_key)
                ]);
            }

        }

        foreach (['success', 'warning', 'error', 'info', 'warn'] as $type) {
            $message_key = "{$type}_important_message";
            if ($request->session()->has($message_key)) {
                $this->messages->push([
                    'type' => $type,
                    'message' => $request->session()->get($message_key),
                    'important' => true
                ]);
            }

        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.flash-grabber');
    }
}
