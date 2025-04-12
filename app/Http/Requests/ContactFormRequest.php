<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ReCaptcha\ReCaptcha;

class ContactFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email:rfc,dns',
            'message' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'El nombre es obligatorio.',
            'name.string'      => 'El nombre debe ser una cadena de texto.',
            'name.max'         => 'El nombre no puede tener más de 255 caracteres.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'El correo electrónico debe ser válido.',
            'message.required' => 'El mensaje no puede estar vacío.',
            'message.string'   => 'El mensaje debe ser una cadena de texto.',
        ];
    }

    protected function passedValidation(): void
    {
        // Verificar el Origin
//        $allowedOrigins = explode(',', env('ALLOWED_ORIGINS'));
//
//        if (!in_array($this->header('Origin'), $allowedOrigins, true)) {
//            abort(403, response()->json(['error' => 'Acceso no autorizado: Origin no permitido'], 403));
//        }

        // Verificar reCAPTCHA con el paquete oficial
//        $recaptcha = new ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
//
//        $resp = $recaptcha->verify(
//            $this->input('recaptcha_token'),
//            $this->ip()
//        );
//
//        if (!$resp->isSuccess()) {
//            abort(403, response()->json([
//                'error' => 'Verificación reCAPTCHA fallida',
//                'recaptcha_response' => [
//                    'success' => false,
//                    'error-codes' => $resp->getErrorCodes(),
//                ]
//            ], 403));
//        }
//
//        // Verificar el score mínimo
//        $minScore = 0.6;
//        if ($resp->getScore() < $minScore) {
//            abort(403, response()->json([
//                'error' => 'Puntuación reCAPTCHA demasiado baja',
//                'score' => $resp->getScore(),
//            ], 403));
//        }
    }
}
