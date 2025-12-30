<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendWelcomeEmailRequest;
use App\Mail\WelcomeEmail;
use App\Models\InvitationLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SendWelcomeEmailController extends Controller
{
    public function index()
    {
        return view('emails.send');
    }

    public function sendWelcomeEmail(SendWelcomeEmailRequest $request): JsonResponse
    {
        $uuid = (string) Str::uuid();
        $email = $request->email;
        $expiresAt = now()->addDays(5);

        try {
            $invitation = InvitationLink::create([
                'uuid'       => $uuid,
                'email'      => $email,
                'status'     => 'pending',
                'expires_at' => $expiresAt,
            ]);

            $signedURL = URL::temporarySignedRoute(
                'hiring.form.view',
                $expiresAt,
                ['uuid' => $uuid]
            );

            Mail::to($email)->send(
                new WelcomeEmail($invitation, $signedURL)
            );

            return response()->json([
                'success' => true,
                'message' => 'Correo enviado correctamente.',
            ]);

        } catch (\Throwable $e) {
            Log::error('Error al enviar correo de bienvenida', [
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al enviar el correo.',
            ], 500);
        }
    }
}
