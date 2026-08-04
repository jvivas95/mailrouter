<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipient;

class RecipientController extends Controller
{
    //
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:recipients,email',
        ]);

        $maxIndex = Recipient::max('order_index') ?? 0;

        Recipient::create([
            'name' => $request->name,
            'email' => $request->email,
            'order_index' => $maxIndex + 1,
            'active' => true,
        ]);

        return back()->with('success', "Destinatario {$request->name} añadido");
    }

    public function destroy(int $id)
    {
        $recipient = Recipient::findOrFail($id);
        $recipient->delete();

        return back()->with('success', "Destinatario {$recipient->name} eliminado");
    }

    public function toggle(int $id)
    {
        $recipient = Recipient::findOrFail($id);
        $recipient->update(['active' => !$recipient->active]);

        $status = $recipient->active ? 'activado' : 'desactivado';
        return back()->with('success', "Destinatario {$recipient->name} {$status}");
    }

    public function reorder(Request $request)
    {
        $order = $request->input('order');

        foreach ($order as $index => $id) {
            Recipient::where('id', $id)->update(['order_index' => $index]);
        }

        return response()->json(['success' => true]);
    }

}
