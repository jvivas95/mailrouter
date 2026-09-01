<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Recipient;
use App\Models\User;
use App\Models\AppConfig;
use App\Models\RotationState;
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
        $state = RotationState::firstOrCreate(['id' => 1], ['current_index' => 0]);
        $active = $recipients->where('active', true)->values();

        //
        $currentRecipient = null;
        if ($active->isNotEmpty()) {
            $idx = $state->current_index % $active->count();
            $currentRecipient = $active[$idx];
        }

        $stats = [
            'total' => Email::count(),
            'forwarded' => Email::forwarded()->count(),
            'pending' => Email::pending()->count(),
            'errors' => Email::where('status', 'error')->count(),
        ];

        return view('dashboard', compact('emails', 'recipients', 'users', 'config', 'stats', 'currentRecipient'));
    }

    public function show(Email $email)
    {
        return view('emails.show', compact('email'));
    }


    public function startWorker()
    {
        $config = AppConfig::get();
        $config['active'] = true;
        AppConfig::set($config);

        // Dispatch the ProcessInboxJob to run immediately
        ProcessInboxJob::dispatchSync();

        return back()->with('success', 'Monitor de correos activado');
    }

    public function stopWorker()
    {
        $config = AppConfig::get();
        $config['active'] = false;
        AppConfig::set($config);

        return back()->with('success', 'Monitor de correos desactivado');
    }

    public function checkNow()
    {
        $config = AppConfig::get();

        if (empty($config['email_address']) || empty($config['email_password'])) {
            return back()->with('error', 'Configura el email primero');
        }

        ProcessInboxJob::dispatchSync();

        return back()->with('success', 'Revisión de correos iniciada');
    }
}
