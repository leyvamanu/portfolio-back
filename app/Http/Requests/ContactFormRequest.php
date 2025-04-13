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
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email:rfc,dns|max:255',
            'message' => 'required|string|min:20|max:2000',
            'privacy' => 'accepted',
            'recaptcha_token' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'El nombre es obligatorio.',
            'name.string'      => 'El nombre debe ser una cadena de texto.',
            'name.min'         => 'El nombre debe tener al menos 2 caracteres.',
            'name.max'         => 'El nombre no puede tener más de 100 caracteres.',

            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'El correo electrónico debe ser válido.',
            'email.max'        => 'El correo electrónico no puede tener más de 255 caracteres.',

            'message.required' => 'El mensaje no puede estar vacío.',
            'message.string'   => 'El mensaje debe ser una cadena de texto.',
            'message.min'      => 'El mensaje debe tener al menos 20 caracteres.',
            'message.max'      => 'El mensaje no puede tener más de 2000 caracteres.',

            'privacy.accepted' => 'Debes aceptar la política de privacidad.',

            'recaptcha_token.required' => 'La verificación reCAPTCHA es obligatoria.',
            'recaptcha_token.string'   => 'La verificación reCAPTCHA debe ser un token válido.',
        ];
    }


    protected function passedValidation(): void
    {
        // Verificar el Origin
        $allowedOrigins = config('cors.allowed_origins');

        if (!in_array($this->header('Origin'), $allowedOrigins, true)) {
            abort(403, response()->json(['error' => 'Acceso no autorizado: Origin no permitido'], 403));
        }

        // Verificar reCAPTCHA con el paquete oficial
        $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));

        $resp = $recaptcha->verify(
            $this->input('recaptcha_token'),
            $this->ip()
        );

        if (!$resp->isSuccess()) {
            abort(403, response()->json([
                'error' => 'Verificación reCAPTCHA fallida',
                'recaptcha_response' => [
                    'success' => false,
                    'error-codes' => $resp->getErrorCodes(),
                ]
            ], 403));
        }

        // Verificar el score mínimo
        $minScore = 0.6;
        if ($resp->getScore() < $minScore) {
            abort(403, response()->json([
                'error' => 'Puntuación reCAPTCHA demasiado baja',
                'score' => $resp->getScore(),
            ], 403));
        }
    }
}
