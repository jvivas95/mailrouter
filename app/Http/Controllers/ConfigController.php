<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppConfig;

class ConfigController extends Controller
{
    //
    public function update(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email',
            'email_password' => 'required|string',
            'imap_host' => 'required|string',
            'smtp_host' => 'required|string',
            'check_interval' => 'required|integer|min:1|max:3600',
        ]);

        $config = AppConfig::get();

        // Merge the new values into the existing config
        $config = array_merge($config, [
            'email_address' => $request->email_address,
            'email_password' => $request->email_password,
            'imap_host' => $request->imap_host,
            'imap_port' => $request->input('imap_port', 993),
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->input('smtp_port', 587),
            'check_interval' => $request->check_interval,
        ]);

        AppConfig::set($config);

        return back()->with('success', 'Configuración actualizada');
    }
}
