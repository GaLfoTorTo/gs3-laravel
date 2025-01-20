<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use App\Models\Perfil;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function login(Request $request){
        //TENTAR EFETUAR LOGIN
        try {
            //VERIFICAR SE DADOS RECEBIDOS NÃO ESTÃO VAZIO
            if(!empty($request->all())){
                //REQUEST CREDENCIAIS 
                $credentials = $request->only('user', 'password');
                //INICIALIZAR USUARIO
                $usuario = null;
                //VERIFICAR TIPO DE AUTENTICAÇÃO (EMAIL OU USERNAME)
                if (filter_var($credentials['user'], FILTER_VALIDATE_EMAIL)) {
                    //TENTAR AUTENTICAR PELO EMAIL
                    $credentials = ['email' => $credentials['user'], 'password' => $credentials['password']];
                    //BUSCAR USUARIO PELO EMAIL
                    $usuario = Usuario::with('perfil')->where('email', $credentials['email'])->first();
                } else {
                    //TENTAR AUTENTICAR PELO NAME
                    $credentials = ['name' => $credentials['user'], 'password' => $credentials['password']];
                    //BUSCAR USUARIO PELO NOME
                    $usuario = Usuario::with('perfil')->where('name', $credentials['name'])->first();
                }
                //VERIFICAR SE CREDENCIAIS DO USUARIO SÃO VALIDAS
                if (!$token = JWTAuth::attempt($credentials)) {
                    //RETORNAR MENSAGEM DE ERRO
                    return response()->json(['message' => 'E-mail/Usuario ou senha estão incorretos'], 401);
                }
                //ADICIONAR TOKEN AOS DADOS DO USUARIO
                $usuario['token'] = $token;
                //RETORNAR DADOS PARA AUTENTICAÇÃO
                return response()->json([
                    'usuario' => $usuario, 
                    'message' => 'Login efetuado com sucesso!'
                ],200);
            }
        }catch(\Exception $e) {
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('auth')->error("[Erro de autenticação][Usuario][Auth]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //REDIRECIONAR PARA O FORMULÁRIO COM A MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao efetura login. Tente novamente mais tarde!'], 500);
        }
    }

    public function logout(Request $request){
        try {
            //VERIFICAR SE ID DO USUARIO FOI ENVIADO
            if(isset($request['id'])){
                //INVALIDAR TOKEN
                JWTAuth::invalidate(JWTAuth::getToken());
                //RETORNAR MENSAGEM DE LOGOUT
                return response()->json(['usuario' => null], 200);
            }else{
                //RETORNAR MENSAGEM DE LOGOUT
                return response()->json(['message' => 'Houve um erro ao efetura logout. Usuario não especificado!'], 500);
            }
        }catch(\Exception $e) {
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('auth')->error("[Erro de Logout][Usuario][Auth]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //REDIRECIONAR PARA O FORMULÁRIO COM A MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao efetura logout. Tente novamente mais tarde!'], 500);
        }
    }
}
