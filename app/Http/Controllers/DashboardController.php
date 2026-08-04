<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Recipient;
use App\Models\User;
use App\Models\AppConfig;
use App\Jobs\ProcessInboxJob;


class DashboardController extends Controller
{
    //
    public function index()
    {
        $emails = Email::latest()->take(50)->get();
        $recipients = Recipient::orderBy('order_index')->get();
        $users = User::all();
        $config = AppConfig::get();
        $stats = [
            'total' => Email::count(),
            'forwarded' => Email::forwarded()->count(),
            'pending' => Email::pending()->count(),
            'errors' => Email::where('status', 'error')->count(),
        ];

        return view('dashboard', compact('emails', 'recipients', 'users', 'config', 'stats'));
    }

    public function startWorker()
    {
        $config = AppConfig::get();
        $config['active'] = true;
        AppConfig::set($config);

        // Dispatch the ProcessInboxJob to run immediately
        ProcessInboxJob::dispatch();

        return back()->with('success', 'Worker started and ProcessInboxJob dispatched.');


    }
}
