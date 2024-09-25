<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

    class SolicitacaoMensagemRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'texto' => 'required',
        ];
    }

    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'texto.required' => 'O texto é obrigatório.',
        ];
    }
}

