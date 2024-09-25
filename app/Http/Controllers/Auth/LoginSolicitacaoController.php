<?php

namespace App\Http\Controllers\Auth;

use App\Databases\Models\Solicitacao;
use App\Databases\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class LoginSolicitacaoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function solicitacao_login(Request $request)
    {
        // Adicione sua lógica personalizada antes do login, como logs, etc.
        $this->validateLogin($request);
        $params = $request->except('_token');
        $imovelIdDescriptografado = Crypt::decryptString($params['imovel_id']);
        if ($this->attemptLogin($request)) {
            $userId = auth()->id();
            $solicitacao = new Solicitacao([
                'imovel_id' => $imovelIdDescriptografado,
                'usuario_id' => $userId,
                'status' => 'P'
            ]);
            $solicitacao->save();
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function showLoginForm(string $imovel_id)
    {
        return view('auth.solicitacao.index',compact('imovel_id'));
    }

    public function verificaConta(string $cpf)
    {
        $cpf = preg_replace('/\D/', '', $cpf);
        $user = User::query()->where('cpf',$cpf)->first();
        if($user){
            return true;
        }else{
            return false;
        }
    }

    public function verificaImovel(string $imovel_id)
    {
        $imovelIdDescriptografado = Crypt::decryptString($imovel_id);
        $solicitacao =  Solicitacao::query()->where('imovel_id',$imovelIdDescriptografado)->first();
        if($solicitacao){
            return true;
        }else{
            return false;
        }
    }
}
