<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Mail\ContactFormMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function send(ContactFormRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Verificar reCAPTCHA
//        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
//            'secret' => env('RECAPTCHA_SECRET_KEY'),
//            'response' => $request->input('recaptcha_token')
//        ]);
//
//        $responseData = $response->json();
//
//        if (!$responseData['success']) {
//            return response()->json([
//                'error' => 'Verificación reCAPTCHA fallida',
//                'recaptcha_response' => $responseData // Esto te mostrará detalles del error
//            ], 403);
//        }

        try {
            Mail::to('info@manuleyva.com')->send(new ContactFormMail($validated));
            return response()->json([
                'message' => 'Correo enviado correctamente',
                'data' => $validated
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Hubo un problema al enviar el correo'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
