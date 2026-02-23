<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function landing()
    {
        return view('landing');
    }

    public function contactoForm()
    {
        return view('contacto');
    }

    // Sprint 0: solo valida y regresa mensaje (luego lo conectamos a Gmail y BD)
    public function contactoSend(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'min:2', 'max:80'],
            'email'  => ['required', 'email', 'max:120'],
            'mensaje'=> ['required', 'string', 'min:10', 'max:800'],
        ]);

        // Aquí en Sprint 0 NO enviamos correo aún.
        // Solo confirmamos al usuario:
        return back()->with('success', '¡Gracias! Tu mensaje fue recibido. Te contactaremos pronto.');
    }
}