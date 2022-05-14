<?php

namespace App\View\Components\Admin\Widgets;

use App\Repositories\BookingsRepository;
use Illuminate\View\Component;

use Illuminate\Support\Carbon;

class RecentBookings extends Component
{
    public $showUpcomming;
    private $repository;
    private $limit;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        BookingsRepository $repository,
     bool $showUpcomming = true,
     int $limit = 5)
    {
        $this->showUpcomming = $showUpcomming;
        $this->repository = $repository;
        $this->limit = $limit;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $view_bag = [
            'recent_bookings' => $this->repository->getRecentBookings($this->limit),
            'recent_url' => $this->repository->createRecentBookingsUrl()
        ];

        if ($this->showUpcomming) {
            $view_bag['upcoming_bookings'] = $this->repository->getFutureBookings($this->limit);
            $view_bag['upcoming_url'] = $this->repository->createFutureBookingsUrl();
        }

        return view('components.admin.widgets.recent-bookings', $view_bag);
    }
}
