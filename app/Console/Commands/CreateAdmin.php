<?php

namespace App\Console\Commands;

use App\Databases\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    // Definir a assinatura e descrição do comando
    protected $signature = 'cria:usuario-admin';
    protected $description = 'Cria um usuário administrador com informações padrão';

    public function handle()
    {
        $this->info('Iniciando a criação do usuário administrador...');

        // Verifica se já existe um usuário com o mesmo CPF ou e-mail
        $existingUser = User::where('cpf', '11111111111')
            ->orWhere('email', 'admin@gmail.com')
            ->first();

        if ($existingUser) {
            $this->error('Já existe um usuário com o mesmo CPF ou e-mail.');
            return;
        }

        // Criar um novo usuário
        $user = new User([
            'name' => 'admin',
            'cpf' => '11111111111',
            'email' => 'admin@gmail.com',
            'permission' => 'admin',
            'password' => Hash::make('123123123'), // Senha criptografada
        ]);

        // Salvar o usuário
        if ($user->save()) {
            $this->info('Usuário administrador criado com sucesso!');
        } else {
            $this->error('Erro ao criar o usuário administrador.');
        }
    }
}
