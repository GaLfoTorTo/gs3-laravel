<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Usuario;
use App\Models\Perfil;

class HomeController extends Controller
{
    public function home(){
        try {
            //BUSCAR PERFIS CADASTRADOS
            $perfis = Perfil::get()->makeHidden(['created_at','updated_at','deleted_at']);
            //BUSCAR PERFIS CADASTRADOS
            $usuarios = Usuario::with('perfil')->get()->makeHidden(['created_at','updated_at','deleted_at']);
            //RETORNAR USUARIOS ECONTRADOS
            return response()->json([
                'usuarios' => $usuarios,
                'perfis' => $perfis
            ], 200);
        }catch(\Exception $e) {
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro de listagem][Home]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //REDIRECIONAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao buscar os Usuarios.'], 400);
        }
    }
}
