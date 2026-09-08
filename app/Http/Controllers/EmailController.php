<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;
use App\Models\Recipient;

class EmailController extends Controller
{
    //
    public function index(Request $request){

        // Get stats of emails
        $stats = [
            'total' => Email::count(),
            'forwarded' => Email::forwarded()->count(),
            'pending' => Email::pending()->count(),
            'errors' => Email::where('status', 'error')->count(),
        ];

        // Get active recipients for the filter dropdown
        $active = Recipient::where('active', true)->get();

        // Apply filters if any
        // $filters = $request->only(['sender', 'subject', 'forwarded_to', 'from_date', 'to_date']);
        // $emails = Email::filter($filters)->latest()->paginate(10)->withQueryString();

        $emails = Email::filter($request->all())
        ->latest()
        ->paginate(15)
        ->withQueryString();

        // 🔍 INSPECCIÓN 1: ¿Llegan los elementos al controlador?
        // dd($emails->toArray());


        return view('emails.index', compact('emails', 'stats', 'active'));
    }

    public function show(Email $email){

        return view('emails.show', compact('email'));
    }
}
