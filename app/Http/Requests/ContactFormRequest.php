<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Http;

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

//        // Verificar reCAPTCHA
//        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
//            'secret' => env('RECAPTCHA_SECRET_KEY'),
//            'response' => $this->input('recaptcha_token')
//        ]);
////        dd(json_decode($response));
//        if (!$response->json('success')) {
//            abort(403, response()->json(['error' => 'Verificación reCAPTCHA fallida'], 403));
//        }
    }
}
