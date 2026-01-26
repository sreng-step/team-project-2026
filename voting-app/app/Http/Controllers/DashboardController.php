<?php

namespace App\Http\Controllers;

use App\Models\Poll;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request) {
        $poll = Poll::find(1);
        $stats = $poll->votes()->selectRaw('selected_option, count(*) as count')->groupBy('selected_option')->get();
        return view('dashboard.index', ['poll' => $poll, 'stats' => $stats]);
    }
}
