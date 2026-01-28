<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendWelcomeEmailRequest;
use App\Models\InvitationLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
                'uuid' => $uuid,
                'email' => $email,
                'status' => 'pending',
                'expires_at' => $expiresAt,
            ]);

            $signedURL = URL::temporarySignedRoute(
                'hiring.form.view',
                $expiresAt,
                ['uuid' => $uuid]
            );

            // Enviar datos a n8n para que maneje el envío del correo
            $webhookUrl = env('N8N_INVITATION_WEBHOOK_URL');

            if (!$webhookUrl) {
                throw new \Exception('N8N_INVITATION_WEBHOOK_URL no está configurada en .env');
            }

            $response = Http::timeout(10)->post($webhookUrl, [
                'email' => $email,
                'invitation_link' => $signedURL,
                'expires_at' => $expiresAt->format('Y-m-d H:i:s'),
            ]);

            if ($response->failed()) {
                throw new \Exception('Error al enviar webhook a n8n: ' . $response->body());
            }

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
