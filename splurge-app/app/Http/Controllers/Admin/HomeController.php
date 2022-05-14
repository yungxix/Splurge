<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\StatsRepository;

class HomeController extends Controller
{
    private $statsRepository;
    public function __construct(StatsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }
    public function index() {
        return view("admin.screens.dashboard",
         ['stats' => $this->statsRepository->loadAdminDashboardStats()]);
    }
}
