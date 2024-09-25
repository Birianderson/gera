<?php

namespace App\Http\Controllers\Auth;

use App\Databases\Models\Solicitacao;
use App\Databases\Models\SolicitacaoMensagem;
use App\Databases\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterSolicitacaoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     * @return \Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    protected function create(Request $request)
    {
        $params = $request->except('_token');
        $params['cpf'] = preg_replace('/\D/', '', $params['cpf']);
        $imovelIdDescriptografado = Crypt::decryptString($params['imovel_id']);
        $usuario =  new User([
            'name' => $params['name'],
            'cpf' => $params['cpf'],
            'email' => $params['email'],
            'password' => Hash::make($params['password']),
        ]);
        $usuario->save();
        $solicitacao = new Solicitacao([
            'imovel_id' => $imovelIdDescriptografado,
            'usuario_id' => $usuario->id,
            'status' => 'P'
        ]);
        $solicitacao->save();
        // Cria uma mensagem concatenada para a solicitação de cadastro de imóvel
        $mensagem = "Solicitação de cadastro de imóvel.\n";
        $mensagem .= "Usuário: {$usuario->name}\n";
        $mensagem .= "CPF: {$usuario->cpf}\n";
        $mensagem .= "Email: {$usuario->email}\n";
        $mensagem .= "Imóvel ID: {$imovelIdDescriptografado}\n";


        $this->guard()->login($usuario);
        return redirect($this->redirectTo);
    }
}
