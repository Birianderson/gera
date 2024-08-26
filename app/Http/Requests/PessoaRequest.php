<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PessoaRequest extends FormRequest
{

    /**
     * @return string[][]
     */
    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:128'],

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
            'nome_unidade.required' => 'O campo "Nome da Unidade" é obrigatório.',
            'nome_unidade.max' => 'O campo "Nome da Unidade" deve ter no máximo 128 caracteres.',

            'responsavel.required' => 'O campo "Responsável" é obrigatório.',
            'responsavel.max' => 'O campo "Responsável" deve ter no máximo 128 caracteres.',

            'cargo_responsavel.required' => 'O campo "Cargo do Responsável" é obrigatório.',
            'cargo_responsavel.max' => 'O campo "Cargo do Responsável" deve ter no máximo 128 caracteres.',

            'endereco.required' => 'O campo "Endereço" é obrigatório.',

            'telefone.required' => 'O campo "Telefone" é obrigatório.',
            'telefone.max' => 'O campo "Telefone" deve ter no máximo 30 caracteres.',

            'email.required' => 'O campo "E-mail" é obrigatório.',
            'email.email' => 'O campo "E-mail" deve ser um e-mail válido.',
            'email.max' => 'O campo "E-mail" deve ter no máximo 128 caracteres.',

            'horario_atendimento.required' => 'O campo "Horário de Atendimento" é obrigatório.',
            'horario_atendimento.max' => 'O campo "Horário de Atendimento" deve ter no máximo 60 caracteres.',
        ];
    }
}

