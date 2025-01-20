<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Perfil;

class PerfilController extends Controller
{
    public function editar($id){
        try {
            //VERIFICAR SE FOI RECEBIDO O ID DO PERFIL
            if(!empty($id)){
                //BUSCAR PERFIL
                $perfil = Perfil::findOrFail($id);
                //RETORNAR PERFIS ENCONTRADO
                return response()->json(['perfil' => $perfil], 200);
            }
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Perfil não encontrado.'], 400);
        }catch(\Exception $e) {
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro ao Editar][Perfil]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Perfil não encontrado.'], 400);
        }
    }

    public function salvar(Request $request){
        //INICIALIZAR TRANSAÇÃO NO DB
        DB::beginTransaction();
        try {
            //VERIFICAR SE FOI RECEBIDO O PERFIL
            if(isset($request['perfil'])){
                //SEPARAR DADOS DA REQUEST
                $data = [
                    'id' => $request['id'],
                    'perfil' => $request['perfil']
                ];
                //VERIFICAR SE É UMA EDIÇÃO OU CRIAÇÃO
                if(isset($request['id']) && !empty($request['id'])){
                    //BUSCAR PERFIL E ATUALIZAR PERFIL
                    $perfil = Perfil::findOrFail($request['id']);
                    $perfil->update($data);
                }else{
                    //CRIAR NOVO PERFIL
                    Perfil::create($data);
                }
                //CONSOLIDAR OPERAÇÃO
                DB::commit();
                //RETORNAR MENSAGEM DE SUCCESSO
                return response()->json(['message' => 'Perfil salvo com sucesso.'], 200);
            }
            //DESFAZER OPERAÇÃO
            DB::rollback();
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao tentar salvar o Perfil.'], 400);
        }catch(\Exception $e) {
            //DESFAZER OPERAÇÃO
            DB::rollback();
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro ao Salvar][Perfil]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao tentar salvar o Perfil.'], 400);
        }
    }

    public function deletar($id){
        //INICIALIZAR TRANSAÇÃO NO DB
        DB::beginTransaction();
        try{
            //VERIFICAR SE EXISTE UM ID
            if(!empty($id)){
                //BUSCAR PERFIL E EXCLUIR
                $perfil = Perfil::findOrFail($id);
                $perfil->delete();
                //CONSOLIDAR OPERAÇÃO
                DB::commit();
                //RETORNAR MENSAGEM DE SUCCESSO
                return response()->json(['message' => 'Perfil deletado com sucesso.'], 200);
            }
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Perfil não encontrado!'], 400);
        }catch(\Exception $e) {
            //DESFAZER OPERAÇÃO
            DB::rollback();
            //CAPTURAR ERRO E ENVIAR PARA O LOG
            Log::channel('registro')->error("[Erro ao Deletar][Perfil]", ['[message]' => $e->getMessage(), '[error]' => $e->getTraceAsString()]);
            //RETORNAR MENSAGEM DE ERRO
            return response()->json(['message' => 'Houve um erro ao tentar excluir o Perfil.'], 400);
        }
    }
}
