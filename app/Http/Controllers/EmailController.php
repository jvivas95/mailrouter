<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Email;

class EmailController extends Controller
{
    //
    public function index(){
        $emails = Email::latest()->paginate(20);
        $stats = [
            'total' => Email::count(),
            'forwarded' => Email::forwarded()->count(),
            'pending' => Email::pending()->count(),
            'errors' => Email::where('status', 'error')->count(),
        ];

        return view('emails.index', compact('emails', 'stats'));
    }

    public function show(Email $email){

        return view('emails.show', compact('email'));
    }
}
