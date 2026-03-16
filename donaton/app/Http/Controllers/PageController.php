<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Mail;


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

    
    public function contactoSend(StoreContactMessageRequest $request)
    {
        $contact = ContactMessage::create([
            'name' => $request->input('nombre'),
            'email' => $request->input('email'),
            'subject' => $request->input('asunto'),
            'message' => $request->input('mensaje'),
            'status' => 'new',
        ]);

        // Opcional: enviar correo (si ya configuraste MAIL_* en .env)
        try {
            $to = config('adrian.fuentez.2017@gmail.com'); // o tu correo destino fijo
            if ($to) {
                Mail::raw(
                    "Nuevo mensaje de contacto:\n\n".
                    "Nombre: {$contact->name}\n".
                    "Email: {$contact->email}\n".
                    "Asunto: ".($contact->subject ?? '-')."\n\n".
                    "Mensaje:\n{$contact->message}\n\n".
                    "ID: {$contact->id}\n",
                    function ($message) use ($to, $contact) {
                        $message->to($to)
                            ->subject('DONATON - Nuevo mensaje de contacto');
                    }
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Mail failed', ['error' => $e->getMessage()]);
             return back()->with('error', 'Se guardó tu mensaje, pero falló el envío de correo. Revisa el log.');
        }

        return back()->with('success', '¡Gracias! Tu mensaje fue enviado correctamente.');
    }

    

}