<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Requests\UsuarioRequest;
use App\Models\Usuario;
use App\Models\Perfil;

class UsuarioController extends Controller
{
    public function editar($id){
        try {
            //VERIFICAR SE FOI RECEBIDO O ID DO USUARIO
            if(!empty($id)){
                //BUSCAR USUARIO
                $usuario = Usuario::findOrFail($id);
                //BUSCAR PERFIS
                $perfis = Perfil::select('perfil')->get();
                //RETORNAR USUARIO ENCONTRADO
                return response()->json(['usuario' => $usuario, 'perfis' => $perfis], 200);
            }
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Usuario não encontrado.'], 400);
        }catch(\Exception $e) {
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro ao Editar][Usuario]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Usuario não encontrado.'], 400);
        }
    }

    public function salvar(Request $request){
        //VERIFICAR DADOS DO FORMULARIO
        $isValid = !empty($request['name']) && !empty($request['email']) ? true : false;
        //INICIALIZAR TRANSAÇÃO NO DB
        DB::beginTransaction();
        try {
            //VERIFICAR SE FOI RECEBIDO O USUARIO
            if($isValid == true){
                //SEPARAR DADOS DA REQUEST
                $data = [
                    'id' => $request['id'],
                    'name' => $request['name'],
                    'email' => $request['email'],
                ];
                //VERIFICAR SE FOI RECEBIDO PERFIL
                if(!empty($request['perfil'])){
                    //BUSCAR PERFIL NO BANCO
                    $perfil = Perfil::where('perfil', $request['perfil'])->first();
                    //VERIFICAR SE PERFIL EXISTE
                    if(!empty($perfil)){
                        $data['perfil_id'] = $perfil->id;
                    }
                }
                //VERIFICAR SE FOI RECEBIDA SENHA
                if(!empty($request['password'])){
                    $data['password'] = bcrypt($request['password']);
                }
                //VERIFICAR SE É UMA EDIÇÃO OU CRIAÇÃO
                if(isset($request['id']) && !empty($request['id'])){
                    //BUSCAR E ATUALIZAR USUARIO
                    $usuario = Usuario::findOrFail($request['id']);
                    $usuario->update($data);
                }else{
                    //CRIAR NOVO USUARIO
                    Usuario::create($data);
                }
                //CONSOLIDAR OPERAÇÃO
                DB::commit();
                //RETORNAR MENSAGEM DE SUCCESSO
                return response()->json(['message' => 'Usuário salvo com sucesso.'], 200);
            }
            //DESFAZER OPERAÇÃO
            DB::rollback();
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao tentar salvar o Usuário.'], 400);
        }catch(\Exception $e) {
            //DESFAZER OPERAÇÃO
            DB::rollback();
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro ao Salvar][Usuario]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao tentar salvar o Usuário.'], 400);
        }
    }

    public function deletar($id){
        //INICIALIZAR TRANSAÇÃO NO DB
        DB::beginTransaction();
        try{
            //VERIFICAR SE EXISTE UM ID
            if(!empty($id)){
                //BUSCAR USUARIO E EXCLUIR
                $usuario = Usuario::findOrFail($id);
                $usuario->delete();
                //CONSOLIDAR OPERAÇÃO
                DB::commit();
                //RETORNAR MENSAGEM DE SUCCESSO
                return response()->json(['message' => 'Usuário deletado com sucesso.'], 200);
            }
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Usuario não encontrado!'], 400);
        }catch(\Exception $e) {
            //DESFAZER OPERAÇÃO
            DB::rollback();
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro ao Deletar][Usuario]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao tentar excluir o Usuário.'], 400);
        }
    }
}
