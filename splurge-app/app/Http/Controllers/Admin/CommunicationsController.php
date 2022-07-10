<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Communication;
use App\Repositories\CommunicationsRepository;
use Illuminate\Http\Request;

class CommunicationsController extends Controller
{
    private $repository;
    public function __construct(CommunicationsRepository $repository)
    {
        $this->repository = $repository;
    }
    public function index(Request $request) {
        return view("admin.screens.messages.index", ["messages" => $this->repository->findAll($request)
        ->select("id", "subject", "created_at", "channel_type", "internal", "sender", "receiver", "channel_id")
        ->orderBy('created_at', 'desc')->cursorPaginate(10)]);
    }

    public function show(Communication $communication) {
        return view("admin.screens.messages.show", ['message' => $communication]);
    }

    public function showContent(Communication $communication) {
        return response($communication->content);
    }
}
