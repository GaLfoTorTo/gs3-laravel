<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\HomeController;

//ROTA DE LOGIN
Route::post('/login', [AuthController::class, 'login'])->name('login');

//ROTAS INTERNAS
Route::middleware('auth:api')->group(function () {
    //ROTA HOME 
    Route::post('/home', [HomeController::class, 'home'])->name('home');
    //ROTAS DE PERFIL
    Route::post('/perfis/editar/{id}', [PerfilController::class, 'editar'])->name('perfis.editar');
    Route::post('/perfis/atribuir', [PerfilController::class, 'atribuir'])->name('perfis.atribuir');
    Route::post('/perfis/salvar', [PerfilController::class, 'salvar'])->name('perfis.salvar');
    Route::post('/perfis/deletar/{id}', [PerfilController::class, 'deletar'])->name('perfis.deletar');
    //ROTAS DE USUARIOS
    Route::post('/usuarios/editar/{id}', [UsuarioController::class, 'editar'])->name('usuarios.editar');
    Route::post('/usuarios/salvar', [UsuarioController::class, 'salvar'])->name('usuarios.salvar');
    Route::post('/usuarios/deletar/{id}', [UsuarioController::class, 'deletar'])->name('usuarios.deletar');
});